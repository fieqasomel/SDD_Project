<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Agency;
use App\Models\Complaint;
use App\Models\Inquiry;
use App\Models\PublicUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssignmentsExport;

class ReportController extends Controller
{
    /**
     * Generate assignment report
     */
    public function generateAssignedReport(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Only MCMC users can generate reports
        if ($userType !== 'mcmc') {
            return redirect()->route('assignments.index')->with('error', 'Only MCMC users can generate assignment reports.');
        }
        
        // Get filter parameters
        $startDate = $request->filled('date_from') ? $request->date_from : Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('date_to') ? $request->date_to : Carbon::now()->endOfMonth()->format('Y-m-d');
        $agencyId = $request->filled('agency') ? $request->agency : null;
        $filterPeriod = $request->filled('period') ? $request->period : 'custom';
        
        // Apply date filters based on period
        if ($filterPeriod === 'this_month') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($filterPeriod === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        } elseif ($filterPeriod === 'this_year') {
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
        }
        
        // Get agency name if filtered by agency
        $agencyName = null;
        if ($agencyId) {
            $agency = Agency::find($agencyId);
            $agencyName = $agency ? $agency->A_Name : null;
        }
        
        // Get report data
        $agencyStats = Report::getAssignmentStatsByAgency($startDate, $endDate, $agencyId);
        $monthlyTrends = Report::getMonthlyAssignmentTrends(Carbon::parse($startDate)->year, $agencyId);
        $categoryDistribution = Report::getCategoryDistribution($startDate, $endDate, $agencyId);
        $resolutionTimeStats = Report::getResolutionTimeStats($startDate, $endDate, $agencyId);
        
        // Get overall statistics
        $totalAssignments = $agencyStats->sum('total_assignments');
        $totalPending = $agencyStats->sum('pending_verification');
        $totalAccepted = $agencyStats->sum('accepted');
        $totalRejected = $agencyStats->sum('rejected');
        $totalInProgress = $agencyStats->sum('in_progress');
        $totalResolved = $agencyStats->sum('resolved');
        $totalClosed = $agencyStats->sum('closed');
        
        // Format data for charts
        $agencyLabels = $agencyStats->pluck('A_Name')->toArray();
        $agencyData = $agencyStats->pluck('total_assignments')->toArray();
        
        $monthLabels = collect($monthlyTrends)->pluck('month_name')->toArray();
        $monthData = collect($monthlyTrends)->pluck('total_assignments')->toArray();
        
        $categoryLabels = $categoryDistribution->pluck('category')->toArray();
        $categoryData = $categoryDistribution->pluck('count')->toArray();
        
        $statusLabels = ['Pending', 'Accepted', 'Rejected', 'In Progress', 'Resolved', 'Closed'];
        $statusData = [$totalPending, $totalAccepted, $totalRejected, $totalInProgress, $totalResolved, $totalClosed];
        
        // Prepare chart data
        $chartData = [
            'agencyChart' => [
                'labels' => $agencyLabels,
                'data' => $agencyData
            ],
            'monthlyChart' => [
                'labels' => $monthLabels,
                'data' => $monthData
            ],
            'categoryChart' => [
                'labels' => $categoryLabels,
                'data' => $categoryData
            ],
            'statusChart' => [
                'labels' => $statusLabels,
                'data' => $statusData
            ]
        ];
        
        // Get all agencies for filter dropdown
        $agencies = Agency::orderBy('A_Name')->get();
        
        // Check if export is requested
        if ($request->has('export')) {
            $exportType = $request->export;
            
            if ($exportType === 'pdf') {
                return $this->exportToPdf($agencyStats, $chartData, $startDate, $endDate, $agencyName);
            } elseif ($exportType === 'excel') {
                return $this->exportToExcel($agencyStats, $startDate, $endDate, $agencyName);
            }
        }
        
        // Store report data in session for later export
        session(['report_data' => [
            'agencyStats' => $agencyStats,
            'chartData' => $chartData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'agencyName' => $agencyName
        ]]);
        
        return view('ManageAssignment.AssignmentReport', compact(
            'agencyStats',
            'monthlyTrends',
            'categoryDistribution',
            'resolutionTimeStats',
            'chartData',
            'totalAssignments',
            'totalPending',
            'totalAccepted',
            'totalRejected',
            'totalInProgress',
            'totalResolved',
            'totalClosed',
            'agencies',
            'startDate',
            'endDate',
            'agencyId',
            'filterPeriod'
        ));
    }
    
    /**
     * Export report to PDF
     */
    private function exportToPdf($agencyStats, $chartData, $startDate, $endDate, $agencyName = null)
    {
        $title = 'Assignment Report';
        $subtitle = 'Period: ' . Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y');
        
        if ($agencyName) {
            $subtitle .= ' | Agency: ' . $agencyName;
        }
        
        $pdf = PDF::loadView('ManageAssignment.AssignmentReportPdf', compact(
            'agencyStats',
            'chartData',
            'title',
            'subtitle'
        ));
        
        return $pdf->download('assignment_report_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Export report to Excel
     */
    private function exportToExcel($agencyStats, $startDate, $endDate, $agencyName = null)
    {
        $fileName = 'assignment_report_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new AssignmentsExport($agencyStats, $startDate, $endDate, $agencyName), $fileName);
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
}