<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Agency;
use App\Models\Complaint;
use App\Models\MCMC;
use App\Models\PublicUser;
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
        
        // Only MCMC users can access assignment management
        if ($userType !== 'mcmc') {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to manage assignments.');
        }
        
        // Get assignments with filters
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
        
        // Get agencies for filter dropdown
        $agencies = Agency::orderBy('A_Name')->get();
        
        // Get statistics
        $stats = $this->getAssignmentStats();
        
        return view('ManageAssignment.index', compact('assignments', 'agencies', 'stats'));
    }

    /**
     * Show form to assign inquiry to agency
     */
    public function assignInquiry($inquiryId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to assign inquiries.');
        }
        
        $inquiry = Inquiry::with('publicUser')->findOrFail($inquiryId);
        
        // Check if inquiry is already assigned
        if ($inquiry->isAssigned()) {
            return redirect()->route('assignments.index')->with('error', 'This inquiry is already assigned.');
        }
        
        // Get agencies that match the inquiry category
        $agencies = Agency::where('A_Category', $inquiry->I_Category)->orderBy('A_Name')->get();
        
        if ($agencies->isEmpty()) {
            return redirect()->route('assignments.index')->with('error', 'No agencies found for this inquiry category.');
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
        
        // Verify agency category matches inquiry category
        if ($agency->A_Category !== $inquiry->I_Category) {
            return redirect()->back()->with('error', 'Selected agency does not handle this inquiry category.');
        }
        
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
        
        // Get agencies that match the inquiry category (excluding current agency)
        $agencies = Agency::where('A_Category', $complaint->inquiry->I_Category)
                          ->where('A_ID', '!=', $complaint->A_ID)
                          ->orderBy('A_Name')
                          ->get();
        
        if ($agencies->isEmpty()) {
            return redirect()->route('assignments.index')->with('error', 'No other agencies available for reassignment.');
        }
        
        return view('ManageAssignment.ReassigneInquiry', compact('complaint', 'agencies'));
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
        
        // Verify new agency category matches inquiry category
        if ($newAgency->A_Category !== $complaint->inquiry->I_Category) {
            return redirect()->back()->with('error', 'Selected agency does not handle this inquiry category.');
        }
        
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
            'status' => 'required|string|in:' . implode(',', [
                Inquiry::STATUS_IN_PROGRESS,
                Inquiry::STATUS_RESOLVED,
                Inquiry::STATUS_CLOSED
            ]),
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
     * Generate assignment report
     */
    public function generateAssignedReport(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'Only MCMC users can generate assignment reports.');
        }
        
        $query = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc']);
        
        // Apply date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        } else {
            // Default to current month
            $query->byDateRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        }
        
        if ($request->filled('agency')) {
            $query->where('A_ID', $request->agency);
        }
        
        $assignments = $query->orderBy('C_AssignedDate', 'desc')->get();
        
        // Generate statistics
        $stats = [
            'total_assignments' => $assignments->count(),
            'pending' => $assignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === Inquiry::STATUS_PENDING;
            })->count(),
            'in_progress' => $assignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === Inquiry::STATUS_IN_PROGRESS;
            })->count(),
            'resolved' => $assignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === Inquiry::STATUS_RESOLVED;
            })->count(),
            'closed' => $assignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === Inquiry::STATUS_CLOSED;
            })->count(),
        ];
        
        $agencyStats = $assignments->groupBy('A_ID')->map(function($group) {
            return [
                'agency' => $group->first()->agency,
                'count' => $group->count()
            ];
        });
        
        $agencies = Agency::orderBy('A_Name')->get();
        
        return view('ManageAssignment.GenerateAssignedReport', compact('assignments', 'stats', 'agencyStats', 'agencies'));
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
     * Get assignment statistics
     */
    private function getAssignmentStats()
    {
        $totalAssignments = Complaint::count();
        
        return [
            'total_assignments' => $totalAssignments,
            'pending' => Complaint::whereHas('inquiry', function($q) {
                $q->where('I_Status', Inquiry::STATUS_PENDING);
            })->count(),
            'in_progress' => Complaint::whereHas('inquiry', function($q) {
                $q->where('I_Status', Inquiry::STATUS_IN_PROGRESS);
            })->count(),
            'resolved' => Complaint::whereHas('inquiry', function($q) {
                $q->where('I_Status', Inquiry::STATUS_RESOLVED);
            })->count(),
            'closed' => Complaint::whereHas('inquiry', function($q) {
                $q->where('I_Status', Inquiry::STATUS_CLOSED);
            })->count(),
            'agencies_count' => Agency::count(),
            'this_month' => Complaint::whereMonth('C_AssignedDate', Carbon::now()->month)
                                   ->whereYear('C_AssignedDate', Carbon::now()->year)
                                   ->count()
        ];
    }

    /**
     * Check if user can view assignment
     */
    private function canViewAssignment($complaint, $user, $userType)
    {
        if ($userType === 'mcmc') {
            return true; // MCMC can view all assignments
        } elseif ($userType === 'agency') {
            return $complaint->A_ID === $user->A_ID; // Agency can view their own assignments
        } elseif ($userType === 'public') {
            return $complaint->inquiry->PU_ID === $user->PU_ID; // Public user can view their own inquiry assignments
        }
        
        return false;
    }

    /**
     * Show verification form for agency to accept/reject assignment
     */
    public function verifyAssignment($complaintId)
    {
        $complaint = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])
                              ->findOrFail($complaintId);
        
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Only allow agency users to verify their own assignments
        if ($userType !== 'agency' || $complaint->A_ID !== $user->A_ID) {
            abort(403, 'Unauthorized to verify this assignment.');
        }
        
        // Only allow verification if status is pending
        if (!$complaint->isPendingVerification()) {
            return redirect()->route('assignments.view', $complaint->C_ID)
                           ->with('error', 'This assignment has already been verified.');
        }
        
        return view('ManageAssignment.VerifyAssignment', compact('complaint', 'userType'));
    }

    /**
     * Process verification (accept/reject) from agency
     */
    public function processVerification(Request $request, $complaintId)
    {
        $complaint = Complaint::with(['inquiry', 'agency'])
                              ->findOrFail($complaintId);
        
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Only allow agency users to verify their own assignments
        if ($userType !== 'agency' || $complaint->A_ID !== $user->A_ID) {
            abort(403, 'Unauthorized to verify this assignment.');
        }
        
        // Only allow verification if status is pending
        if (!$complaint->isPendingVerification()) {
            return redirect()->route('assignments.view', $complaint->C_ID)
                           ->with('error', 'This assignment has already been verified.');
        }
        
        $request->validate([
            'verification_action' => 'required|in:accept,reject',
            'rejection_reason' => 'required_if:verification_action,reject|max:1000',
            'verification_comment' => 'nullable|max:1000'
        ]);
        
        $verificationAction = $request->verification_action;
        
        if ($verificationAction === 'accept') {
            // Accept the assignment
            $complaint->update([
                'C_VerificationStatus' => Complaint::VERIFICATION_ACCEPTED,
                'C_VerificationDate' => now(),
                'C_VerifiedBy' => $user->A_ID,
                'C_Comment' => $request->verification_comment ?: 'Assignment accepted by agency.'
            ]);
            
            // Update inquiry status to In Progress
            $complaint->inquiry->update([
                'I_Status' => Inquiry::STATUS_IN_PROGRESS
            ]);
            
            // Add history entry
            $complaint->addHistory(
                'Assignment accepted and verification completed. Inquiry status updated to In Progress.',
                $user->A_Name,
                'Agency'
            );
            
            return redirect()->route('assignments.view', $complaint->C_ID)
                           ->with('success', 'Assignment accepted successfully. You can now proceed with the inquiry.');
                           
        } else {
            // Reject the assignment
            $complaint->update([
                'C_VerificationStatus' => Complaint::VERIFICATION_REJECTED,
                'C_RejectionReason' => $request->rejection_reason,
                'C_VerificationDate' => now(),
                'C_VerifiedBy' => $user->A_ID,
                'C_Comment' => 'Assignment rejected: ' . $request->rejection_reason
            ]);
            
            // Add history entry
            $complaint->addHistory(
                'Assignment rejected by agency. Reason: ' . $request->rejection_reason,
                $user->A_Name,
                'Agency'
            );
            
            return redirect()->route('assignments.index')
                           ->with('success', 'Assignment rejected. MCMC has been notified and will reassign the inquiry.');
        }
    }

    /**
     * Show rejected assignments for MCMC to reassign
     */
    public function rejectedAssignments(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Only MCMC can view rejected assignments
        if ($userType !== 'mcmc') {
            abort(403, 'Unauthorized access.');
        }
        
        $query = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])
                          ->rejected()
                          ->orderBy('C_VerificationDate', 'desc');
        
        // Apply filters
        if ($request->filled('agency')) {
            $query->where('A_ID', $request->agency);
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }
        
        $rejectedAssignments = $query->paginate(10);
        $agencies = Agency::orderBy('A_Name')->get();
        
        return view('ManageAssignment.RejectedAssignments', compact('rejectedAssignments', 'agencies', 'userType'));
    }


}