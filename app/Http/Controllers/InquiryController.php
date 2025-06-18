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