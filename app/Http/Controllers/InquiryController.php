<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Initialize query
            $query = Inquiry::query();
            
            // If user is authenticated, filter by user
            if (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                $query->where('PU_ID', Auth::user()->PU_ID);
            } else {
                // If not authenticated or not a public user, return empty
                return view('ManageInquiry.ManageInquiries', [
                    'inquiries' => collect([]),
                    'stats' => [
                        'total' => 0,
                        'pending' => 0,
                        'in_progress' => 0,
                        'resolved' => 0,
                        'closed' => 0
                    ]
                ]);
            }

            // Apply search filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Description', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Category', 'like', "%{$searchTerm}%");
                });
            }
            
            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }
            
            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }

            // Get inquiries
            $inquiries = $query->orderBy('I_Date', 'desc')->get();
            
            // Calculate statistics (for all user's inquiries, not just filtered)
            $allInquiries = Inquiry::where('PU_ID', Auth::user()->PU_ID)->get();
            $stats = [
                'total' => $allInquiries->count(),
                'pending' => $allInquiries->where('I_Status', 'Pending')->count(),
                'in_progress' => $allInquiries->where('I_Status', 'In Progress')->count(),
                'resolved' => $allInquiries->where('I_Status', 'Resolved')->count(),
                'closed' => $allInquiries->where('I_Status', 'Closed')->count()
            ];
            
            return view('ManageInquiry.ManageInquiries', [
                'inquiries' => $inquiries,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            // Fallback in case of any errors
            \Log::error('Error in index: ' . $e->getMessage());
            return view('ManageInquiry.ManageInquiries', [
                'inquiries' => collect([]),
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'in_progress' => 0,
                    'resolved' => 0,
                    'closed' => 0
                ]
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Categories for the dropdown
        $categories = [
            'General Information',
            'Technical Support',
            'Billing',
            'Complaint',
            'Service Request',
            'Feedback',
            'Other'
        ];
        
        return view('ManageInquiry.SubmitInquiry', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store method called', ['request_data' => $request->all()]);
        
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string|max:1000',
            'source' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // 2MB max
        ]);

        \Log::info('Validation passed', ['validated_data' => $validatedData]);

        try {
            // Handle file upload if present
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('inquiries', $fileName, 'public');
            }

            // Generate unique inquiry ID
            do {
                $inquiryId = 'INQ' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (Inquiry::where('I_ID', $inquiryId)->exists());

            // Save to database
            $inquiry = new Inquiry();
            $inquiry->I_ID = $inquiryId;
            $inquiry->I_Title = $validatedData['title'];
            $inquiry->I_Category = $validatedData['category'];
            $inquiry->I_Description = $validatedData['description'];
            $inquiry->I_Source = $validatedData['source'] ?? null;
            $inquiry->I_filename = $attachmentPath;
            $inquiry->I_Date = now()->toDateString();
            $inquiry->I_Status = 'Pending';
            
            // Get the correct PU_ID from authenticated user
            if (Auth::user() instanceof \App\Models\PublicUser) {
                $inquiry->PU_ID = Auth::user()->PU_ID;
            } else {
                $inquiry->PU_ID = null; // Or handle other user types
            }
            
            $inquiry->save();
            
            // Log the successful save for debugging
            \Log::info('Inquiry saved successfully', [
                'inquiry_id' => $inquiryId,
                'user_id' => Auth::user()->PU_ID ?? 'N/A',
                'title' => $validatedData['title']
            ]);

            return redirect()->route('inquiries.index')->with('success', 'Inquiry submitted successfully! Your inquiry ID is ' . $inquiryId . '. You will receive updates on its status.');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Inquiry submission error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'There was an error submitting your inquiry. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Find the inquiry by ID
            $inquiry = Inquiry::where('I_ID', $id)->first();
            
            // Check if inquiry exists
            if (!$inquiry) {
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }
            
            // Check if user owns this inquiry (for security)
            if (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                if ($inquiry->PU_ID !== Auth::user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to view this inquiry.');
                }
            }
            
            return view('ManageInquiry.ViewInquiryDetails', compact('inquiry'));
            
        } catch (\Exception $e) {
            \Log::error('Error viewing inquiry: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Error viewing inquiry.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            \Log::info('Edit inquiry requested for ID: ' . $id);
            
            // Find the inquiry by ID
            $inquiry = Inquiry::where('I_ID', $id)->first();
            \Log::info('Inquiry found: ' . ($inquiry ? 'Yes' : 'No'));
            
            // Check if inquiry exists
            if (!$inquiry) {
                \Log::error('Inquiry not found with ID: ' . $id);
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }
            
            \Log::info('Inquiry details: ID=' . $inquiry->I_ID . ', Status=' . $inquiry->I_Status . ', PU_ID=' . $inquiry->PU_ID);
            
            // Check if user owns this inquiry (for security)
            if (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                \Log::info('User authenticated: ' . Auth::user()->PU_ID);
                if ($inquiry->PU_ID !== Auth::user()->PU_ID) {
                    \Log::error('User not authorized. Inquiry PU_ID: ' . $inquiry->PU_ID . ', User PU_ID: ' . Auth::user()->PU_ID);
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to edit this inquiry.');
                }
            } else {
                \Log::error('User not authenticated or not a PublicUser');
                return redirect()->route('inquiries.index')->with('error', 'Authentication required.');
            }
            
            // Check if inquiry can be edited (only pending inquiries can be edited)
            if ($inquiry->I_Status !== 'Pending') {
                \Log::error('Inquiry cannot be edited. Status: ' . $inquiry->I_Status);
                return redirect()->route('inquiries.index')->with('error', 'Only pending inquiries can be edited.');
            }
            
            // Categories for the dropdown
            $categories = [
                'General Information',
                'Technical Support',
                'Billing',
                'Complaint',
                'Service Request',
                'Feedback',
                'Other'
            ];
            
            return view('ManageInquiry.EditInquiry', compact('inquiry', 'categories'));
            
        } catch (\Exception $e) {
            \Log::error('Error editing inquiry: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Error editing inquiry.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Find the inquiry by ID
            $inquiry = Inquiry::where('I_ID', $id)->first();
            
            // Check if inquiry exists
            if (!$inquiry) {
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }
            
            // Check if user owns this inquiry (for security)
            if (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                if ($inquiry->PU_ID !== Auth::user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to update this inquiry.');
                }
            }
            
            // Check if inquiry can be updated (only pending inquiries can be updated)
            if ($inquiry->I_Status !== 'Pending') {
                return redirect()->route('inquiries.index')->with('error', 'Only pending inquiries can be updated.');
            }
            
            // Validate the request
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|string',
                'description' => 'required|string|max:1000',
                'source' => 'nullable|string|max:255',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            ]);
            
            // Handle file upload if present
            $attachmentPath = $inquiry->I_filename; // Keep existing attachment by default
            if ($request->hasFile('attachment')) {
                // Delete old file if exists
                if ($inquiry->I_filename && \Storage::disk('public')->exists($inquiry->I_filename)) {
                    \Storage::disk('public')->delete($inquiry->I_filename);
                }
                
                // Upload new file
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('inquiries', $fileName, 'public');
            }
            
            // Update the inquiry
            $inquiry->I_Title = $validatedData['title'];
            $inquiry->I_Category = $validatedData['category'];
            $inquiry->I_Description = $validatedData['description'];
            $inquiry->I_Source = $validatedData['source'] ?? null;
            $inquiry->I_filename = $attachmentPath;
            $inquiry->save();
            
            \Log::info('Inquiry updated successfully', [
                'inquiry_id' => $id,
                'user_id' => Auth::user()->PU_ID ?? 'N/A'
            ]);
            
            return redirect()->route('inquiries.index')->with('success', 'Inquiry updated successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error updating inquiry: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'There was an error updating your inquiry. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the inquiry by ID
            $inquiry = Inquiry::where('I_ID', $id)->first();
            
            // Check if inquiry exists
            if (!$inquiry) {
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }
            
            // Check if user owns this inquiry (for security)
            if (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                if ($inquiry->PU_ID !== Auth::user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to delete this inquiry.');
                }
            }
            
            // Check if inquiry can be deleted (only pending inquiries can be deleted)
            if ($inquiry->I_Status !== 'Pending') {
                return redirect()->route('inquiries.index')->with('error', 'Only pending inquiries can be deleted.');
            }
            
            // Delete attachment file if exists
            if ($inquiry->I_filename && \Storage::disk('public')->exists($inquiry->I_filename)) {
                \Storage::disk('public')->delete($inquiry->I_filename);
            }
            
            // Delete the inquiry
            $inquiryTitle = $inquiry->I_Title;
            $inquiry->delete();
            
            \Log::info('Inquiry deleted successfully', [
                'inquiry_id' => $id,
                'inquiry_title' => $inquiryTitle,
                'user_id' => Auth::user()->PU_ID ?? 'N/A'
            ]);
            
            return redirect()->route('inquiries.index')->with('success', 'Inquiry "' . $inquiryTitle . '" deleted successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error deleting inquiry: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Error deleting inquiry.');
        }
    }

    /**
     * Search inquiries
     */
    public function search(Request $request)
    {
        try {
            $userType = $this->getUserType();
            $query = Inquiry::with('publicUser');
            
            // Apply search filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Description', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Category', 'like', "%{$searchTerm}%");
                });
            }
            
            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }
            
            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }

            // Apply date filters
            if ($request->filled('date_from')) {
                $query->where('I_Date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->where('I_Date', '<=', $request->date_to);
            }
            
            // Apply user-specific filters
            if ($userType === 'public') {
                $query->where('PU_ID', Auth::user()->PU_ID);
            }
            
            $inquiries = $query->orderBy('I_Date', 'desc')->paginate(15);
            
            // Categories and statuses for filter dropdowns
            $categories = ['General Information', 'Technical Support', 'Billing', 'Complaint', 'Service Request', 'Feedback', 'Other'];
            $statuses = ['Pending', 'In Progress', 'Resolved', 'Closed', 'Rejected'];
            
            return view('ManageInquiry.SearchInquiry', compact('inquiries', 'categories', 'statuses', 'userType'));
            
        } catch (\Exception $e) {
            \Log::error('Error in search: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Search failed. Please try again.');
        }
    }

    /**
     * Generate inquiry report
     */
    public function generateReport(Request $request)
    {
        try {
            $userType = $this->getUserType();
            $query = Inquiry::with(['publicUser', 'complaints.agency']);
            
            // Apply user-specific filters
            if ($userType === 'agency') {
                // Agencies only see inquiries assigned to them
                $agencyId = Auth::user()->A_ID;
                $query->whereHas('complaints', function($q) use ($agencyId) {
                    $q->where('A_ID', $agencyId);
                });
            } elseif ($userType === 'public') {
                // Public users only see their own inquiries
                $query->where('PU_ID', Auth::user()->PU_ID);
            }
            // MCMC users see all inquiries (no additional filter)
            
            // Apply date filters
            if ($request->filled('date_from')) {
                $query->where('I_Date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('I_Date', '<=', $request->date_to);
            }
            
            // Apply other filters  
            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }
            
            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }
            
            $inquiries = $query->orderBy('I_Date', 'desc')->get();
            
            // Categories and statuses for filter dropdowns
            $categories = ['General Information', 'Technical Support', 'Billing', 'Complaint', 'Service Request', 'Feedback', 'Other'];
            $statuses = ['Pending', 'In Progress', 'Resolved', 'Closed', 'Rejected'];
            
            return view('ManageInquiry.GenerateInquiryReport', compact('inquiries', 'categories', 'statuses', 'userType'));
            
        } catch (\Exception $e) {
            \Log::error('Error in generateReport: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Report generation failed. Please try again.');
        }
    }

    /**
     * Show public inquiries history (for transparency)
     */
    public function inquiryHistory(Request $request)
    {
        try {
            $userType = $this->getUserType();
            
            // Get all inquiries with public information only (privacy protected)
            $inquiries = Inquiry::with(['publicUser:PU_ID,PU_Name'])
                ->orderBy('I_Date', 'desc')
                ->paginate(20);
            
            // Categories for filtering
            $categories = [
                'General Information',
                'Technical Support', 
                'Billing',
                'Complaint',
                'Service Request',
                'Feedback',
                'Other'
            ];
            
            // Status options for filtering
            $statuses = ['Pending', 'In Progress', 'Resolved', 'Closed', 'Rejected'];
            
            return view('ManageInquiry.InquiryHistoryView', compact('inquiries', 'categories', 'statuses', 'userType'));
            
        } catch (\Exception $e) {
            \Log::error('Error in inquiryHistory: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load inquiry history. Please try again.');
        }
    }

    /**
     * Display public inquiries (accessible by all users but with privacy protection)
     */
    public function publicInquiries(Request $request)
    {
        try {
            $userType = $this->getUserType();
            
            // Build query for all inquiries (regardless of user) but select only public-safe fields
            $query = Inquiry::select([
                'I_ID', 'I_Title', 'I_Category', 'I_Description', 
                'I_Status', 'I_Date', 'PU_ID'
            ]);
            
            // Apply search filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Description', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Category', 'like', "%{$searchTerm}%");
                });
            }
            
            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }
            
            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }

            // Apply date filters
            if ($request->filled('date_from')) {
                $query->where('I_Date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->where('I_Date', '<=', $request->date_to);
            }
            
            // Get inquiries with pagination
            $inquiries = $query->orderBy('I_Date', 'desc')->paginate(15);
            
            // Categories and statuses for filter dropdowns
            $categories = ['General Information', 'Technical Support', 'Billing', 'Complaint', 'Service Request', 'Feedback', 'Other'];
            $statuses = ['Pending', 'In Progress', 'Resolved', 'Closed', 'Rejected'];
            
            return view('ManageInquiry.PublicInquiries', compact('inquiries', 'categories', 'statuses', 'userType'));
            
        } catch (\Exception $e) {
            \Log::error('Error in publicInquiries: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load public inquiries. Please try again.');
        }
    }

    /**
<<<<<<< HEAD
     * MCMC Staff - View New Inquiries
     * Display newly submitted inquiries for MCMC staff review
     */
    public function viewNewInquiries(Request $request)
    {
        try {
            // Check if user is MCMC staff
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('home')->with('error', 'Access denied. MCMC staff only.');
            }

            // Get new inquiries (Pending status) with user information
            $query = Inquiry::with(['publicUser:PU_ID,PU_Name,PU_Email,PU_Phone'])
                           ->where('I_Status', 'Pending')
                           ->orderBy('I_Date', 'desc');

            // Apply search filters if provided
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Description', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Category', 'like', "%{$searchTerm}%");
                });
            }

            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }

            if ($request->filled('date_from')) {
                $query->where('I_Date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('I_Date', '<=', $request->date_to);
            }

            $newInquiries = $query->paginate(15);

            // Get statistics
            $stats = [
                'total_new' => Inquiry::where('I_Status', 'Pending')->count(),
                'today_new' => Inquiry::where('I_Status', 'Pending')
                                    ->whereDate('I_Date', today())
                                    ->count(),
                'this_week_new' => Inquiry::where('I_Status', 'Pending')
                                        ->whereBetween('I_Date', [now()->startOfWeek(), now()->endOfWeek()])
                                        ->count(),
                'this_month_new' => Inquiry::where('I_Status', 'Pending')
                                         ->whereMonth('I_Date', now()->month)
                                         ->whereYear('I_Date', now()->year)
                                         ->count(),
            ];

            $categories = ['General Information', 'Technical Support', 'Billing', 'Complaint', 'Service Request', 'Feedback', 'Other'];

            return view('ManageInquiry.MCMCNewInquiries', compact('newInquiries', 'stats', 'categories'));

        } catch (\Exception $e) {
            \Log::error('Error in viewNewInquiries: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load new inquiries. Please try again.');
        }
    }

    /**
     * MCMC Staff - Filter and Validate Inquiry
     * Review inquiry and mark as genuine or non-serious
     */
    public function filterInquiry(Request $request, $id)
    {
        try {
            // Check if user is MCMC staff
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('home')->with('error', 'Access denied. MCMC staff only.');
            }

            $inquiry = Inquiry::with('publicUser')->where('I_ID', $id)->first();

            if (!$inquiry) {
                return redirect()->back()->with('error', 'Inquiry not found.');
            }

            // Only pending inquiries can be filtered
            if ($inquiry->I_Status !== 'Pending') {
                return redirect()->back()->with('error', 'Only pending inquiries can be filtered.');
            }

            return view('ManageInquiry.MCMCFilterInquiry', compact('inquiry'));

        } catch (\Exception $e) {
            \Log::error('Error in filterInquiry: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading inquiry for filtering.');
        }
    }

    /**
     * MCMC Staff - Process Inquiry Filtering
     * Update inquiry status based on MCMC staff review
     */
    public function processInquiryFilter(Request $request, $id)
    {
        try {
            // Check if user is MCMC staff
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('home')->with('error', 'Access denied. MCMC staff only.');
            }

            $validatedData = $request->validate([
                'action' => 'required|in:approve,reject',
                'mcmc_notes' => 'nullable|string|max:1000',
                'rejection_reason' => 'required_if:action,reject|string|max:500'
            ]);

            $inquiry = Inquiry::where('I_ID', $id)->first();

            if (!$inquiry) {
                return redirect()->back()->with('error', 'Inquiry not found.');
            }

            if ($inquiry->I_Status !== 'Pending') {
                return redirect()->back()->with('error', 'Only pending inquiries can be processed.');
            }

            // Update inquiry based on action
            if ($validatedData['action'] === 'approve') {
                $inquiry->I_Status = 'Approved'; // Ready for assignment
                $message = 'Inquiry has been approved and is ready for assignment.';
            } else {
                $inquiry->I_Status = 'Rejected';
                $inquiry->rejection_reason = $validatedData['rejection_reason'];
                $message = 'Inquiry has been rejected as non-serious.';
            }

            // Add MCMC notes and processing info
            $inquiry->mcmc_notes = $validatedData['mcmc_notes'];
            $inquiry->mcmc_processed_by = Auth::guard('mcmc')->user()->M_ID;
            $inquiry->mcmc_processed_at = now();
            $inquiry->save();

            // Log the action for audit trail
            \Log::info('MCMC Inquiry Processing', [
                'inquiry_id' => $id,
                'action' => $validatedData['action'],
                'mcmc_staff' => Auth::guard('mcmc')->user()->M_ID,
                'timestamp' => now()
            ]);

            return redirect()->route('inquiries.mcmc.new')->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error in processInquiryFilter: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing inquiry filter.');
        }
    }

    /**
     * MCMC Staff - View Previous Inquiries
     * Display all previously filtered inquiries with search and filter options
     */
    public function viewPreviousInquiries(Request $request)
    {
        try {
            // Check if user is MCMC staff
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('home')->with('error', 'Access denied. MCMC staff only.');
            }

            // Get all inquiries that have been processed (not Pending)
            $query = Inquiry::with(['publicUser:PU_ID,PU_Name,PU_Email'])
                           ->whereNotIn('I_Status', ['Pending'])
                           ->orderBy('mcmc_processed_at', 'desc');

            // Apply search filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Description', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Category', 'like', "%{$searchTerm}%");
                });
            }

            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }

            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('I_Date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('I_Date', '<=', $request->date_to);
            }

            // Filter by agency if specified
            if ($request->filled('agency')) {
                $query->whereHas('complaints', function($q) use ($request) {
                    $q->where('A_ID', $request->agency);
                });
            }

            $previousInquiries = $query->paginate(15);

            // Get available agencies for filter
            $agencies = \App\Models\Agency::select('A_ID', 'A_Name')->get();

            $categories = ['General Information', 'Technical Support', 'Billing', 'Complaint', 'Service Request', 'Feedback', 'Other'];
            $statuses = ['Approved', 'Rejected', 'In Progress', 'Resolved', 'Closed'];

            return view('ManageInquiry.MCMCPreviousInquiries', compact('previousInquiries', 'categories', 'statuses', 'agencies'));

        } catch (\Exception $e) {
            \Log::error('Error in viewPreviousInquiries: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load previous inquiries. Please try again.');
        }
    }

    /**
     * MCMC Staff - Generate Comprehensive Inquiry Reports
     * Generate detailed reports with multiple formats and filtering options
     */
    public function generateMCMCReport(Request $request)
    {
        try {
            // Check if user is MCMC staff
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('home')->with('error', 'Access denied. MCMC staff only.');
            }

            $query = Inquiry::with(['publicUser:PU_ID,PU_Name,PU_Email', 'complaints.agency:A_ID,A_Name']);

            // Apply date filters
            if ($request->filled('date_from')) {
                $query->where('I_Date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('I_Date', '<=', $request->date_to);
            }

            // Apply other filters
            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }

            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }

            if ($request->filled('agency') && $request->agency !== '') {
                $query->whereHas('complaints', function($q) use ($request) {
                    $q->where('A_ID', $request->agency);
                });
            }

            $inquiries = $query->orderBy('I_Date', 'desc')->get();

            // Generate comprehensive statistics
            $stats = [
                'total_inquiries' => $inquiries->count(),
                'by_status' => $inquiries->groupBy('I_Status')->map->count(),
                'by_category' => $inquiries->groupBy('I_Category')->map->count(),
                'monthly_stats' => $inquiries->groupBy(function($inquiry) {
                    return \Carbon\Carbon::parse($inquiry->I_Date)->format('Y-m');
                })->map->count(),
                'yearly_stats' => $inquiries->groupBy(function($inquiry) {
                    return \Carbon\Carbon::parse($inquiry->I_Date)->format('Y');
                })->map->count(),
                'by_agency' => $inquiries->filter(function($inquiry) {
                    return $inquiry->complaints->isNotEmpty();
                })->groupBy(function($inquiry) {
                    return $inquiry->complaints->first()->agency->A_Name ?? 'Unassigned';
                })->map->count(),
            ];

            // Handle export requests
            if ($request->filled('export')) {
                return $this->exportMCMCReport($inquiries, $stats, $request->export);
            }

            $categories = ['General Information', 'Technical Support', 'Billing', 'Complaint', 'Service Request', 'Feedback', 'Other'];
            $statuses = ['Pending', 'Approved', 'Rejected', 'In Progress', 'Resolved', 'Closed'];
            $agencies = \App\Models\Agency::select('A_ID', 'A_Name')->get();

            return view('ManageInquiry.MCMCInquiryReport', compact('inquiries', 'stats', 'categories', 'statuses', 'agencies'));

        } catch (\Exception $e) {
            \Log::error('Error in generateMCMCReport: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Report generation failed. Please try again.');
        }
    }

    /**
     * Export MCMC Report in different formats
     */
    private function exportMCMCReport($inquiries, $stats, $format)
    {
        $filename = 'mcmc_inquiry_report_' . now()->format('Y-m-d_H-i-s');

        if ($format === 'pdf') {
            // Generate PDF report
            $pdf = \PDF::loadView('ManageInquiry.reports.mcmc_pdf', compact('inquiries', 'stats'));
            return $pdf->download($filename . '.pdf');
        } elseif ($format === 'excel') {
            // Generate Excel report
            return \Excel::download(new \App\Exports\MCMCInquiryReportExport($inquiries, $stats), $filename . '.xlsx');
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

    /**
     * MCMC Staff - Audit Log
     * View audit trail of all MCMC actions on inquiries
     */
    public function auditLog(Request $request)
    {
        try {
            // Check if user is MCMC staff
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('home')->with('error', 'Access denied. MCMC staff only.');
            }

            // Get inquiries with MCMC processing information
            $query = Inquiry::with(['publicUser:PU_ID,PU_Name'])
                           ->whereNotNull('mcmc_processed_by')
                           ->orderBy('mcmc_processed_at', 'desc');

            // Apply filters
            if ($request->filled('date_from')) {
                $query->whereDate('mcmc_processed_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('mcmc_processed_at', '<=', $request->date_to);
            }

            if ($request->filled('mcmc_staff')) {
                $query->where('mcmc_processed_by', $request->mcmc_staff);
            }

            if ($request->filled('action')) {
                if ($request->action === 'approved') {
                    $query->where('I_Status', 'Approved');
                } elseif ($request->action === 'rejected') {
                    $query->where('I_Status', 'Rejected');
                }
            }

            $auditLogs = $query->paginate(20);

            // Get MCMC staff list for filter
            $mcmcStaff = \App\Models\MCMC::select('M_ID', 'M_Name')->get();

            return view('ManageInquiry.MCMCAuditLog', compact('auditLogs', 'mcmcStaff'));

        } catch (\Exception $e) {
            \Log::error('Error in auditLog: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load audit log. Please try again.');
        }
    }

    /**
=======
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
     * Get the type of the authenticated user
     */
    private function getUserType()
    {
        if (Auth::guard('publicuser')->check()) {
            return 'public';
        } elseif (Auth::guard('agency')->check()) {
            return 'agency';
        } elseif (Auth::guard('mcmc')->check()) {
            return 'mcmc';
        }
        
        return 'unknown';
    }
}