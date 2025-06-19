<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Inquiry;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\Complaint;
use App\Models\MCMC;

class MCMCController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('mcmc')->user();
        
        // Get statistics for the dashboard
        $stats = [
            'total_users' => PublicUser::count(),
            'total_agencies' => Agency::count(),
            'total_inquiries' => Inquiry::count(),
            'total_assignments' => Complaint::count(),
            'pending_inquiries' => Inquiry::where('I_Status', 'Pending')->count(),
            'in_progress_inquiries' => Inquiry::where('I_Status', 'In Progress')->count(),
            'resolved_inquiries' => Inquiry::where('I_Status', 'Resolved')->count(),
            'this_month_inquiries' => Inquiry::whereMonth('I_Date', now()->month)
                                           ->whereYear('I_Date', now()->year)
                                           ->count(),
        ];
        
        return view('Dashboard.MCMCDashboard', compact('user', 'stats'));
    }

    /**
     * Display a listing of agencies
     */
    public function manageAgencies(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            $query = Agency::query();

            // Apply search filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('A_Name', 'like', "%{$searchTerm}%")
                      ->orWhere('A_Email', 'like', "%{$searchTerm}%")
                      ->orWhere('A_Category', 'like', "%{$searchTerm}%");
                });
            }

            // Apply category filter
            if ($request->filled('category')) {
                $query->where('A_Category', $request->category);
            }

            $agencies = $query->orderBy('A_Name')->get();
            
            // Get unique categories for filter dropdown
            $categories = Agency::distinct()->pluck('A_Category')->filter()->sort();

            return view('ManageAgency.ManageAgencies', compact('agencies', 'categories'));

        } catch (\Exception $e) {
            \Log::error('Error in manageAgencies: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error loading agencies.');
        }
    }

    /**
     * Show the form for creating a new agency
     */
    public function createAgency()
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        return view('ManageAgency.CreateAgency');
    }

    /**
     * Store a newly created agency
     */
    public function storeAgency(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        $validator = Validator::make($request->all(), [
            'agency_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:10', 'unique:agency,A_userName'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:agency,A_Email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:225'],
            'category' => ['required', 'string', 'max:50'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Generate unique ID
            $lastAgency = Agency::orderBy('A_ID', 'desc')->first();
            $newId = $lastAgency ? 'A' . str_pad((intval(substr($lastAgency->A_ID, 1)) + 1), 6, '0', STR_PAD_LEFT) : 'A000001';

            // Create the Agency
            $agency = Agency::create([
                'A_ID' => $newId,
                'A_Name' => $request->agency_name,
                'A_userName' => $request->username,
                'A_Email' => $request->email,
                'A_Password' => $request->password, // Will be hashed by mutator
                'A_PhoneNum' => $request->phone,
                'A_Address' => $request->address,
                'A_Category' => $request->category,
            ]);

            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                $filename = $newId . '_' . time() . '.' . $request->file('profile_photo')->getClientOriginalExtension();
                $request->file('profile_photo')->storeAs('profile-photos', $filename, 'public');
                $agency->A_ProfilePicture = $filename;
                $agency->save();
            }

            return redirect()->route('mcmc.agencies.index')->with('success', 'Agency created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating agency: ' . $e->getMessage());
            return back()->with('error', 'Error creating agency. Please try again.')->withInput();
        }
    }

    /**
     * Show the form for editing an agency
     */
    public function editAgency(Agency $agency)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        return view('ManageAgency.EditAgency', compact('agency'));
    }

    /**
     * Update the specified agency
     */
    public function updateAgency(Request $request, Agency $agency)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        $validator = Validator::make($request->all(), [
            'agency_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:10', 'unique:agency,A_userName,' . $agency->A_ID . ',A_ID'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:agency,A_Email,' . $agency->A_ID . ',A_ID'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:225'],
            'category' => ['required', 'string', 'max:50'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Update agency data
            $agency->update([
                'A_Name' => $request->agency_name,
                'A_userName' => $request->username,
                'A_Email' => $request->email,
                'A_PhoneNum' => $request->phone,
                'A_Address' => $request->address,
                'A_Category' => $request->category,
            ]);

            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                $filename = $agency->A_ID . '_' . time() . '.' . $request->file('profile_photo')->getClientOriginalExtension();
                $request->file('profile_photo')->storeAs('profile-photos', $filename, 'public');
                $agency->A_ProfilePicture = $filename;
                $agency->save();
            }

            return redirect()->route('mcmc.agencies.index')->with('success', 'Agency updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating agency: ' . $e->getMessage());
            return back()->with('error', 'Error updating agency. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified agency
     */
    public function destroyAgency(Agency $agency)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            // Check if agency has any assignments
            $hasAssignments = Complaint::where('A_ID', $agency->A_ID)->exists();
            
            if ($hasAssignments) {
                return redirect()->route('mcmc.agencies.index')
                    ->with('error', 'Cannot delete agency with existing assignments. Please reassign or complete all assignments first.');
            }

            $agency->delete();
            return redirect()->route('mcmc.agencies.index')->with('success', 'Agency deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Error deleting agency: ' . $e->getMessage());
            return redirect()->route('mcmc.agencies.index')->with('error', 'Error deleting agency.');
        }
    }

    /**
     * Reset agency password
     */
    public function resetAgencyPassword(Request $request, Agency $agency)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $agency->A_Password = $request->new_password; // Will be hashed by mutator
            $agency->save();

            return redirect()->route('mcmc.agencies.index')->with('success', 'Agency password reset successfully!');

        } catch (\Exception $e) {
            \Log::error('Error resetting agency password: ' . $e->getMessage());
            return back()->with('error', 'Error resetting password. Please try again.');
        }
    }

    /**
     * View all users
     */
    public function viewAllUsers(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            $query = PublicUser::query();

            // Apply search filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('PU_Name', 'like', "%{$searchTerm}%")
                      ->orWhere('PU_Email', 'like', "%{$searchTerm}%")
                      ->orWhere('PU_IC', 'like', "%{$searchTerm}%");
                });
            }

            $users = $query->orderBy('PU_Name')->get();

            return view('ManageUser.ViewAllUsers', compact('users'));

        } catch (\Exception $e) {
            \Log::error('Error in viewAllUsers: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error loading users.');
        }
    }

    /**
     * View user details
     */
    public function viewUserDetails($userId)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            $user = PublicUser::where('PU_ID', $userId)->first();
            
            if (!$user) {
                return redirect()->route('mcmc.users.index')->with('error', 'User not found.');
            }

            // Get user's inquiries
            $inquiries = Inquiry::where('PU_ID', $userId)->orderBy('I_Date', 'desc')->get();

            return view('ManageUser.ViewUserDetails', compact('user', 'inquiries'));

        } catch (\Exception $e) {
            \Log::error('Error in viewUserDetails: ' . $e->getMessage());
            return redirect()->route('mcmc.users.index')->with('error', 'Error loading user details.');
        }
    }

    /**
     * Generate user report
     */
    public function generateUserReport(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            $users = PublicUser::with(['inquiries'])->get();
            
            $stats = [
                'total_users' => $users->count(),
                'users_with_inquiries' => $users->filter(function($user) {
                    return $user->inquiries->count() > 0;
                })->count(),
                'total_inquiries' => $users->sum(function($user) {
                    return $user->inquiries->count();
                }),
            ];

            return view('Reports.UserReport', compact('users', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Error generating user report: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error generating report.');
        }
    }

    /**
     * Download user report
     */
    public function downloadUserReport()
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        // This would typically generate a PDF or Excel file
        // For now, redirect back with a message
        return redirect()->route('mcmc.reports.index')->with('info', 'Download functionality will be implemented soon.');
    }

    /**
     * View activity logs
     */
    public function viewActivityLogs(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            // Get recent inquiries with MCMC processing information as activity logs
            $query = Inquiry::with(['publicUser', 'mcmcProcessor'])
                           ->whereNotNull('mcmc_processed_by')
                           ->orderBy('mcmc_processed_at', 'desc');

            // Apply filters
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('mcmc_processed_at', [$request->date_from, $request->date_to]);
            }

            if ($request->filled('mcmc_staff')) {
                $query->where('mcmc_processed_by', $request->mcmc_staff);
            }

            $activities = $query->paginate(20);
            
            // Get MCMC staff for filter
            $mcmcStaff = MCMC::select('M_ID', 'M_Name')->get();

            return view('Reports.ActivityLogs', compact('activities', 'mcmcStaff'));

        } catch (\Exception $e) {
            \Log::error('Error in viewActivityLogs: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error loading activity logs.');
        }
    }
}