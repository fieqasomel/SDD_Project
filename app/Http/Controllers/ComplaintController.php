<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Agency;
use App\Models\Complaint;
use App\Models\MCMC;
use App\Models\PublicUser;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    /**
     * Display the assignment management dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Handle different user types
        if ($userType === 'mcmc') {
            // MCMC users see all assignments with administrative functions
            $query = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc']);
            
            // Apply search filters
            if ($request->filled('search')) {
                $query->whereHas('inquiry', function($q) use ($request) {
                    $q->where('I_Title', 'like', "%{$request->search}%")
                      ->orWhere('I_Description', 'like', "%{$request->search}%");
                });
            }
            
            if ($request->filled('agency')) {
                $query->where('A_ID', $request->agency);
            }
            
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            
            $assignments = $query->orderBy('C_AssignedDate', 'desc')->paginate(10);
            
            // Get unassigned inquiries (for assign button)
            $unassignedInquiries = Inquiry::with('publicUser')
                                         ->where('I_Status', 'Pending')
                                         ->whereNotExists(function ($query) {
                                             $query->select(DB::raw(1))
                                                   ->from('complaint')
                                                   ->whereColumn('complaint.I_ID', 'inquiry.I_ID');
                                         })
                                         ->orderBy('I_Date', 'desc')
                                         ->limit(10)
                                         ->get();
                                         
        } elseif ($userType === 'agency') {
            // Agency users see only assignments for their agency
            $query = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])
                             ->where('A_ID', $user->A_ID);
            
            // Apply search filters
            if ($request->filled('search')) {
                $query->whereHas('inquiry', function($q) use ($request) {
                    $q->where('I_Title', 'like', "%{$request->search}%")
                      ->orWhere('I_Description', 'like', "%{$request->search}%");
                });
            }
            
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            
            $assignments = $query->orderBy('C_AssignedDate', 'desc')->paginate(10);
            $unassignedInquiries = collect(); // Empty collection for agency users
            
        } elseif ($userType === 'public') {
            // Public users see their own assignments
            $query = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])
                             ->whereHas('inquiry', function($q) use ($user) {
                                 $q->where('PU_ID', $user->PU_ID);
                             });
            
            // Apply search filters
            if ($request->filled('search')) {
                $query->whereHas('inquiry', function($q) use ($request) {
                    $q->where('I_Title', 'like', "%{$request->search}%")
                      ->orWhere('I_Description', 'like', "%{$request->search}%");
                });
            }
            
            if ($request->filled('agency')) {
                $query->where('A_ID', $request->agency);
            }
            
            if ($request->filled('status')) {
                $query->whereHas('inquiry', function($q) use ($request) {
                    $q->where('I_Status', $request->status);
                });
            }
            
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            
            $assignments = $query->orderBy('C_AssignedDate', 'desc')->paginate(10);
            $unassignedInquiries = collect(); // Empty collection for public users
            
        } else {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to manage assignments.');
        }
        
        // Get agencies for filter dropdown
        $agencies = Agency::orderBy('A_Name')->get();
        
        // Get statistics based on user type
        $stats = $this->getAssignmentStats($userType, $user);
        
        return view('ManageAssignment.ManageAssignments', compact('assignments', 'agencies', 'stats', 'unassignedInquiries', 'userType'));
    }

    /**
     * Show form to assign inquiry to agency
     */
    public function assignInquiry($inquiry)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to assign inquiries.');
        }
        
        $inquiry = Inquiry::with('publicUser')->findOrFail($inquiry);
        
        // Check if inquiry is already assigned
        if ($inquiry->isAssigned()) {
            return redirect()->route('assignments.index')->with('error', 'This inquiry is already assigned.');
        }
        
        // Get all agencies (they can now handle any inquiry category)
        $agencies = Agency::orderBy('A_Name')->get();
        
        if ($agencies->isEmpty()) {
            return redirect()->route('assignments.index')->with('error', 'No agencies available in the system.');
        }
        return view('ManageAssignment.AssignInquiry', compact('inquiry', 'agencies'));
    }

    /**
     * Store the assignment
     */
    public function storeAssignment(Request $request, $inquiryId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to assign inquiries.');
        }
        
        $request->validate([
            'agency_id' => 'required|exists:agency,A_ID',
            'comment' => 'nullable|string|max:1000'
        ]);
        
        $inquiry = Inquiry::findOrFail($inquiryId);
        
        // Check if inquiry is already assigned
        if ($inquiry->isAssigned()) {
            return redirect()->route('assignments.index')->with('error', 'This inquiry is already assigned.');
        }
        
        $agency = Agency::findOrFail($request->agency_id);
        
        // Verify agency can handle inquiry category (removed restriction)
        // Agencies can now accept all categories of inquiries
        // if (!$agency->canHandle($inquiry->I_Category)) {
        //     return redirect()->back()->with('error', 'Selected agency does not handle this inquiry category.');
        // }
        
        DB::transaction(function () use ($inquiry, $agency, $user, $request) {
            // Create complaint record (assignment)
            $complaint = Complaint::create([
                'C_ID' => Complaint::generateComplaintId(),
                'I_ID' => $inquiry->I_ID,
                'A_ID' => $agency->A_ID,
                'M_ID' => $user->M_ID,
                'C_AssignedDate' => Carbon::now()->toDateString(),
                'C_Comment' => $request->comment,
                'C_History' => '',
                'C_VerificationStatus' => Complaint::VERIFICATION_PENDING
            ]);
            
            // Add initial history entry
            $complaint->addHistory('Inquiry assigned to ' . $agency->A_Name . ' for verification', $user->M_ID, 'MCMC');
            
            // Create notification for agency about new assignment
            Notification::createAssignmentNotification($complaint);
            
            // Keep inquiry status as pending until agency verifies and accepts
            // $inquiry->update(['I_Status' => Inquiry::STATUS_IN_PROGRESS]); // Removed - will be updated after verification
        });
        
        return redirect()->route('assignments.index')->with('success', 'Inquiry successfully assigned to ' . $agency->A_Name . ' for verification. The agency will review if this inquiry falls under their scope.');
    }

    /**
     * Show form to reassign inquiry
     */
    public function reassignInquiry($complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'You do not have permission to reassign inquiries.');
        }
        
        $complaint = Complaint::with(['inquiry.publicUser', 'agency'])->findOrFail($complaintId);
        
        // Get all agencies (excluding current agency)
        $agencies = Agency::where('A_ID', '!=', $complaint->A_ID)
                          ->orderBy('A_Name')
                          ->get();
        
        if ($agencies->isEmpty()) {
            return redirect()->route('assignments.index')->with('error', 'No other agencies available for reassignment.');
        }
        
        return view('ManageAssignment.ReassignedInquiry', compact('complaint', 'agencies'));
    }

    /**
     * Store the reassignment
     */
    public function storeReassignment(Request $request, $complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'You do not have permission to reassign inquiries.');
        }
        
        $request->validate([
            'agency_id' => 'required|exists:agency,A_ID',
            'comment' => 'required|string|max:1000'
        ]);
        
        $complaint = Complaint::with(['inquiry', 'agency'])->findOrFail($complaintId);
        $newAgency = Agency::findOrFail($request->agency_id);
        $oldAgency = $complaint->agency;
        
        // Verify new agency can handle inquiry category (removed restriction)
        // Agencies can now accept all categories of inquiries
        // if (!$newAgency->canHandle($complaint->inquiry->I_Category)) {
        //     return redirect()->back()->with('error', 'Selected agency does not handle this inquiry category.');
        // }
        
        DB::transaction(function () use ($complaint, $newAgency, $oldAgency, $user, $request) {
            // Update complaint record and reset verification status
            $complaint->update([
                'A_ID' => $newAgency->A_ID,
                'C_AssignedDate' => Carbon::now()->toDateString(),
                'C_Comment' => $request->comment,
                'C_VerificationStatus' => Complaint::VERIFICATION_PENDING,
                'C_RejectionReason' => null,
                'C_VerificationDate' => null,
                'C_VerifiedBy' => null
            ]);
            
            // Add history entry
            $complaint->addHistory(
                'Inquiry reassigned from ' . $oldAgency->A_Name . ' to ' . $newAgency->A_Name . '. Reason: ' . $request->comment . '. Verification status reset to pending.',
                $user->M_ID,
                'MCMC'
            );
        });
        
        return redirect()->route('assignments.index')->with('success', 'Inquiry successfully reassigned to ' . $newAgency->A_Name . ' for verification.');
    }

    /**
     * View assigned inquiry details
     */
    public function viewAssignedInquiry($complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $complaint = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])->findOrFail($complaintId);
        
        // Check permissions
        if (!$this->canViewAssignment($complaint, $user, $userType)) {
            return redirect()->route('assignments.index')->with('error', 'You do not have permission to view this assignment.');
        }
        
        return view('ManageAssignment.ViewAssignedInquiry', compact('complaint', 'userType'));
    }

    /**
     * Review inquiry (for agencies)
     */
    public function reviewInquiry($complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'agency') {
            return redirect()->route('assignments.index')->with('error', 'Only agencies can review assigned inquiries.');
        }
        
        $complaint = Complaint::with(['inquiry.publicUser', 'agency'])->findOrFail($complaintId);
        
        // Check if this agency is assigned to this complaint
        if ($complaint->A_ID !== $user->A_ID) {
            return redirect()->route('assignments.index')->with('error', 'You can only review inquiries assigned to your agency.');
        }
        
        return view('ManageAssignment.ReviewInquiry', compact('complaint'));
    }

    /**
     * Update inquiry review
     */
    public function updateReview(Request $request, $complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'agency') {
            return redirect()->route('assignments.index')->with('error', 'Only agencies can update inquiry reviews.');
        }
        
        $request->validate([
            'status' => 'required|string|in:In Progress,Resolved,Closed',
            'comment' => 'required|string|max:1000'
        ]);
        
        $complaint = Complaint::with('inquiry')->findOrFail($complaintId);
        
        // Check if this agency is assigned to this complaint
        if ($complaint->A_ID !== $user->A_ID) {
            return redirect()->route('assignments.index')->with('error', 'You can only update inquiries assigned to your agency.');
        }
        
        DB::transaction(function () use ($complaint, $request, $user) {
            // Update inquiry status
            $complaint->inquiry->update(['I_Status' => $request->status]);
            
            // Update complaint comment
            $complaint->update(['C_Comment' => $request->comment]);
            
            // Add history entry
            $statusText = $request->status;
            $complaint->addHistory(
                'Status updated to ' . $statusText . '. Comment: ' . $request->comment,
                $user->A_ID,
                'Agency'
            );
        });
        
        return redirect()->route('assignments.view', $complaintId)->with('success', 'Inquiry review updated successfully.');
    }

    /**
     * Track assignment history
     */
    public function trackAssignmentHistory($complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $complaint = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])->findOrFail($complaintId);
        
        // Check permissions
        if (!$this->canViewAssignment($complaint, $user, $userType)) {
            return redirect()->route('assignments.index')->with('error', 'You do not have permission to view this assignment history.');
        }
        
        $history = $complaint->getFormattedHistory();
        
        return view('ManageAssignment.TrackAssignmentHistory', compact('complaint', 'history', 'userType'));
    }

    /**
     * Generate assigned report
     */
    public function generateAssignedReport(Request $request)
    {
        // Redirect to the ReportController for report generation
        return app(ReportController::class)->generateAssignedReport($request);
    }

    /**
     * Get assignment statistics
     */
    private function getAssignmentStats($userType = 'mcmc', $user = null)
    {
        $query = Complaint::with('inquiry');
        
        // Filter based on user type
        if ($userType === 'agency' && $user) {
            $query->where('A_ID', $user->A_ID);
        } elseif ($userType === 'public' && $user) {
            $query->whereHas('inquiry', function($q) use ($user) {
                $q->where('PU_ID', $user->PU_ID);
            });
        }
        
        $allAssignments = $query->get();
        
        $stats = [
            'total_assignments' => $allAssignments->count(),
            'pending' => $allAssignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === 'Pending';
            })->count(),
            'in_progress' => $allAssignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === 'In Progress';
            })->count(),
            'resolved' => $allAssignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === 'Resolved';
            })->count(),
            'closed' => $allAssignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === 'Closed';
            })->count(),
            'this_month' => $allAssignments->filter(function($assignment) {
                return Carbon::parse($assignment->C_AssignedDate)->isCurrentMonth();
            })->count(),
        ];
        
        // Add additional stats for public users
        if ($userType === 'public') {
            $totalInquiries = $user ? Inquiry::where('PU_ID', $user->PU_ID)->count() : 0;
            $assigned = $allAssignments->count();
            
            $stats['total_inquiries'] = $totalInquiries;
            $stats['assigned'] = $assigned;
        }
        
        // Add additional stats for agency users
        if ($userType === 'agency') {
            $stats['total_assigned_to_agency'] = $allAssignments->count();
            $stats['awaiting_verification'] = $allAssignments->filter(function($assignment) {
                return $assignment->C_VerificationStatus === 'Pending';
            })->count();
            $stats['verified_by_agency'] = $allAssignments->filter(function($assignment) {
                return $assignment->C_VerificationStatus === 'Accepted';
            })->count();
            $stats['rejected_by_agency'] = $allAssignments->filter(function($assignment) {
                return $assignment->C_VerificationStatus === 'Rejected';
            })->count();
        }
        
        return $stats;
    }

    /**
     * Verify assignment (for agencies)
     */
    public function verifyAssignment($complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'agency') {
            return redirect()->route('assignments.index')->with('error', 'Only agencies can verify assignments.');
        }
        
        $complaint = Complaint::with(['inquiry.publicUser', 'agency'])->findOrFail($complaintId);
        
        // Check if this agency is assigned to this complaint
        if ($complaint->A_ID !== $user->A_ID) {
            return redirect()->route('assignments.index')->with('error', 'You can only verify inquiries assigned to your agency.');
        }
        
        return view('ManageAssignment.VerifyAssignment', compact('complaint'));
    }

    /**
     * Process verification (for agencies)
     */
    public function processVerification(Request $request, $complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'agency') {
            return redirect()->route('assignments.index')->with('error', 'Only agencies can verify assignments.');
        }
        
        $request->validate([
            'verification_action' => 'required|string|in:accept,reject',
            'rejection_reason' => 'required_if:verification_action,reject|nullable|string|max:1000',
            'verification_comment' => 'nullable|string|max:1000'
        ]);
        
        $complaint = Complaint::with('inquiry')->findOrFail($complaintId);
        
        // Check if this agency is assigned to this complaint
        if ($complaint->A_ID !== $user->A_ID) {
            return redirect()->route('assignments.index')->with('error', 'You can only verify inquiries assigned to your agency.');
        }
        
        DB::transaction(function () use ($complaint, $request, $user) {
            if ($request->verification_action === 'accept') {
                // Accept the assignment
                $complaint->update([
                    'C_VerificationStatus' => Complaint::VERIFICATION_ACCEPTED,
                    'C_VerificationDate' => Carbon::now()->toDateString(),
                    'C_VerifiedBy' => $user->A_ID
                ]);
                
                // Update inquiry status to in progress
                $complaint->inquiry->update(['I_Status' => Inquiry::STATUS_IN_PROGRESS]);
                
                // Add history entry with optional comment
                $historyMessage = 'Assignment verified and accepted by agency. Inquiry status updated to In Progress.';
                if ($request->verification_comment) {
                    $historyMessage .= ' Comment: ' . $request->verification_comment;
                }
                
                $complaint->addHistory($historyMessage, $user->A_ID, 'Agency');
                
            } else {
                // Reject the assignment
                $complaint->update([
                    'C_VerificationStatus' => Complaint::VERIFICATION_REJECTED,
                    'C_VerificationDate' => Carbon::now()->toDateString(),
                    'C_VerifiedBy' => $user->A_ID,
                    'C_RejectionReason' => $request->rejection_reason
                ]);
                
                // Add history entry
                $complaint->addHistory(
                    'Assignment rejected by agency. Reason: ' . $request->rejection_reason,
                    $user->A_ID,
                    'Agency'
                );
                
                // Create notification for MCMC
                Notification::createRejectionNotification($complaint, $request->rejection_reason);
            }
        });
        
        $message = $request->verification_action === 'accept' 
            ? 'Assignment accepted successfully. You can now start working on this inquiry.' 
            : 'Assignment rejected successfully. MCMC has been notified and will reassign this inquiry.';
        
        return redirect()->route('assignments.view', $complaintId)->with('success', $message);
    }

    /**
     * View rejected assignments (MCMC only)
     */
    public function rejectedAssignments()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'Only MCMC users can view rejected assignments.');
        }
        
        $rejectedAssignments = Complaint::with(['inquiry.publicUser', 'agency'])
            ->where('C_VerificationStatus', Complaint::VERIFICATION_REJECTED)
            ->orderBy('C_VerificationDate', 'desc')
            ->paginate(10);
        
        return view('ManageAssignment.RejectedAssignments', compact('rejectedAssignments'));
    }

    /**
     * Get user type based on authenticated user
     */
    private function getUserType($user)
    {
        if ($user instanceof PublicUser) {
            return 'public';
        } elseif ($user instanceof Agency) {
            return 'agency';
        } elseif ($user instanceof MCMC) {
            return 'mcmc';
        }
        
        return 'unknown';
    }

    /**
     * View notifications for MCMC
     */
    public function viewNotifications()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'Only MCMC users can view notifications.');
        }
        
        $notifications = Notification::where('P_ID', $user->M_ID)
                                   ->orderBy('N_Timestamp', 'desc')
                                   ->paginate(15);
        
        return view('ManageAssignment.Notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($notificationId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'Unauthorized access.');
        }
        
        $notification = Notification::where('N_ID', $notificationId)
                                  ->where('P_ID', $user->M_ID)
                                  ->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Check if user can view assignment
     */
    private function canViewAssignment($complaint, $user, $userType)
    {
        if ($userType === 'mcmc') {
            return true; // MCMC can view all assignments
        } elseif ($userType === 'agency' && $complaint->A_ID === $user->A_ID) {
            return true; // Agency can view their own assignments
        } elseif ($userType === 'public' && $complaint->inquiry->PU_ID === $user->PU_ID) {
            return true; // Public user can view assignments related to their inquiries
        }
        
        return false;
    }
}