<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';
    protected $primaryKey = 'R_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'R_ID',
        'R_Title',
        'R_Type',
        'R_Date',
        'R_Content',
        'R_GeneratedBy',
        'R_UserType',
        'R_Filters'
    ];

    protected $casts = [
        'R_Date' => 'datetime',
        'R_Filters' => 'array',
    ];

    // Generate unique report ID
    public static function generateReportId()
    {
        $lastReport = self::orderBy('R_ID', 'desc')->first();
        
        if (!$lastReport) {
            return 'RPT0001';
        }
        
        $lastNumber = intval(substr($lastReport->R_ID, 3));
        $newNumber = $lastNumber + 1;
        
        return 'RPT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Get assignment statistics by agency
    public static function getAssignmentStatsByAgency($startDate = null, $endDate = null, $agencyId = null)
    {
        $query = DB::table('complaint')
            ->join('agency', 'complaint.A_ID', '=', 'agency.A_ID')
            ->join('inquiry', 'complaint.I_ID', '=', 'inquiry.I_ID')
            ->select(
                'agency.A_Name',
                'agency.A_Category',
                DB::raw('COUNT(*) as total_assignments'),
                DB::raw('SUM(CASE WHEN complaint.C_VerificationStatus = "Pending" THEN 1 ELSE 0 END) as pending_verification'),
                DB::raw('SUM(CASE WHEN complaint.C_VerificationStatus = "Accepted" THEN 1 ELSE 0 END) as accepted'),
                DB::raw('SUM(CASE WHEN complaint.C_VerificationStatus = "Rejected" THEN 1 ELSE 0 END) as rejected'),
                DB::raw('SUM(CASE WHEN inquiry.I_Status = "In Progress" THEN 1 ELSE 0 END) as in_progress'),
                DB::raw('SUM(CASE WHEN inquiry.I_Status = "Resolved" THEN 1 ELSE 0 END) as resolved'),
                DB::raw('SUM(CASE WHEN inquiry.I_Status = "Closed" THEN 1 ELSE 0 END) as closed')
            )
            ->groupBy('agency.A_ID', 'agency.A_Name', 'agency.A_Category');

        if ($startDate && $endDate) {
            $query->whereBetween('complaint.C_AssignedDate', [$startDate, $endDate]);
        }

        if ($agencyId) {
            $query->where('agency.A_ID', $agencyId);
        }

        return $query->get();
    }

    // Get monthly assignment trends
    public static function getMonthlyAssignmentTrends($year = null, $agencyId = null)
    {
        $year = $year ?: date('Y');
        
        $query = DB::table('complaint')
            ->select(
                DB::raw('MONTH(C_AssignedDate) as month'),
                DB::raw('COUNT(*) as total_assignments')
            )
            ->whereYear('C_AssignedDate', $year)
            ->groupBy(DB::raw('MONTH(C_AssignedDate)'));
            
        if ($agencyId) {
            $query->where('A_ID', $agencyId);
        }
        
        $monthlyData = $query->get()->keyBy('month');
        
        // Fill in missing months with zero counts
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = [
                'month' => $i,
                'month_name' => Carbon::create($year, $i, 1)->format('F'),
                'total_assignments' => $monthlyData->get($i)->total_assignments ?? 0
            ];
        }
        
        return $result;
    }

    // Get category distribution
    public static function getCategoryDistribution($startDate = null, $endDate = null, $agencyId = null)
    {
        $query = DB::table('complaint')
            ->join('inquiry', 'complaint.I_ID', '=', 'inquiry.I_ID')
            ->select(
                'inquiry.I_Category as category',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('inquiry.I_Category');
            
        if ($startDate && $endDate) {
            $query->whereBetween('complaint.C_AssignedDate', [$startDate, $endDate]);
        }
        
        if ($agencyId) {
            $query->where('complaint.A_ID', $agencyId);
        }
        
        return $query->get();
    }

    // Get resolution time statistics
    public static function getResolutionTimeStats($startDate = null, $endDate = null, $agencyId = null)
    {
        $query = DB::table('complaint')
            ->join('inquiry', 'complaint.I_ID', '=', 'inquiry.I_ID')
            ->select(
                DB::raw('AVG(DATEDIFF(NOW(), complaint.C_AssignedDate)) as avg_days'),
                DB::raw('MIN(DATEDIFF(NOW(), complaint.C_AssignedDate)) as min_days'),
                DB::raw('MAX(DATEDIFF(NOW(), complaint.C_AssignedDate)) as max_days'),
                DB::raw('COUNT(CASE WHEN DATEDIFF(NOW(), complaint.C_AssignedDate) <= 7 THEN 1 END) as within_week'),
                DB::raw('COUNT(CASE WHEN DATEDIFF(NOW(), complaint.C_AssignedDate) > 7 AND DATEDIFF(NOW(), complaint.C_AssignedDate) <= 30 THEN 1 END) as within_month'),
                DB::raw('COUNT(CASE WHEN DATEDIFF(NOW(), complaint.C_AssignedDate) > 30 THEN 1 END) as over_month')
            );
            
        if ($startDate && $endDate) {
            $query->whereBetween('complaint.C_AssignedDate', [$startDate, $endDate]);
        }
        
        if ($agencyId) {
            $query->where('complaint.A_ID', $agencyId);
        }
        
        return $query->first();
    }
}