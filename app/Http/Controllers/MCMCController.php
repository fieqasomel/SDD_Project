<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\Complaint;

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
}