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
        
        return view('ManageAssignment.ManageAssignmentManageAssignments', compact('assignments', 'agencies', 'stats'));
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
    private function getAssignmentStats()
    {
        $allAssignments = Complaint::with('inquiry')->get();
        
        $stats = [
            'total_assignments' => $allAssignments->count(),
            'pending' => $allAssignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === Inquiry::STATUS_PENDING;
            })->count(),
            'in_progress' => $allAssignments->filter(function($assignment) {
                return $assignment->inquiry->I_Status === Inquiry::STATUS_IN_PROGRESS;
            })->count(),
            'this_month' => $allAssignments->filter(function($assignment) {
                return $assignment->C_AssignedDate->isCurrentMonth();
            })->count()
        ];
        
        return $stats;
    }

    /**
     * Determine user type
     */
    private function getUserType($user)
    {
        if (isset($user->M_ID)) {
            return 'mcmc';
        } elseif (isset($user->A_ID)) {
            return 'agency';
        } elseif (isset($user->PU_ID)) {
            return 'publicuser';
        }
        
        return null;
    }

    /**
     * Check if user can view assignment
     */
    private function canViewAssignment($complaint, $user, $userType)
    {
        if ($userType === 'mcmc') {
            return true; // MCMC users can view all assignments
        } elseif ($userType === 'agency' && $complaint->A_ID === $user->A_ID) {
            return true; // Agency can view their own assignments
        } elseif ($userType === 'publicuser' && $complaint->inquiry->PU_ID === $user->PU_ID) {
            return true; // Public user can view their own inquiries
        }
        
        return false;
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
        
        // Check if already verified
        if ($complaint->C_VerificationStatus !== Complaint::VERIFICATION_PENDING) {
            return redirect()->route('assignments.view', $complaintId)->with('error', 'This assignment has already been verified.');
        }
        
        return view('ManageAssignment.VerifyAssignment', compact('complaint'));
    }

    /**
     * Update assignment verification
     */
    public function updateVerification(Request $request, $complaintId)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'agency') {
            return redirect()->route('assignments.index')->with('error', 'Only agencies can verify assignments.');
        }
        
        $request->validate([
            'verification' => 'required|in:accept,reject',
            'reason' => 'required_if:verification,reject|nullable|string|max:1000'
        ]);
        
        $complaint = Complaint::with('inquiry')->findOrFail($complaintId);
        
        // Check if this agency is assigned to this complaint
        if ($complaint->A_ID !== $user->A_ID) {
            return redirect()->route('assignments.index')->with('error', 'You can only verify inquiries assigned to your agency.');
        }
        
        // Check if already verified
        if ($complaint->C_VerificationStatus !== Complaint::VERIFICATION_PENDING) {
            return redirect()->route('assignments.view', $complaintId)->with('error', 'This assignment has already been verified.');
        }
        
        DB::transaction(function () use ($complaint, $request, $user) {
            if ($request->verification === 'accept') {
                // Accept the assignment
                $complaint->update([
                    'C_VerificationStatus' => Complaint::VERIFICATION_ACCEPTED,
                    'C_VerificationDate' => Carbon::now()->toDateString(),
                    'C_VerifiedBy' => $user->A_ID
                ]);
                
                // Update inquiry status to in progress
                $complaint->inquiry->update(['I_Status' => Inquiry::STATUS_IN_PROGRESS]);
                
                // Add history entry
                $complaint->addHistory(
                    'Assignment verified and accepted by agency. Inquiry status updated to In Progress.',
                    $user->A_ID,
                    'Agency'
                );
                
                // TODO: Send notification to public user
            } else {
                // Reject the assignment
                $complaint->update([
                    'C_VerificationStatus' => Complaint::VERIFICATION_REJECTED,
                    'C_RejectionReason' => $request->reason,
                    'C_VerificationDate' => Carbon::now()->toDateString(),
                    'C_VerifiedBy' => $user->A_ID
                ]);
                
                // Add history entry
                $complaint->addHistory(
                    'Assignment rejected by agency. Reason: ' . $request->reason,
                    $user->A_ID,
                    'Agency'
                );
                
                // TODO: Send notification to MCMC
            }
        });
        
        $action = $request->verification === 'accept' ? 'accepted' : 'rejected';
        return redirect()->route('assignments.view', $complaintId)->with('success', 'Assignment has been ' . $action . ' successfully.');
    }

    /**
     * Show rejected assignments
     */
    public function rejectedAssignments()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'Only MCMC users can view rejected assignments.');
        }
        
        $rejectedAssignments = Complaint::with(['inquiry.publicUser', 'agency', 'mcmc'])
                                      ->rejected()
                                      ->orderBy('C_VerificationDate', 'desc')
                                      ->paginate(10);
        
        return view('ManageAssignment.RejectedAssignments', compact('rejectedAssignments'));
    }
}