<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use App\Models\Complaint;
use Carbon\Carbon;

class AgencyController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('agency')->user();
        return view('Dashboard.AgencyDashboard', compact('user'));
    }

    /**
     * Display agency's current inquiries
     */
    public function inquiries(Request $request)
    {
        $agency = Auth::guard('agency')->user();
        
        $query = Complaint::where('A_ID', $agency->A_ID)
            ->whereIn('C_Status', ['assigned', 'under_investigation', 'pending_review'])
            ->with(['inquiry', 'agency']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('C_Status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('C_AssignedDate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('C_AssignedDate', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('inquiry', function($q) use ($searchTerm) {
                $q->where('I_Subject', 'like', "%{$searchTerm}%")
                  ->orWhere('I_Description', 'like', "%{$searchTerm}%");
            });
        }

        $inquiries = $query->orderBy('C_AssignedDate', 'desc')->paginate(15);

        return view('agency.inquiries.index', compact('inquiries'));
    }

    /**
     * Display agency's inquiry history with filtering
     */
    public function inquiryHistory(Request $request)
    {
        $agency = Auth::guard('agency')->user();
        
        $query = Complaint::where('A_ID', $agency->A_ID)
            ->with(['inquiry', 'agency']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('C_Status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('C_AssignedDate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('C_AssignedDate', '<=', $request->date_to);
        }

        if ($request->filled('month')) {
            $query->whereMonth('C_AssignedDate', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('C_AssignedDate', $request->year);
        }

        if ($request->filled('category')) {
            $query->whereHas('inquiry', function($q) use ($request) {
                $q->where('I_Category', $request->category);
            });
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('inquiry', function($q) use ($searchTerm) {
                $q->where('I_Subject', 'like', "%{$searchTerm}%")
                  ->orWhere('I_Description', 'like', "%{$searchTerm}%");
            });
        }

        $inquiries = $query->orderBy('C_AssignedDate', 'desc')->paginate(15);

        // Get filter options
        $statusOptions = [
            'assigned' => 'Assigned',
            'under_investigation' => 'Under Investigation',
            'verified_true' => 'Verified as True',
            'identified_fake' => 'Identified as Fake',
            'rejected' => 'Rejected',
            'completed' => 'Completed'
        ];

        $categoryOptions = Inquiry::distinct()->pluck('I_Category')->filter()->sort();
        $years = range(date('Y'), date('Y') - 5);
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('agency.inquiries.history', compact('inquiries', 'statusOptions', 'categoryOptions', 'years', 'months'));
    }

    /**
     * Display specific inquiry details with full history
     */
    public function showInquiry($id)
    {
        $agency = Auth::guard('agency')->user();
        
        $complaint = Complaint::where('C_ID', $id)
            ->where('A_ID', $agency->A_ID)
            ->with(['inquiry', 'agency', 'updates'])
            ->firstOrFail();

        // Get status history (if you have a status history table)
        $statusHistory = $complaint->statusHistory ?? collect();

        return view('agency.inquiries.show', compact('complaint', 'statusHistory'));
    }

    /**
     * Update inquiry status
     */
    public function updateInquiryStatus(Request $request, $id)
    {
        $agency = Auth::guard('agency')->user();
        
        $complaint = Complaint::where('C_ID', $id)
            ->where('A_ID', $agency->A_ID)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:under_investigation,verified_true,identified_fake,completed',
            'remarks' => 'nullable|string|max:1000'
        ]);

        $complaint->update([
            'C_Status' => $request->status,
            'C_UpdatedDate' => now(),
            'C_Remarks' => $request->remarks
        ]);

        // Log the status change (if you have activity logging)
        // ActivityLog::create([...]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    /**
     * Add update/communication to inquiry
     */
    public function addInquiryUpdate(Request $request, $id)
    {
        $agency = Auth::guard('agency')->user();
        
        $complaint = Complaint::where('C_ID', $id)
            ->where('A_ID', $agency->A_ID)
            ->firstOrFail();

        $request->validate([
            'update_text' => 'required|string|max:2000',
            'is_internal' => 'boolean'
        ]);

        // Add to updates table (you might need to create this)
        $complaint->updates()->create([
            'update_text' => $request->update_text,
            'is_internal' => $request->boolean('is_internal', false),
            'created_by' => $agency->A_ID,
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Update added successfully.');
    }
}