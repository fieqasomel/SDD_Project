<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaint';
    protected $primaryKey = 'C_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'C_ID',
        'I_ID',
        'A_ID',
        'M_ID',
        'C_AssignedDate',
        'C_Comment',
        'C_History',
        'C_VerificationStatus',
        'C_RejectionReason',
        'C_VerificationDate',
        'C_VerifiedBy'
    ];

    protected $casts = [
        'C_AssignedDate' => 'date',
        'C_VerificationDate' => 'datetime',
    ];

    // Relationship with Inquiry
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'I_ID', 'I_ID');
    }

    // Relationship with Agency
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'A_ID', 'A_ID');
    }

    // Relationship with MCMC
    public function mcmc()
    {
        return $this->belongsTo(MCMC::class, 'M_ID', 'M_ID');
    }

    // Generate unique complaint ID
    public static function generateComplaintId()
    {
        $lastComplaint = self::orderBy('C_ID', 'desc')->first();
        
        if (!$lastComplaint) {
            return 'CMP0001';
        }
        
        $lastNumber = intval(substr($lastComplaint->C_ID, 3));
        $newNumber = $lastNumber + 1;
        
        return 'CMP' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Scope for filtering by agency
    public function scopeByAgency($query, $agencyId)
    {
        return $query->where('A_ID', $agencyId);
    }

    // Scope for filtering by MCMC
    public function scopeByMCMC($query, $mcmcId)
    {
        return $query->where('M_ID', $mcmcId);
    }

    // Scope for filtering by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('C_AssignedDate', [$startDate, $endDate]);
    }

    // Add history entry
    public function addHistory($action, $userId, $userType)
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $historyEntry = "[{$timestamp}] {$userType} {$userId}: {$action}";
        
        $currentHistory = $this->C_History ? $this->C_History . "\n" : '';
        $this->C_History = $currentHistory . $historyEntry;
        $this->save();
    }

    // Get formatted history
    public function getFormattedHistory()
    {
        if (!$this->C_History) {
            return [];
        }

        $entries = explode("\n", $this->C_History);
        return array_filter($entries);
    }

    // Verification status constants
    const VERIFICATION_PENDING = 'Pending';
    const VERIFICATION_ACCEPTED = 'Accepted';
    const VERIFICATION_REJECTED = 'Rejected';

    // Check if assignment is pending verification
    public function isPendingVerification()
    {
        return $this->C_VerificationStatus === self::VERIFICATION_PENDING;
    }

    // Check if assignment is accepted
    public function isAccepted()
    {
        return $this->C_VerificationStatus === self::VERIFICATION_ACCEPTED;
    }

    // Check if assignment is rejected
    public function isRejected()
    {
        return $this->C_VerificationStatus === self::VERIFICATION_REJECTED;
    }

    // Get verification status badge color
    public function getVerificationBadgeColor()
    {
        switch ($this->C_VerificationStatus) {
            case self::VERIFICATION_PENDING:
                return 'warning';
            case self::VERIFICATION_ACCEPTED:
                return 'success';
            case self::VERIFICATION_REJECTED:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // Scope for filtering by verification status
    public function scopeByVerificationStatus($query, $status)
    {
        return $query->where('C_VerificationStatus', $status);
    }

    // Scope for pending verification
    public function scopePendingVerification($query)
    {
        return $query->where('C_VerificationStatus', self::VERIFICATION_PENDING);
    }

    // Scope for accepted assignments
    public function scopeAccepted($query)
    {
        return $query->where('C_VerificationStatus', self::VERIFICATION_ACCEPTED);
    }

    // Scope for rejected assignments
    public function scopeRejected($query)
    {
        return $query->where('C_VerificationStatus', self::VERIFICATION_REJECTED);
    }
}