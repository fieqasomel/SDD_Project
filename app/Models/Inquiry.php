<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiry';
    protected $primaryKey = 'I_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'I_ID',
        'PU_ID',
        'I_Title',
        'I_Description',
        'I_Category',
        'I_Date',
        'I_Status',
        'I_Source',
        'I_filename',
        'InfoPath',
        'processed_by',
        'processed_date',
        'mcmc_notes',
        'priority_level',
        'is_serious'
    ];

    protected $casts = [
        'I_Date' => 'date',
        'processed_date' => 'datetime',
        'is_serious' => 'boolean',
    ];

    // Define inquiry statuses
    const STATUS_PENDING = 'Pending';
    const STATUS_UNDER_REVIEW = 'Under Review';
    const STATUS_VALIDATED = 'Validated';
    const STATUS_ASSIGNED = 'Assigned';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_RESOLVED = 'Resolved';
    const STATUS_CLOSED = 'Closed';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_NON_SERIOUS = 'Non-Serious';

    // Define inquiry categories
    const CATEGORIES = [
        'General Information',
        'Technical Support',
        'Billing',
        'Service Request',
        'Complaint',
        'Other'
    ];

    // Relationship with PublicUser
    public function publicUser()
    {
        return $this->belongsTo(PublicUser::class, 'PU_ID', 'PU_ID');
    }

    // Relationship with MCMC processor
    public function processor()
    {
        return $this->belongsTo(MCMC::class, 'processed_by', 'M_ID');
    }

    // Relationship with Progress (if inquiry has progress tracking)
    public function progress()
    {
        return $this->hasMany(Progress::class, 'I_ID', 'I_ID');
    }

    // Relationship with Complaint (assignment tracking)
    public function complaint()
    {
        return $this->hasOne(Complaint::class, 'I_ID', 'I_ID');
    }

    // Check if inquiry is assigned
    public function isAssigned()
    {
        return $this->complaint()->exists();
    }

    // Get assigned agency
    public function getAssignedAgency()
    {
        return $this->complaint ? $this->complaint->agency : null;
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('I_Status', $status);
    }

    // Scope for filtering by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('I_Category', $category);
    }

    // Scope for filtering by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('I_Date', [$startDate, $endDate]);
    }

    // Scope for searching by title or description
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('I_Title', 'like', "%{$search}%")
              ->orWhere('I_Description', 'like', "%{$search}%");
        });
    }

    // Generate unique inquiry ID
    public static function generateInquiryId()
    {
        $lastInquiry = self::orderBy('I_ID', 'desc')->first();
        
        if (!$lastInquiry) {
            return 'INQ0001';
        }
        
        $lastNumber = intval(substr($lastInquiry->I_ID, 3));
        $newNumber = $lastNumber + 1;
        
        return 'INQ' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Get status badge color for UI
    public function getStatusBadgeColor()
    {
        switch ($this->I_Status) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_IN_PROGRESS:
                return 'info';
            case self::STATUS_RESOLVED:
                return 'success';
            case self::STATUS_CLOSED:
                return 'secondary';
            case self::STATUS_REJECTED:
                return 'danger';
            default:
                return 'light';
        }
    }

    // Check if inquiry can be edited
    public function canBeEdited()
    {
        return in_array($this->I_Status, [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    // Check if inquiry can be deleted
    public function canBeDeleted()
    {
        return $this->I_Status === self::STATUS_PENDING;
    }
}