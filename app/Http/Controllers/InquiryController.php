<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Inquiry;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;
use App\Models\Complaint;

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
            $isPublicUser = false;
            $isMCMC = false;
            
<<<<<<< HEAD
            // Check user type and apply appropriate filters
=======
            // Check user type and apply appropriate filters (hybrid approach)
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
            if (Auth::guard('publicuser')->check()) {
                // Public User via publicuser guard - show only their inquiries
                $query->where('PU_ID', Auth::guard('publicuser')->user()->PU_ID);
                $isPublicUser = true;
            } elseif (Auth::guard('mcmc')->check()) {
                // MCMC Staff via mcmc guard - show all inquiries for management
                // No additional filter needed, they can see all inquiries
                $isMCMC = true;
            } elseif (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                // Fallback for default guard PublicUser
                $query->where('PU_ID', Auth::user()->PU_ID);
                $isPublicUser = true;
            } elseif (Auth::check() && Auth::user() instanceof \App\Models\Agency) {
                // Agencies see inquiries assigned to them through complaints
                $query->whereHas('complaints', function($q) {
                    $q->where('A_ID', Auth::user()->A_ID);
                });
            } else {
                // If not authenticated or invalid user type, return empty
                return view('ManageInquiry.ManageInquiries', [
                    'inquiries' => collect([]),
                    'stats' => [
                        'total' => 0,
                        'pending' => 0,
                        'in_progress' => 0,
                        'resolved' => 0,
                        'closed' => 0
                    ],
                    'isMCMC' => false
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
                    
                    // For MCMC, also search by Public User details
                    if (Auth::guard('mcmc')->check()) {
                        $q->orWhereHas('publicUser', function($userQuery) use ($searchTerm) {
                            $userQuery->where('PU_Name', 'like', "%{$searchTerm}%")
                                     ->orWhere('PU_Email', 'like', "%{$searchTerm}%");
                        });
                    }
                });
            }
            
            if ($request->filled('category') && $request->category !== '') {
                $query->where('I_Category', $request->category);
            }
            
            if ($request->filled('status') && $request->status !== '') {
                $query->where('I_Status', $request->status);
            }

            // Date range filtering for agencies
