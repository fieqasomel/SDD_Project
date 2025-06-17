<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InquiryController extends Controller
{
    /**
     * Display the main inquiry management dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Build query based on user permissions
        $query = Inquiry::with(['publicUser', 'complaint']);
        
        // Apply user-specific filters
        if ($userType === 'public') {
            $query->where('PU_ID', $user->PU_ID);
        } elseif ($userType === 'agency') {
            // Agency can see inquiries related to their category
            $query->where('I_Category', $user->A_Category);
        }
        // MCMC users can see all inquiries
        
        // Apply search filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }
        
        // Get inquiries with pagination
        $inquiries = $query->orderBy('I_Date', 'desc')->paginate(10);
        
        // Get statistics for dashboard
        $stats = $this->getInquiryStats($userType, $user);
        
        return view('ManageInquiry.index', compact('inquiries', 'stats', 'userType'));
    }

    /**
     * Show the form for creating a new inquiry
     */
    public function create()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Only public users can create inquiries
        if ($userType !== 'public') {
            return redirect()->route('inquiries.index')->with('error', 'Only public users can create inquiries.');
        }
        
        return view('ManageInquiry.create', [
            'categories' => Inquiry::CATEGORIES
        ]);
    }

    /**
     * Store a newly created inquiry
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        if ($userType !== 'public') {
            return redirect()->route('inquiries.index')->with('error', 'Only public users can create inquiries.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:' . implode(',', Inquiry::CATEGORIES),
            'source' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);
        
        $inquiryId = Inquiry::generateInquiryId();
        
        $inquiryData = [
            'I_ID' => $inquiryId,
            'PU_ID' => $user->PU_ID,
            'I_Title' => $request->title,
            'I_Description' => $request->description,
            'I_Category' => $request->category,
            'I_Date' => Carbon::now()->toDateString(),
            'I_Status' => Inquiry::STATUS_PENDING,
            'I_Source' => $request->source
        ];
        
        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = $inquiryId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inquiries', $filename, 'public');
            
            $inquiryData['I_filename'] = $filename;
            $inquiryData['InfoPath'] = $path;
        }
        
        Inquiry::create($inquiryData);
        
        return redirect()->route('inquiries.index')->with('success', 'Inquiry submitted successfully!');
    }

    /**
     * Display the specified inquiry
     */
    public function show($id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $inquiry = Inquiry::with(['publicUser', 'progress'])->findOrFail($id);
        
        // Check permissions
        if (!$this->canViewInquiry($inquiry, $user, $userType)) {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to view this inquiry.');
        }
        
        return view('ManageInquiry.show', compact('inquiry', 'userType'));
    }

    /**
     * Show the form for editing the specified inquiry
     */
    public function edit($id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $inquiry = Inquiry::findOrFail($id);
        
        // Check permissions
        if (!$this->canEditInquiry($inquiry, $user, $userType)) {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to edit this inquiry.');
        }
        
        return view('ManageInquiry.edit', [
            'inquiry' => $inquiry,
            'categories' => Inquiry::CATEGORIES,
            'statuses' => $this->getAvailableStatuses($userType)
        ]);
    }

    /**
     * Update the specified inquiry
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $inquiry = Inquiry::findOrFail($id);
        
        if (!$this->canEditInquiry($inquiry, $user, $userType)) {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to edit this inquiry.');
        }
        
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:' . implode(',', Inquiry::CATEGORIES),
            'source' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ];
        
        // Add status validation for admin users
        if ($userType !== 'public') {
            $availableStatuses = $this->getAvailableStatuses($userType);
            $rules['status'] = 'required|string|in:' . implode(',', $availableStatuses);
        }
        
        $request->validate($rules);
        
        $updateData = [
            'I_Title' => $request->title,
            'I_Description' => $request->description,
            'I_Category' => $request->category,
            'I_Source' => $request->source
        ];
        
        // Update status if user has permission
        if ($userType !== 'public' && $request->filled('status')) {
            $updateData['I_Status'] = $request->status;
        }
        
        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($inquiry->InfoPath) {
                Storage::disk('public')->delete($inquiry->InfoPath);
            }
            
            $file = $request->file('attachment');
            $filename = $inquiry->I_ID . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('inquiries', $filename, 'public');
            
            $updateData['I_filename'] = $filename;
            $updateData['InfoPath'] = $path;
        }
        
        $inquiry->update($updateData);
        
        return redirect()->route('inquiries.show', $id)->with('success', 'Inquiry updated successfully!');
    }

    /**
     * Show the delete confirmation form
     */
    public function delete($id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $inquiry = Inquiry::with('publicUser')->findOrFail($id);
        
        if (!$this->canDeleteInquiry($inquiry, $user, $userType)) {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to delete this inquiry.');
        }
        
        return view('ManageInquiry.DeleteInquiry', compact('inquiry', 'userType'));
    }

    /**
     * Remove the specified inquiry
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $inquiry = Inquiry::findOrFail($id);
        
        if (!$this->canDeleteInquiry($inquiry, $user, $userType)) {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to delete this inquiry.');
        }

        // Validate deletion reason and confirmation
        $request->validate([
            'deletion_reason' => 'required|string|max:255',
            'additional_comments' => 'required_if:deletion_reason,Other|nullable|string|max:1000',
            'optional_comments' => 'nullable|string|max:1000',
            'confirm_deletion' => 'required|accepted'
        ], [
            'deletion_reason.required' => 'Please select a reason for deletion.',
            'additional_comments.required_if' => 'Please provide additional details when selecting "Other" as the reason.',
            'confirm_deletion.required' => 'You must confirm the deletion.',
            'confirm_deletion.accepted' => 'You must confirm the deletion by checking the checkbox.'
        ]);

        // Prepare deletion log data
        $deletionReason = $request->deletion_reason;
        if ($request->deletion_reason === 'Other' && $request->filled('additional_comments')) {
            $deletionReason = 'Other: ' . $request->additional_comments;
        } elseif ($request->filled('optional_comments')) {
            $deletionReason = $request->deletion_reason . ' - ' . $request->optional_comments;
        }

        // Log the deletion for audit trail
        $deletionLog = [
            'inquiry_id' => $inquiry->I_ID,
            'inquiry_title' => $inquiry->I_Title,
            'inquiry_category' => $inquiry->I_Category,
            'inquiry_status' => $inquiry->I_Status,
            'submitted_by' => $inquiry->publicUser ? $inquiry->publicUser->PU_Name : 'N/A',
            'submitted_by_email' => $inquiry->publicUser ? $inquiry->publicUser->PU_Email : 'N/A',
            'deleted_by' => $this->getDeletedByName($user, $userType),
            'deleted_by_type' => $userType,
            'deletion_reason' => $deletionReason,
            'deleted_at' => now(),
            'inquiry_date' => $inquiry->I_Date
        ];

        // Store deletion log in a separate table or log file
        $this->logInquiryDeletion($deletionLog);
        
        // Delete associated file if exists
        if ($inquiry->InfoPath) {
            Storage::disk('public')->delete($inquiry->InfoPath);
        }
        
        $inquiry->delete();
        
        return redirect()->route('inquiries.index')->with('success', 'Inquiry deleted successfully! Deletion reason has been logged for audit purposes.');
    }

    /**
     * Show inquiry history for the authenticated user
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Build query based on user permissions
        $query = Inquiry::with(['publicUser', 'progress', 'complaint.agency']);
        
        // Apply user-specific filters
        if ($userType === 'public') {
            $query->where('PU_ID', $user->PU_ID);
        } elseif ($userType === 'agency') {
            // Agency can see inquiries related to their category
            $query->where('I_Category', $user->A_Category);
        }
        // MCMC users can see all inquiries

        // Apply search filters from request
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }
        
        // Order by date (newest first) and paginate
        $inquiries = $query->orderBy('I_Date', 'desc')->paginate(10);
        
        // Get statistics for the current user
        $stats = $this->getInquiryHistoryStats($userType, $user);
        
        return view('ManageInquiry.InquiryHistoryView', compact('inquiries', 'stats', 'userType'));
    }

    /**
     * Search inquiries
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $query = Inquiry::with('publicUser');
        
        // Apply user-specific filters
        if ($userType === 'public') {
            $query->where('PU_ID', $user->PU_ID);
        } elseif ($userType === 'agency') {
            $query->where('I_Category', $user->A_Category);
        }
        
        // Apply search filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }
        
        $inquiries = $query->orderBy('I_Date', 'desc')->paginate(10);
        
        return view('ManageInquiry.search', compact('inquiries', 'userType'));
    }

    /**
     * Generate inquiry report
     */
    public function generateReport(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Only MCMC and Agency users can generate reports
        if ($userType === 'public') {
            return redirect()->route('inquiries.index')->with('error', 'You do not have permission to generate reports.');
        }
        
        $query = Inquiry::with('publicUser');
        
        // Apply user-specific filters
        if ($userType === 'agency') {
            $query->where('I_Category', $user->A_Category);
        }
        
        // Apply date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        } else {
            // Default to current month
            $query->byDateRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        }
        
        $inquiries = $query->orderBy('I_Date', 'desc')->get();
        
        // Generate statistics
        $stats = [
            'total' => $inquiries->count(),
            'pending' => $inquiries->where('I_Status', Inquiry::STATUS_PENDING)->count(),
            'in_progress' => $inquiries->where('I_Status', Inquiry::STATUS_IN_PROGRESS)->count(),
            'resolved' => $inquiries->where('I_Status', Inquiry::STATUS_RESOLVED)->count(),
            'closed' => $inquiries->where('I_Status', Inquiry::STATUS_CLOSED)->count(),
            'rejected' => $inquiries->where('I_Status', Inquiry::STATUS_REJECTED)->count(),
        ];
        
        $categoryStats = $inquiries->groupBy('I_Category')->map->count();
        
        return view('ManageInquiry.report', compact('inquiries', 'stats', 'categoryStats', 'userType'));
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
     * Get inquiry history statistics
     */
    private function getInquiryHistoryStats($userType, $user)
    {
        $baseQuery = Inquiry::query();
        
        if ($userType === 'public') {
            $baseQuery->where('PU_ID', $user->PU_ID);
        } elseif ($userType === 'agency') {
            $baseQuery->where('I_Category', $user->A_Category);
        }
        
        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->byStatus(Inquiry::STATUS_PENDING)->count(),
            'under_review' => (clone $baseQuery)->byStatus(Inquiry::STATUS_UNDER_REVIEW)->count(),
            'validated' => (clone $baseQuery)->byStatus(Inquiry::STATUS_VALIDATED)->count(),
            'assigned' => (clone $baseQuery)->byStatus(Inquiry::STATUS_ASSIGNED)->count(),
            'in_progress' => (clone $baseQuery)->byStatus(Inquiry::STATUS_IN_PROGRESS)->count(),
            'resolved' => (clone $baseQuery)->byStatus(Inquiry::STATUS_RESOLVED)->count(),
            'closed' => (clone $baseQuery)->byStatus(Inquiry::STATUS_CLOSED)->count(),
            'rejected' => (clone $baseQuery)->byStatus(Inquiry::STATUS_REJECTED)->count(),
            'non_serious' => (clone $baseQuery)->byStatus(Inquiry::STATUS_NON_SERIOUS)->count(),
        ];
    }

    /**
     * Get inquiry statistics for dashboard
     */
    private function getInquiryStats($userType, $user)
    {
        $query = Inquiry::query();
        
        if ($userType === 'public') {
            $query->where('PU_ID', $user->PU_ID);
        } elseif ($userType === 'agency') {
            $query->where('I_Category', $user->A_Category);
        }
        
        return [
            'total' => $query->count(),
            'pending' => $query->byStatus(Inquiry::STATUS_PENDING)->count(),
            'in_progress' => $query->byStatus(Inquiry::STATUS_IN_PROGRESS)->count(),
            'resolved' => $query->byStatus(Inquiry::STATUS_RESOLVED)->count(),
            'closed' => $query->byStatus(Inquiry::STATUS_CLOSED)->count(),
        ];
    }

    /**
     * Check if user can view inquiry
     */
    private function canViewInquiry($inquiry, $user, $userType)
    {
        if ($userType === 'public') {
            return $inquiry->PU_ID === $user->PU_ID;
        } elseif ($userType === 'agency') {
            return $inquiry->I_Category === $user->A_Category;
        } elseif ($userType === 'mcmc') {
            return true; // MCMC can view all inquiries
        }
        
        return false;
    }

    /**
     * Check if user can edit inquiry
     */
    private function canEditInquiry($inquiry, $user, $userType)
    {
        if (!$inquiry->canBeEdited()) {
            return false;
        }
        
        if ($userType === 'public') {
            return $inquiry->PU_ID === $user->PU_ID;
        } elseif ($userType === 'agency') {
            return $inquiry->I_Category === $user->A_Category;
        } elseif ($userType === 'mcmc') {
            return true;
        }
        
        return false;
    }

    /**
     * Check if user can delete inquiry
     */
    private function canDeleteInquiry($inquiry, $user, $userType)
    {
        if (!$inquiry->canBeDeleted()) {
            return false;
        }
        
        if ($userType === 'public') {
            return $inquiry->PU_ID === $user->PU_ID;
        } elseif ($userType === 'mcmc') {
            return true;
        }
        
        return false; // Agency users cannot delete inquiries
    }

    /**
     * Get available statuses based on user type
     */
    private function getAvailableStatuses($userType)
    {
        if ($userType === 'agency') {
            return [
                Inquiry::STATUS_PENDING,
                Inquiry::STATUS_IN_PROGRESS,
                Inquiry::STATUS_RESOLVED
            ];
        } elseif ($userType === 'mcmc') {
            return [
                Inquiry::STATUS_PENDING,
                Inquiry::STATUS_IN_PROGRESS,
                Inquiry::STATUS_RESOLVED,
                Inquiry::STATUS_CLOSED,
                Inquiry::STATUS_REJECTED
            ];
        }
        
        return [];
    }

    /**
     * Get the name of the user who deleted the inquiry
     */
    private function getDeletedByName($user, $userType)
    {
        switch ($userType) {
            case 'public':
                return $user->PU_Name ?? 'Public User';
            case 'agency':
                return $user->A_Name ?? 'Agency User';
            case 'mcmc':
                return $user->M_Name ?? 'MCMC User';
            default:
                return 'Unknown User';
        }
    }

    /**
     * Log inquiry deletion for audit purposes
     */
    private function logInquiryDeletion($deletionLog)
    {
        // Log to Laravel's default log file
        \Log::info('Inquiry Deletion', $deletionLog);

        // Optionally, you can also store in a database table
        // This would require creating a deletion_logs table first
        /*
        DB::table('inquiry_deletion_logs')->insert([
            'inquiry_id' => $deletionLog['inquiry_id'],
            'inquiry_title' => $deletionLog['inquiry_title'],
            'inquiry_category' => $deletionLog['inquiry_category'],
            'inquiry_status' => $deletionLog['inquiry_status'],
            'submitted_by' => $deletionLog['submitted_by'],
            'submitted_by_email' => $deletionLog['submitted_by_email'],
            'deleted_by' => $deletionLog['deleted_by'],
            'deleted_by_type' => $deletionLog['deleted_by_type'],
            'deletion_reason' => $deletionLog['deletion_reason'],
            'deleted_at' => $deletionLog['deleted_at'],
            'inquiry_date' => $deletionLog['inquiry_date'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        */
    }
}