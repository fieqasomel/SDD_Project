<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Complaint;
use App\Models\Inquiry;

class NotificationController extends Controller
{
    /**
     * Display notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Get notifications based on user type
        $notifications = collect([]);
        
        if ($userType === 'mcmc') {
            // MCMC sees rejection notifications
            $notifications = Notification::where('P_ID', $user->M_ID)
                ->orderBy('N_Timestamp', 'desc')
                ->paginate(20);
        } elseif ($userType === 'agency') {
            // Agency sees assignment notifications
            $notifications = $this->getAgencyNotifications($user);
        }
        
        return view('Notification.ViewNotification', compact('notifications', 'userType'));
    }
    
    /**
     * Get notifications for agency users
     */
    private function getAgencyNotifications($agency)
    {
        // Get recent assignments for this agency
        $assignments = Complaint::with(['inquiry.publicUser', 'mcmc'])
            ->where('A_ID', $agency->A_ID)
            ->where('C_VerificationStatus', 'Pending')
            ->orderBy('C_AssignedDate', 'desc')
            ->get();
            
        // Convert to notification format
        $notifications = $assignments->map(function($assignment) {
            return (object) [
                'N_ID' => 'ASSIGN_' . $assignment->C_ID,
                'N_Message' => "New inquiry assigned: \"{$assignment->inquiry->I_Title}\" by {$assignment->mcmc->M_Name}",
                'N_Timestamp' => $assignment->C_AssignedDate,
                'N_Status' => 'UNREAD',
                'type' => 'assignment',
                'complaint_id' => $assignment->C_ID,
                'inquiry_id' => $assignment->I_ID
            ];
        });
        
        return $notifications;
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }
    
    /**
     * Get recent activity for dashboard
     */
    public function getRecentActivity()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        $activities = collect([]);
        
        if ($userType === 'mcmc') {
            // MCMC recent activities
            $activities = $this->getMCMCRecentActivity($user);
        } elseif ($userType === 'agency') {
            // Agency recent activities
            $activities = $this->getAgencyRecentActivity($user);
        }
        
        return $activities->take(5); // Return latest 5 activities
    }
    
    /**
     * Get MCMC recent activities
     */
    private function getMCMCRecentActivity($user)
    {
        $activities = collect([]);
        
        // Recent rejection notifications
        $rejections = Notification::where('P_ID', $user->M_ID)
            ->where('N_Status', 'UNREAD')
            ->orderBy('N_Timestamp', 'desc')
            ->take(3)
            ->get();
            
        foreach ($rejections as $rejection) {
            $activities->push([
                'type' => 'rejection',
                'icon' => 'fas fa-times-circle',
                'color' => 'red',
                'message' => $rejection->N_Message,
                'time' => $rejection->N_Timestamp->diffForHumans(),
                'link' => route('assignments.rejected')
            ]);
        }
        
        // Recent assignments made
        $recentAssignments = Complaint::with(['inquiry', 'agency'])
            ->where('M_ID', $user->M_ID)
            ->orderBy('C_AssignedDate', 'desc')
            ->take(2)
            ->get();
            
        foreach ($recentAssignments as $assignment) {
            $activities->push([
                'type' => 'assignment',
                'icon' => 'fas fa-tasks',
                'color' => 'blue',
                'message' => "Assigned inquiry \"{$assignment->inquiry->I_Title}\" to {$assignment->agency->A_Name}",
                'time' => $assignment->C_AssignedDate->diffForHumans(),
                'link' => route('assignments.view', $assignment->C_ID)
            ]);
        }
        
        return $activities->sortByDesc('time');
    }
    
    /**
     * Get Agency recent activities
     */
    private function getAgencyRecentActivity($user)
    {
        $activities = collect([]);
        
        // Recent assignments received
        $recentAssignments = Complaint::with(['inquiry.publicUser', 'mcmc'])
            ->where('A_ID', $user->A_ID)
            ->orderBy('C_AssignedDate', 'desc')
            ->take(3)
            ->get();
            
        foreach ($recentAssignments as $assignment) {
            $statusColor = match($assignment->C_VerificationStatus) {
                'Pending' => 'yellow',
                'Accepted' => 'green',
                'Rejected' => 'red',
                default => 'gray'
            };
            
            $activities->push([
                'type' => 'assignment_received',
                'icon' => 'fas fa-inbox',
                'color' => $statusColor,
                'message' => "New assignment: \"{$assignment->inquiry->I_Title}\" from MCMC",
                'time' => $assignment->C_AssignedDate->diffForHumans(),
                'link' => route('assignments.view', $assignment->C_ID)
            ]);
        }
        
        // Recent verifications made
        $recentVerifications = Complaint::with(['inquiry'])
            ->where('A_ID', $user->A_ID)
            ->whereNotNull('C_VerificationDate')
            ->orderBy('C_VerificationDate', 'desc')
            ->take(2)
            ->get();
            
        foreach ($recentVerifications as $verification) {
            $activities->push([
                'type' => 'verification',
                'icon' => $verification->C_VerificationStatus === 'Accepted' ? 'fas fa-check-circle' : 'fas fa-times-circle',
                'color' => $verification->C_VerificationStatus === 'Accepted' ? 'green' : 'red',
                'message' => "Verified inquiry \"{$verification->inquiry->I_Title}\" as {$verification->C_VerificationStatus}",
                'time' => $verification->C_VerificationDate->diffForHumans(),
                'link' => route('assignments.view', $verification->C_ID)
            ]);
        }
        
        return $activities->sortByDesc('time');
    }
    
    /**
     * Determine user type
     */
    private function getUserType($user)
    {
        if ($user instanceof \App\Models\PublicUser) {
            return 'publicuser';
        } elseif ($user instanceof \App\Models\Agency) {
            return 'agency';
        } elseif ($user instanceof \App\Models\MCMC) {
            return 'mcmc';
        }
        
        return 'unknown';
    }
}