<<<<<<< HEAD
            if (Auth::user() instanceof \App\Models\Agency && $request->filled('date_from') && $request->filled('date_to')) {
=======
            if ((Auth::check() && Auth::user() instanceof \App\Models\Agency) && $request->filled('date_from') && $request->filled('date_to')) {
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
                $query->whereHas('complaints', function($q) use ($request) {
                    $q->whereBetween('C_AssignedDate', [$request->date_from, $request->date_to]);
                });
            }

            // Get inquiries with relationships (especially for MCMC to see user details)
            if ($isMCMC) {
                $inquiries = $query->with(['publicUser', 'complaints'])->orderBy('I_Date', 'desc')->get();
            } else {
                $inquiries = $query->orderBy('I_Date', 'desc')->get();
            }
            
            // Calculate statistics based on user type (hybrid approach)
            if ($isPublicUser) {
                // For Public Users - only their inquiries
                $userId = Auth::guard('publicuser')->check() ? 
                         Auth::guard('publicuser')->user()->PU_ID : 
                         Auth::user()->PU_ID;
                $allInquiries = Inquiry::where('PU_ID', $userId)->get();
<<<<<<< HEAD
            } elseif (Auth::user() instanceof \App\Models\Agency) {
=======
            } elseif ($isMCMC) {
                // For MCMC - all inquiries in the system
                $allInquiries = Inquiry::all();
            } elseif (Auth::check() && Auth::user() instanceof \App\Models\Agency) {
                // For Agencies - inquiries assigned to them through complaints
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
                $allInquiries = Inquiry::whereHas('complaints', function($q) {
                    $q->where('A_ID', Auth::user()->A_ID);
                })->get();
            } else {
                // For MCMC - all inquiries in the system
                $allInquiries = Inquiry::all();
            }
            
            $stats = [
                'total' => $allInquiries->count(),
                'pending' => $allInquiries->where('I_Status', 'Pending')->count(),
                'in_progress' => $allInquiries->where('I_Status', 'In Progress')->count(),
                'resolved' => $allInquiries->where('I_Status', 'Resolved')->count(),
                'closed' => $allInquiries->where('I_Status', 'Closed')->count(),
                'verified_true' => $allInquiries->where('I_Status', 'Verified as True')->count(),
                'identified_fake' => $allInquiries->where('I_Status', 'Identified as Fake')->count(),
                'rejected' => $allInquiries->where('I_Status', 'Rejected')->count()
            ];
            
            return view('ManageInquiry.ManageInquiries', [
                'inquiries' => $inquiries,
                'stats' => $stats,
                'isMCMC' => $isMCMC
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
                ],
                'isMCMC' => false
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
            if (Auth::guard('publicuser')->check()) {
                $inquiry->PU_ID = Auth::guard('publicuser')->user()->PU_ID;
            } elseif (Auth::user() instanceof \App\Models\PublicUser) {
                $inquiry->PU_ID = Auth::user()->PU_ID;
            } else {
                $inquiry->PU_ID = null; // Or handle other user types
            }
            
            $inquiry->save();
            
            // Log the successful save for debugging
            \Log::info('Inquiry saved successfully', [
                'inquiry_id' => $inquiryId,
                'user_id' => $inquiry->PU_ID ?? 'N/A',
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
            // Find the inquiry by ID with related user data
            $inquiry = Inquiry::with('publicUser')->where('I_ID', $id)->first();
            
            // Check if inquiry exists
            if (!$inquiry) {
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }
            
            // Check authorization based on user type
            if (Auth::guard('mcmc')->check()) {
                // MCMC staff can view all inquiries
                $isMCMC = true;
            } elseif (Auth::guard('publicuser')->check()) {
                // Public users can only view their own inquiries
                if ($inquiry->PU_ID !== Auth::guard('publicuser')->user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to view this inquiry.');
                }
                $isMCMC = false;
            } elseif (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                // Fallback for default guard
                if ($inquiry->PU_ID !== Auth::user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to view this inquiry.');
                }
                $isMCMC = false;
            } else {
                // Not authenticated or invalid user type
                return redirect()->route('login')->with('error', 'Please log in to view inquiries.');
            }
            
            return view('ManageInquiry.ViewInquiryDetails', compact('inquiry', 'isMCMC'));
            
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
            if (Auth::guard('publicuser')->check()) {
                \Log::info('User authenticated: ' . Auth::guard('publicuser')->user()->PU_ID);
                if ($inquiry->PU_ID !== Auth::guard('publicuser')->user()->PU_ID) {
                    \Log::error('User not authorized. Inquiry PU_ID: ' . $inquiry->PU_ID . ', User PU_ID: ' . Auth::guard('publicuser')->user()->PU_ID);
                    return redirect()->route('inquiries.index')->with('error', 'You are not authorized to edit this inquiry.');
                }
            } elseif (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
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
            
            // Handle MCMC validation/filtering
            if (Auth::guard('mcmc')->check()) {
                return $this->handleMCMCValidation($request, $inquiry);
            }
            
            // Handle Public User updates
            if (Auth::guard('publicuser')->check() || (Auth::check() && Auth::user() instanceof \App\Models\PublicUser)) {
                return $this->handlePublicUserUpdate($request, $inquiry, $id);
            }
            
            return redirect()->route('login')->with('error', 'Authentication required.');
            
        } catch (\Exception $e) {
            \Log::error('Error updating inquiry: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Error updating inquiry.');
        }
    }

    /**
     * Handle MCMC validation/filtering of inquiries
     */
    private function handleMCMCValidation(Request $request, Inquiry $inquiry)
    {
        // Validate MCMC input
        $validatedData = $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'mcmc_notes' => 'required|string|max:1000',
        ]);

        $mcmcUser = Auth::guard('mcmc')->user();
        
        // Update inquiry with MCMC decision
        $inquiry->update([
            'I_Status' => $validatedData['status'],
            'mcmc_processed_by' => $mcmcUser->M_ID, // Fixed: was 'processed_by'
            'mcmc_processed_at' => now(), // Fixed: was 'processed_date'
            'mcmc_notes' => $validatedData['mcmc_notes'],
        ]);

        $message = $validatedData['status'] === 'Approved' ? 
                  'Inquiry has been approved and is ready for assignment.' : 
                  'Inquiry has been rejected.';
                  
        return redirect()->route('mcmc.inquiries.new')->with('success', $message);
    }

    /**
     * Handle Public User updates to their inquiries
     */
    private function handlePublicUserUpdate(Request $request, Inquiry $inquiry, string $id)
    {
        // Check if user owns this inquiry
        $userId = Auth::guard('publicuser')->check() ? 
                 Auth::guard('publicuser')->user()->PU_ID : 
                 Auth::user()->PU_ID;
                 
        if ($inquiry->PU_ID !== $userId) {
            return redirect()->route('inquiries.index')->with('error', 'You are not authorized to edit this inquiry.');
        }
        
        // Check if inquiry can be edited
        if ($inquiry->I_Status !== 'Pending') {
            return redirect()->route('inquiries.index')->with('error', 'Only pending inquiries can be edited.');
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
        $attachmentPath = $inquiry->I_filename; // Keep existing file by default
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($inquiry->I_filename) {
                Storage::disk('public')->delete($inquiry->I_filename);
            }
            
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('inquiries', $fileName, 'public');
        }

        // Update the inquiry
        $inquiry->update([
            'I_Title' => $validatedData['title'],
            'I_Category' => $validatedData['category'],
            'I_Description' => $validatedData['description'],
            'I_Source' => $validatedData['source'] ?? null,
            'I_filename' => $attachmentPath,
        ]);

        return redirect()->route('inquiries.index')->with('success', 'Inquiry updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the inquiry by ID
            $inquiry = Inquiry::where('I_ID', $id)->first();
            
            if (!$inquiry) {
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }
            
            // Only MCMC can delete inquiries
            if (!Auth::guard('mcmc')->check()) {
                return redirect()->route('inquiries.index')->with('error', 'You are not authorized to delete inquiries.');
            }
            
            $mcmcUser = Auth::guard('mcmc')->user();
            
            // Instead of actually deleting, mark as deleted by MCMC
            $inquiry->update([
                'I_Status' => 'Deleted by MCMC',
                'mcmc_processed_by' => $mcmcUser->M_ID, // Fixed: was 'processed_by'
                'mcmc_processed_at' => now(), // Fixed: was 'processed_date'
                'mcmc_notes' => 'Inquiry deleted by MCMC staff - Non-serious/Invalid submission'
            ]);
            
            return redirect()->route('mcmc.inquiries.new')->with('success', 'Inquiry has been marked as deleted.');
            
        } catch (\Exception $e) {
            \Log::error('Error deleting inquiry: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Error deleting inquiry.');
        }
    }

    /**
     * MCMC method to view new/pending inquiries
     */
    public function mcmcNewInquiries(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            // Get new inquiries (pending and not yet processed by MCMC)
            $query = Inquiry::with(['publicUser'])
                ->where('I_Status', 'Pending')
                ->whereNull('mcmc_processed_by'); // Fixed: was 'processed_by'

            // Apply filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Category', 'like', "%{$searchTerm}%")
                      ->orWhereHas('publicUser', function($userQuery) use ($searchTerm) {
                          $userQuery->where('PU_Name', 'like', "%{$searchTerm}%");
                      });
                });
            }

            if ($request->filled('category')) {
                $query->where('I_Category', $request->category);
            }

            $inquiries = $query->orderBy('I_Date', 'desc')->get();
        
            $stats = [
                'total_new' => Inquiry::where('I_Status', 'Pending')->whereNull('mcmc_processed_by')->count(), // Fixed: was 'processed_by'
                'today_new' => Inquiry::where('I_Status', 'Pending')->whereNull('mcmc_processed_by')->whereDate('I_Date', today())->count(), // Fixed: was 'processed_by'
                'this_week' => Inquiry::where('I_Status', 'Pending')->whereNull('mcmc_processed_by')->whereBetween('I_Date', [now()->startOfWeek(), now()->endOfWeek()])->count(), // Fixed: was 'processed_by'
            ];

            return view('ManageInquiry.MCMCNewInquiries', compact('inquiries', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Error in mcmcNewInquiries: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error loading new inquiries.');
        }
    }

    /**
     * MCMC method to view processed inquiries
     */
    public function mcmcProcessedInquiries(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        $query = Inquiry::with(['publicUser', 'mcmcProcessor'])
            ->whereNotNull('mcmc_processed_by') // Fixed: was 'processed_by'
            ->whereNotIn('I_Status', ['Pending']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('I_ID', 'like', "%{$searchTerm}%")
                  ->orWhere('I_Title', 'like', "%{$searchTerm}%")
                  ->orWhereHas('publicUser', function($userQuery) use ($searchTerm) {
                      $userQuery->where('PU_Name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('I_Status', $request->status);
        }

        $inquiries = $query->orderBy('mcmc_processed_at', 'desc')->get();

        return view('ManageInquiry.MCMCProcessedInquiries', compact('inquiries'));
    }

    /**
     * Search inquiries (public method)
     */
    public function search(Request $request)
    {
        $query = Inquiry::query();
        
        // Apply user-specific filters
        if (Auth::guard('publicuser')->check()) {
            $query->where('PU_ID', Auth::guard('publicuser')->user()->PU_ID);
        } elseif (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
            $query->where('PU_ID', Auth::user()->PU_ID);
        } elseif (Auth::check() && Auth::user() instanceof \App\Models\Agency) {
            $query->whereHas('complaints', function($q) {
                $q->where('A_ID', Auth::user()->A_ID);
            });
        } elseif (!Auth::guard('mcmc')->check()) {
            // If not MCMC and not authenticated properly, return empty
            $query->whereRaw('1 = 0'); // This will return no results
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

        if ($request->filled('category')) {
            $query->where('I_Category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('I_Status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('I_Date', [$request->date_from, $request->date_to]);
        }

        $inquiries = $query->orderBy('I_Date', 'desc')->get();

        return view('ManageInquiry.SearchInquiries', compact('inquiries'));
    }

    /**
     * Generate inquiry reports
     */
    public function generateReport(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            // Base query for inquiries
            $query = Inquiry::with(['publicUser']);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('I_Status', $request->status);
            }

            if ($request->filled('category')) {
                $query->where('I_Category', $request->category);
            }

            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('I_Date', [$request->date_from, $request->date_to]);
            }

            $inquiries = $query->orderBy('I_Date', 'desc')->get();

            // Generate statistics
            $stats = [
                'total' => $inquiries->count(),
                'pending' => $inquiries->where('I_Status', 'Pending')->count(),
                'approved' => $inquiries->where('I_Status', 'Approved')->count(),
                'rejected' => $inquiries->where('I_Status', 'Rejected')->count(),
                'in_progress' => $inquiries->where('I_Status', 'In Progress')->count(),
                'resolved' => $inquiries->where('I_Status', 'Resolved')->count(),
            ];

            return view('ManageInquiry.InquiryReports', compact('inquiries', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Error generating report: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error generating report.');
        }
    }

    /**
     * View inquiry history/activity
     */
    public function history(Request $request)
    {
        if (!Auth::guard('mcmc')->check()) {
            return redirect()->route('login')->with('error', 'MCMC access required.');
        }

        try {
            // Get inquiries with MCMC processing information
            $query = Inquiry::with(['publicUser:PU_ID,PU_Name'])
                           ->whereNotNull('mcmc_processed_by')
                           ->orderBy('mcmc_processed_at', 'desc');

            // Apply filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('I_ID', 'like', "%{$searchTerm}%")
                      ->orWhere('I_Title', 'like', "%{$searchTerm}%");
                });
            }

            if ($request->filled('mcmc_staff')) {
                $query->where('mcmc_processed_by', $request->mcmc_staff);
            }

            if ($request->filled('status')) {
                $query->where('I_Status', $request->status);
            }

            $inquiries = $query->paginate(20);

            // Get MCMC staff for filter dropdown
            $mcmcStaff = MCMC::select('M_ID', 'M_Name')->get();

            return view('ManageInquiry.InquiryHistory', compact('inquiries', 'mcmcStaff'));

        } catch (\Exception $e) {
            \Log::error('Error in inquiry history: ' . $e->getMessage());
            return redirect()->route('mcmc.dashboard')->with('error', 'Error loading inquiry history.');
        }
    }

    /**
     * Public inquiries view (for general public without login)
     */
    public function publicInquiries()
    {
        // This could show general statistics or public information about inquiries
        // without revealing sensitive details
        
        $stats = [
            'total_inquiries' => Inquiry::count(),
            'resolved_inquiries' => Inquiry::where('I_Status', 'Resolved')->count(),
            'categories' => Inquiry::select('I_Category', DB::raw('count(*) as count'))
                                  ->groupBy('I_Category')
                                  ->get()
        ];

        return view('ManageInquiry.PublicInquiries', compact('stats'));
    }

    /**
     * Delete inquiry (soft delete for MCMC)
     */
    public function delete(string $id)
    {
        return $this->destroy($id);
    }

    /**
     * Get inquiry history for a specific inquiry
     */
    public function inquiryHistory(string $id)
    {
        try {
            $inquiry = Inquiry::with(['publicUser', 'complaints.agency', 'mcmcProcessor'])
                             ->where('I_ID', $id)
                             ->first();

            if (!$inquiry) {
                return redirect()->route('inquiries.index')->with('error', 'Inquiry not found.');
            }

            // Check permissions
            if (Auth::guard('publicuser')->check()) {
                if ($inquiry->PU_ID !== Auth::guard('publicuser')->user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'Unauthorized access.');
                }
            } elseif (Auth::check() && Auth::user() instanceof \App\Models\PublicUser) {
                if ($inquiry->PU_ID !== Auth::user()->PU_ID) {
                    return redirect()->route('inquiries.index')->with('error', 'Unauthorized access.');
                }
            } elseif (!Auth::guard('mcmc')->check() && !(Auth::check() && Auth::user() instanceof \App\Models\Agency)) {
                return redirect()->route('login')->with('error', 'Authentication required.');
            }

            return view('ManageInquiry.InquiryHistory', compact('inquiry'));

        } catch (\Exception $e) {
            \Log::error('Error in inquiry history: ' . $e->getMessage());
            return redirect()->route('inquiries.index')->with('error', 'Error loading inquiry history.');
        }
    }
}