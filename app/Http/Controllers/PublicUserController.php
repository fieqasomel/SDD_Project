<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicUser;
use App\Models\Inquiry;
use App\Models\Complaint;
use App\Models\Agency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PublicUserController extends Controller
{
    public function showForm()
    {
        return view('Registration.register_user');
    }

    public function register(Request $request)
    {
        $request->validate([
            'PU_Name' => 'required|string',
            'PU_IC' => 'required|digits:12',
            'PU_Age' => 'required|integer|min:1',
            'PU_Address' => 'required|string',
            'PU_Email' => 'required|email|unique:public_users,PU_Email',
            'PU_PhoneNum' => 'required|digits_between:10,11',
            'PU_Gender' => 'required|in:Male,Female',
            'PU_Password' => 'required|min:6',
        ], [
            // Optional: Custom error messages
        ], [
            // 👇 This is what fixes your issue
            'PU_Name' => 'name',
            'PU_IC' => 'ic',
            'PU_Age' => 'age',
            'PU_Address' => 'address',
            'PU_Email' => 'email',
            'PU_PhoneNum' => 'phone',
            'PU_Gender' => 'gender',
            'PU_Password' => 'password',
        ]);


        // Generate unique PU_ID
        $lastUser = PublicUser::orderBy('PU_ID', 'desc')->first();
        $nextId = 'PU00001';
        if ($lastUser) {
            $num = intval(substr($lastUser->PU_ID, 2)) + 1;
            $nextId = 'PU' . str_pad($num, 5, '0', STR_PAD_LEFT);
        }

        // Store to database
        PublicUser::create([
            'PU_ID' => $nextId,
            'PU_Name' => $request->PU_Name,
            'PU_IC' => $request->PU_IC,
            'PU_Age' => $request->PU_Age,
            'PU_Address' => $request->PU_Address,
            'PU_Email' => $request->PU_Email,
            'PU_PhoneNum' => $request->PU_PhoneNum,
            'PU_Gender' => $request->PU_Gender,
            'PU_Password' => Hash::make($request->PU_Password),
        ]);

        return redirect()->back()->with('success', "Registration successful! Your ID is: $nextId");
    }

    public function PublicUserRegistration()
    {
        return view('Registration.PublicUserRegistration');
    }


    public function dashboard()
    {
        $user = Auth::guard('publicuser')->user();

        // Calculate inquiry statistics for the user
        $stats = [
            'total' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'resolved' => 0
        ];

        if ($user) {
            // Get all inquiries for this user
            $inquiries = \App\Models\Inquiry::where('PU_ID', $user->PU_ID)->get();

            // Calculate statistics
            $stats['total'] = $inquiries->count();
            $stats['pending'] = $inquiries->where('I_Status', \App\Models\Inquiry::STATUS_PENDING)->count();
            $stats['in_progress'] = $inquiries->where('I_Status', \App\Models\Inquiry::STATUS_IN_PROGRESS)->count();
            $stats['resolved'] = $inquiries->where('I_Status', \App\Models\Inquiry::STATUS_RESOLVED)->count();
        }

        return view('Dashboard.PublicUserDashboard', compact('user', 'stats'));
    }

    /**
     * Display assignments for the authenticated public user
     */
    public function myAssignments(Request $request)
    {
        $user = Auth::guard('publicuser')->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your assignments.');
        }

        // Get all inquiries for this user that have been assigned
        $query = Inquiry::with(['complaint.agency', 'complaint.mcmc'])
            ->where('PU_ID', $user->PU_ID)
            ->whereHas('complaint'); // Only inquiries that have assignments

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('I_Title', 'like', "%{$request->search}%")
                  ->orWhere('I_Description', 'like', "%{$request->search}%")
                  ->orWhere('I_ID', 'like', "%{$request->search}%");
            });
        }

        // Apply agency filter
        if ($request->filled('agency')) {
            $query->whereHas('complaint', function($q) use ($request) {
                $q->where('A_ID', $request->agency);
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('I_Status', $request->status);
        }

        // Apply date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereHas('complaint', function($q) use ($request) {
                $q->whereBetween('C_AssignedDate', [$request->date_from, $request->date_to]);
            });
        }

        $assignments = $query->orderBy('I_Date', 'desc')->paginate(10);

        // Get agencies for filter dropdown (only agencies that have assignments for this user)
        $agencies = Agency::whereHas('complaints.inquiry', function($q) use ($user) {
            $q->where('PU_ID', $user->PU_ID);
        })->orderBy('A_Name')->get();

        // Get assignment statistics  
        $stats = [
            'total_inquiries' => Inquiry::where('PU_ID', $user->PU_ID)->count(),
            'assigned' => Inquiry::where('PU_ID', $user->PU_ID)->whereHas('complaint')->count(),
            'pending' => Inquiry::where('PU_ID', $user->PU_ID)->where(function($q) {
                $q->where('I_Status', 'like', '%pending%')
                  ->orWhere('I_Status', 'Pending');
            })->count(),
            'in_progress' => Inquiry::where('PU_ID', $user->PU_ID)->where(function($q) {
                $q->where('I_Status', 'like', '%progress%')
                  ->orWhere('I_Status', 'In Progress');
            })->count(),
            'resolved' => Inquiry::where('PU_ID', $user->PU_ID)->where(function($q) {
                $q->where('I_Status', 'like', '%resolved%')
                  ->orWhere('I_Status', 'Resolved');
            })->count(),
            'closed' => Inquiry::where('PU_ID', $user->PU_ID)->where(function($q) {
                $q->where('I_Status', 'like', '%closed%')
                  ->orWhere('I_Status', 'Closed');
            })->count(),
        ];

        return view('ManageAssignment.ManageAssignments', compact('assignments', 'agencies', 'stats'));
    }
}
