<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Progress;
use App\Models\Inquiry;
use Carbon\Carbon;

class ProgressController extends Controller
{
    public function edit($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('ManageProgress.UpdateInquiryProgress', compact('inquiry'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'P_Status' => 'required',
            'P_Description' => 'required|string',
            'P_Date' => 'required|date',
        ]);

        $progress = new Progress();
        $progress->I_ID = $id;
        $progress->P_Status = $request->P_Status;
        $progress->P_Description = $request->P_Description;
        $progress->P_Date = $request->P_Date;
        $progress->save();

        // Optional: Update Inquiry status
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->I_Status = $request->P_Status;
        $inquiry->save();

        return redirect()->route('inquiry.progress.edit', $id)
                         ->with('success', 'Progress updated successfully.');
    }

    public function view(Request $request, $id)
    {
        $progressRecords = Progress::where('I_ID', $id)
                            ->orderByDesc('P_Date')
                            ->get();

        return view('ManageProgress.ViewInquiryProgress', compact('progressRecords'));
    }

    public function searchInquiriesByStatus(Request $request)
    {
        $status = $request->input('status');

        // Check user type
        if (Auth::guard('publicuser')->check()) {
            $user = Auth::guard('publicuser')->user();

            // Public User: only see their own inquiries
            $query = Inquiry::where('public_user_id', $user->id);
        } elseif (Auth::guard('mcmc')->check()) {
            // MCMC: can view all inquiries
            $query = Inquiry::query();
        } else {
            abort(403, 'Unauthorized');
        }

        if ($status && $status !== 'All') {
            $query->where('status', $status);
        }

        $inquiries = $query->orderBy('created_at', 'desc')->get();

        return view('ManageProgress.SearchByStatus', compact('inquiries', 'status'));
    }
    public function showFeedbackForm()
    {
    $inquiries = Inquiry::all();
    return view('ManageProgress.ProvideFeedback', compact('inquiries'));
    }

    public function submitFeedback(Request $request)
    {
    $request->validate([
        'inquiry_id' => 'required|exists:inquiry,I_ID',
        'p_title' => 'required|string|max:255',
        'p_description' => 'required|string',
    ]);

    Progress::create([
        'P_ID' => 'P' . uniqid(),
        'I_ID' => $request->inquiry_id,
        'C_ID' => null,
        'P_Title' => $request->p_title,
        'P_Description' => $request->p_description,
        'P_Date' => now(),
        'P_Status' => 'In Progress',
    ]);

    return redirect()->back()->with('success', 'Feedback successfully submitted.');
    }
    public function viewNotifications()
    {
    // Join progress with inquiries and complaints for meaningful notifications
    $notifications = Progress::select(
            'progress.P_Date',
            'progress.P_Status',
            'progress.P_Title',
            'progress.P_Description',
            'inquiry.I_ID',
            'inquiry.I_Title',
            'complaint.A_ID'
        )
        ->join('inquiry', 'progress.I_ID', '=', 'inquiry.I_ID')
        ->join('complaint', 'progress.C_ID', '=', 'complaint.C_ID')
        ->orderBy('progress.P_Date', 'desc')
        ->get();

    return view('Notification.InquiryNotification', compact('notifications'));
    }

    use Carbon\Carbon;

   public function viewMcmcAlerts()
   {
    $now = Carbon::now();

    // Simulate "overdue" alerts: inquiries not updated for 7+ days
    $overdueAlerts = Progress::select(
            'progress.P_Date',
            'inquiry.I_ID',
            'inquiry.I_Title'
        )
        ->join('inquiry', 'progress.I_ID', '=', 'inquiry.I_ID')
        ->where('progress.P_Status', '!=', 'Closed')
        ->where('progress.P_Date', '<', $now->copy()->subDays(7))
        ->orderBy('progress.P_Date', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'time' => $item->P_Date,
                'message' => "Inquiry #{$item->I_ID} is overdue. No action taken for 7 days.",
                'type' => 'Late Inquiry'
            ];
        });

    // Simulate static "system error" alert for example
    $systemAlerts = collect([
        [
            'time' => Carbon::parse('2025-06-13 23:45'),
            'message' => 'System failed to log inquiry submission. Error Code: 502',
            'type' => 'System Failure'
        ]
    ]);

    $alerts = $overdueAlerts->merge($systemAlerts)->sortByDesc('time');

    return view('Notification.McmcAlerts', compact('alerts'));
    }

}
