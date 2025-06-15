<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Inquiry;
use App\Models\PublicUser;
use App\Models\Agency;
use App\Models\MCMC;
use App\Models\Complaint;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class MCMCInquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:mcmc');
    }

    /**
     * Display all new inquiries for MCMC review
     */
    public function newInquiries(Request $request)
    {
        $query = Inquiry::with(['publicUser'])
            ->where('I_Status', Inquiry::STATUS_PENDING)
            ->whereNull('processed_by');

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('I_Title', 'like', '%' . $request->search . '%')
                  ->orWhere('I_Description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('publicUser', function($userQuery) use ($request) {
                      $userQuery->where('PU_Name', 'like', '%' . $request->search . '%')
                               ->orWhere('PU_Email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('I_Category', $request->category);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('I_Date', [$request->date_from, $request->date_to]);
        }

        $inquiries = $query->orderBy('I_Date', 'desc')->paginate(15);
        
        $stats = [
            'total_new' => Inquiry::where('I_Status', Inquiry::STATUS_PENDING)->whereNull('processed_by')->count(),
            'today_new' => Inquiry::where('I_Status', Inquiry::STATUS_PENDING)->whereNull('processed_by')->whereDate('I_Date', today())->count(),
            'this_week' => Inquiry::where('I_Status', Inquiry::STATUS_PENDING)->whereNull('processed_by')->whereBetween('I_Date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('MCMC.inquiries.new', compact('inquiries', 'stats'));
    }

    /**
     * Show inquiry details for validation
     */
    public function showInquiry($id)
    {
        $inquiry = Inquiry::with(['publicUser', 'processor'])->findOrFail($id);
        
        return view('MCMC.inquiries.show', compact('inquiry'));
    }

    /**
     * Validate and process inquiry
     */
    public function validateInquiry(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:' . Inquiry::STATUS_VALIDATED . ',' . Inquiry::STATUS_REJECTED . ',' . Inquiry::STATUS_NON_SERIOUS,
            'mcmc_notes' => 'required|string|max:1000',
            'priority_level' => 'required|in:Low,Medium,High,Critical',
            'is_serious' => 'required|boolean'
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $mcmcUser = Auth::guard('mcmc')->user();

        $inquiry->update([
            'I_Status' => $request->status,
            'processed_by' => $mcmcUser->M_ID,
            'processed_date' => now(),
            'mcmc_notes' => $request->mcmc_notes,
            'priority_level' => $request->priority_level,
            'is_serious' => $request->is_serious
        ]);

        // Log activity
        if ($mcmcUser && $mcmcUser->M_ID) {
            $this->logActivity($mcmcUser->M_ID, 'validate_inquiry', "Validated inquiry {$inquiry->I_ID} with status: {$request->status}");
        }

        $message = $request->status === Inquiry::STATUS_VALIDATED ? 'Inquiry validated successfully!' : 'Inquiry processed successfully!';
        
        return redirect()->route('mcmc.inquiries.new')->with('success', $message);
    }

    /**
     * Display all previously processed inquiries
     */
    public function processedInquiries(Request $request)
    {
        $query = Inquiry::with(['publicUser', 'processor'])
            ->whereNotNull('processed_by')
            ->whereNotIn('I_Status', [Inquiry::STATUS_PENDING]);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('I_Title', 'like', '%' . $request->search . '%')
                  ->orWhere('I_Description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('publicUser', function($userQuery) use ($request) {
                      $userQuery->where('PU_Name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('I_Status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('I_Category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority_level', $request->priority);
        }

        if ($request->filled('processor')) {
            $query->where('processed_by', $request->processor);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('processed_date', [$request->date_from, $request->date_to]);
        }

        $inquiries = $query->orderBy('processed_date', 'desc')->paginate(15);
        
        // Get processors for filter dropdown
        $processors = MCMC::whereIn('M_ID', 
            Inquiry::whereNotNull('processed_by')->pluck('processed_by')->unique()
        )->get();

        return view('MCMC.inquiries.processed', compact('inquiries', 'processors'));
    }

    /**
     * Generate inquiry reports
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:monthly,yearly,custom',
            'date_from' => 'required_if:report_type,custom|date',
            'date_to' => 'required_if:report_type,custom|date|after_or_equal:date_from',
            'month' => 'required_if:report_type,monthly|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        // Determine date range
        switch ($request->report_type) {
            case 'monthly':
                $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::createFromDate($request->year, 1, 1)->startOfYear();
                $endDate = $startDate->copy()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->date_from);
                $endDate = Carbon::parse($request->date_to);
                break;
        }

        // Get report data
        $reportData = $this->getReportData($startDate, $endDate);
        
        return view('MCMC.reports.inquiry-report', compact('reportData', 'startDate', 'endDate'));
    }

    /**
     * Export report in PDF format
     */
    public function exportReportPDF(Request $request)
    {
        // Validate and get date range (same as generateReport)
        $request->validate([
            'report_type' => 'required|in:monthly,yearly,custom',
            'date_from' => 'required_if:report_type,custom|date',
            'date_to' => 'required_if:report_type,custom|date|after_or_equal:date_from',
            'month' => 'required_if:report_type,monthly|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        // Get date range
        switch ($request->report_type) {
            case 'monthly':
                $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::createFromDate($request->year, 1, 1)->startOfYear();
                $endDate = $startDate->copy()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->date_from);
                $endDate = Carbon::parse($request->date_to);
                break;
        }

        $reportData = $this->getReportData($startDate, $endDate);
        
        $pdf = PDF::loadView('MCMC.reports.inquiry-report-pdf', compact('reportData', 'startDate', 'endDate'));
        
        $filename = 'inquiry_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export report in Excel format
     */
    public function exportReportExcel(Request $request)
    {
        // Similar validation and date range logic
        $request->validate([
            'report_type' => 'required|in:monthly,yearly,custom',
            'date_from' => 'required_if:report_type,custom|date',
            'date_to' => 'required_if:report_type,custom|date|after_or_equal:date_from',
            'month' => 'required_if:report_type,monthly|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1)
        ]);

        // Get date range
        switch ($request->report_type) {
            case 'monthly':
                $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                break;
            case 'yearly':
                $startDate = Carbon::createFromDate($request->year, 1, 1)->startOfYear();
                $endDate = $startDate->copy()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->date_from);
                $endDate = Carbon::parse($request->date_to);
                break;
        }

        $reportData = $this->getReportData($startDate, $endDate);
        
        // Create CSV export
        $filename = 'inquiry_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($reportData) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, [
                'Inquiry ID', 'Title', 'Category', 'Status', 'Priority', 'User Name', 
                'User Email', 'Submission Date', 'Processor', 'Processing Date', 'Notes'
            ]);
            
            // Write data
            foreach ($reportData['inquiries'] as $inquiry) {
                fputcsv($file, [
                    $inquiry->I_ID ?? 'N/A',
                    $inquiry->I_Title ?? 'N/A',
                    $inquiry->I_Category ?? 'N/A',
                    $inquiry->I_Status ?? 'N/A',
                    $inquiry->priority_level ?? 'N/A',
                    $inquiry->publicUser?->PU_Name ?? 'N/A',
                    $inquiry->publicUser?->PU_Email ?? 'N/A',
                    $inquiry->I_Date ? $inquiry->I_Date->format('Y-m-d') : 'N/A',
                    $inquiry->processor?->M_Name ?? 'Not Processed',
                    $inquiry->processed_date ? $inquiry->processed_date->format('Y-m-d H:i') : 'N/A',
                    $inquiry->mcmc_notes ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get activity log for audit trail
     */
    public function activityLog(Request $request)
    {
        $query = DB::table('activity_logs')
            ->join('mcmc', 'activity_logs.user_id', '=', 'mcmc.M_ID')
            ->select('activity_logs.*', 'mcmc.M_Name as processor_name');

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('activity_logs.created_at', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('action')) {
            $query->where('activity_logs.action', $request->action);
        }

        $activities = $query->orderBy('activity_logs.created_at', 'desc')->paginate(20);

        return view('MCMC.activity-log', compact('activities'));
    }

    /**
     * Private helper methods
     */
    private function getReportData($startDate, $endDate)
    {
        $inquiries = Inquiry::with(['publicUser', 'processor'])
            ->whereBetween('I_Date', [$startDate, $endDate])
            ->orderBy('I_Date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_inquiries' => $inquiries->count(),
            'processed_inquiries' => $inquiries->whereNotNull('processed_by')->count(),
            'pending_inquiries' => $inquiries->where('I_Status', Inquiry::STATUS_PENDING)->count(),
            'validated_inquiries' => $inquiries->where('I_Status', Inquiry::STATUS_VALIDATED)->count(),
            'rejected_inquiries' => $inquiries->where('I_Status', Inquiry::STATUS_REJECTED)->count(),
            'non_serious_inquiries' => $inquiries->where('I_Status', Inquiry::STATUS_NON_SERIOUS)->count(),
        ];

        // Category breakdown
        $categoryStats = $inquiries->groupBy('I_Category')->map->count();
        
        // Status breakdown
        $statusStats = $inquiries->groupBy('I_Status')->map->count();
        
        // Priority breakdown
        $priorityStats = $inquiries->whereNotNull('priority_level')->groupBy('priority_level')->map->count();
        
        // Monthly trend (for yearly reports)
        $monthlyTrend = [];
        if ($startDate->diffInMonths($endDate) > 1) {
            for ($month = $startDate->copy(); $month <= $endDate; $month->addMonth()) {
                $monthlyTrend[$month->format('M Y')] = $inquiries->filter(function($inquiry) use ($month) {
                    return $inquiry->I_Date->format('Y-m') === $month->format('Y-m');
                })->count();
            }
        }

        return [
            'inquiries' => $inquiries,
            'stats' => $stats,
            'category_stats' => $categoryStats,
            'status_stats' => $statusStats,
            'priority_stats' => $priorityStats,
            'monthly_trend' => $monthlyTrend
        ];
    }

    private function logActivity($userId, $action, $description)
    {
        try {
            // Create activity log entry using the model
            ActivityLog::log($userId, $action, $description);
        } catch (\Exception $e) {
            // Log the error but don't break the flow
            logger('Failed to log activity: ' . $e->getMessage());
        }
    }
}
