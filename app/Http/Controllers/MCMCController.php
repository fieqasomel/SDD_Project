<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Inquiry;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\Complaint;
use App\Models\MCMC;
use Carbon\Carbon;

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

    // Agency Management Functions
    public function manageAgencies()
    {
        $agencies = Agency::orderBy('created_at', 'desc')->get();
        return view('ManageAgency.index', compact('agencies'));
    }

    public function createAgency()
    {
        return view('ManageAgency.create');
    }

    public function storeAgency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:10|unique:agency,A_userName',
            'address' => 'required|string|max:225',
            'email' => 'required|string|email|max:50|unique:agency,A_Email',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Generate unique ID
        $lastAgency = Agency::orderBy('A_ID', 'desc')->first();
        $newId = $lastAgency ? 'A' . str_pad((intval(substr($lastAgency->A_ID, 1)) + 1), 6, '0', STR_PAD_LEFT) : 'A000001';

        // Generate random password
        $password = Str::random(12);

        $agency = Agency::create([
            'A_ID' => $newId,
            'A_Name' => $request->name,
            'A_userName' => $request->username,
            'A_Address' => $request->address,
            'A_Email' => $request->email,
            'A_PhoneNum' => $request->phone,
            'A_Category' => $request->category,
            'A_Password' => $password,
        ]);

        // Send email with login credentials
        $this->sendAgencyCredentials($agency, $password);

        return redirect()->route('mcmc.agencies.index')->with('success', 'Agency registered successfully! Login credentials sent to email.');
    }

    public function editAgency(Agency $agency)
    {
        return view('ManageAgency.edit', compact('agency'));
    }

    public function updateAgency(Request $request, Agency $agency)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:10|unique:agency,A_userName,' . $agency->A_ID . ',A_ID',
            'address' => 'required|string|max:225',
            'email' => 'required|string|email|max:50|unique:agency,A_Email,' . $agency->A_ID . ',A_ID',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $agency->update([
            'A_Name' => $request->name,
            'A_userName' => $request->username,
            'A_Address' => $request->address,
            'A_Email' => $request->email,
            'A_PhoneNum' => $request->phone,
            'A_Category' => $request->category,
        ]);

        return redirect()->route('mcmc.agencies.index')->with('success', 'Agency updated successfully!');
    }

    public function destroyAgency(Agency $agency)
    {
        $agency->delete();
        return redirect()->route('mcmc.agencies.index')->with('success', 'Agency deleted successfully!');
    }

    public function resetAgencyPassword(Agency $agency)
    {
        $newPassword = Str::random(12);
        $agency->A_Password = $newPassword;
        $agency->save();

        // Send email with new password
        $this->sendAgencyCredentials($agency, $newPassword);

        return redirect()->route('mcmc.agencies.index')->with('success', 'Password reset successfully! New credentials sent to email.');
    }

    // User Data Access Functions
    public function viewAllUsers(Request $request)
    {
        $query = PublicUser::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('PU_Name', 'like', '%' . $request->search . '%')
                  ->orWhere('PU_Email', 'like', '%' . $request->search . '%')
                  ->orWhere('PU_IC', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('gender')) {
            $query->where('PU_Gender', $request->gender);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('ManageUsers.index', compact('users'));
    }

    public function viewUserDetails(PublicUser $user)
    {
        $userInquiries = Inquiry::where('PU_ID', $user->PU_ID)->orderBy('I_Date', 'desc')->get();
        return view('ManageUsers.show', compact('user', 'userInquiries'));
    }

    // Reporting and Analytics Functions
    public function generateUserReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $userType = $request->input('user_type', 'all');
        $agency = $request->input('agency');

        $data = [];

        if ($userType === 'all' || $userType === 'public') {
            $publicUsers = PublicUser::whereBetween('created_at', [$startDate, $endDate])->get();
            $data['public_users'] = $publicUsers;
        }

        if ($userType === 'all' || $userType === 'agency') {
            $agencyQuery = Agency::whereBetween('created_at', [$startDate, $endDate]);
            if ($agency) {
                $agencyQuery->where('A_ID', $agency);
            }
            $agencies = $agencyQuery->get();
            $data['agencies'] = $agencies;
        }

        // Summary statistics
        $stats = [
            'total_public_users' => $userType !== 'agency' ? PublicUser::whereBetween('created_at', [$startDate, $endDate])->count() : 0,
            'total_agencies' => $userType !== 'public' ? Agency::whereBetween('created_at', [$startDate, $endDate])->count() : 0,
            'total_inquiries' => Inquiry::whereBetween('I_Date', [$startDate, $endDate])->count(),
            'active_inquiries' => Inquiry::whereIn('I_Status', ['Pending', 'In Progress'])->whereBetween('I_Date', [$startDate, $endDate])->count(),
        ];

        return view('Reports.user-report', compact('data', 'stats', 'startDate', 'endDate', 'userType'));
    }

    public function downloadUserReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $userType = $request->input('user_type', 'all');
        $format = $request->input('format', 'pdf');

        // Generate report data
        $data = $this->getReportData($startDate, $endDate, $userType);

        if ($format === 'excel') {
            return $this->downloadExcelReport($data, $startDate, $endDate);
        } else {
            return $this->downloadPdfReport($data, $startDate, $endDate);
        }
    }

    // Activity Logs
    public function viewActivityLogs(Request $request)
    {
        // Implementation depends on how you want to track activity
        // For now, we can show recent inquiries and assignments as activity
        $activities = collect();

        // Recent inquiries
        $recentInquiries = Inquiry::with('publicUser')
            ->orderBy('I_Date', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($inquiry) {
                return [
                    'type' => 'inquiry',
                    'action' => 'Inquiry submitted',
                    'user' => $inquiry->publicUser->PU_Name ?? 'Unknown',
                    'details' => $inquiry->I_Title,
                    'timestamp' => $inquiry->I_Date,
                ];
            });

        // Recent assignments
        $recentAssignments = Complaint::with(['inquiry', 'agency'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($assignment) {
                return [
                    'type' => 'assignment',
                    'action' => 'Inquiry assigned',
                    'user' => 'MCMC Staff',
                    'details' => 'Assigned to ' . ($assignment->agency->A_Name ?? 'Unknown Agency'),
                    'timestamp' => $assignment->created_at,
                ];
            });

        $activities = $activities->merge($recentInquiries)->merge($recentAssignments);
        $activities = $activities->sortByDesc('timestamp');

        return view('ManageActivity.index', compact('activities'));
    }

    // Private helper methods
    private function sendAgencyCredentials($agency, $password)
    {
        // Implementation for sending email with credentials
        // You can use Laravel Mail facade here
        try {
            Mail::send('emails.agency-credentials', [
                'agency' => $agency,
                'password' => $password,
                'loginUrl' => route('login')
            ], function ($message) use ($agency) {
                $message->to($agency->A_Email, $agency->A_Name);
                $message->subject('Your Agency Login Credentials - MCMC System');
            });
        } catch (\Exception $e) {
            // Log the error
            logger('Failed to send agency credentials email: ' . $e->getMessage());
        }
    }

    private function getReportData($startDate, $endDate, $userType)
    {
        $data = [];

        if ($userType === 'all' || $userType === 'public') {
            $data['public_users'] = PublicUser::whereBetween('created_at', [$startDate, $endDate])->get();
        }

        if ($userType === 'all' || $userType === 'agency') {
            $data['agencies'] = Agency::whereBetween('created_at', [$startDate, $endDate])->get();
        }

        $data['inquiries'] = Inquiry::whereBetween('I_Date', [$startDate, $endDate])->get();

        return $data;
    }

    private function downloadExcelReport($data, $startDate, $endDate)
    {
        // Implementation for Excel export
        // You would need to install PhpSpreadsheet package
        // For now, return a simple CSV
        
        $filename = 'user_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, ['Type', 'Name', 'Email', 'Registration Date']);
            
            // Write public users
            if (isset($data['public_users'])) {
                foreach ($data['public_users'] as $user) {
                    fputcsv($file, [
                        'Public User',
                        $user->PU_Name,
                        $user->PU_Email,
                        $user->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            }
            
            // Write agencies
            if (isset($data['agencies'])) {
                foreach ($data['agencies'] as $agency) {
                    fputcsv($file, [
                        'Agency',
                        $agency->A_Name,
                        $agency->A_Email,
                        $agency->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function downloadPdfReport($data, $startDate, $endDate)
    {
        // Implementation for PDF export
        // You would need to install a PDF package like DomPDF
        // For now, return HTML that can be printed as PDF
        
        return view('Reports.pdf-user-report', compact('data', 'startDate', 'endDate'));
    }
}