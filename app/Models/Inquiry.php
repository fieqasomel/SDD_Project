<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

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
        'mcmc_notes',
        'mcmc_processed_by',
        'mcmc_processed_at',
        'rejection_reason'
    ];

    protected $casts = [
    'I_Date' => 'datetime',
    ];

    // Relationship with PublicUser
    public function publicUser()
    {
        return $this->belongsTo(PublicUser::class, 'PU_ID', 'PU_ID');
    }

    // Relationship with Complaint
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'I_ID', 'I_ID');
    }

    // Singular relationship with Complaint (for latest assignment)
    public function complaint()
    {
        return $this->hasOne(Complaint::class, 'I_ID', 'I_ID')->latest('C_AssignedDate');
    }

    // Relationship with MCMC (who processed the inquiry)
    public function mcmcProcessor()
    {
        return $this->belongsTo(MCMC::class, 'mcmc_processed_by', 'M_ID');
    }

    // Helper methods for status checking
    public function isPending()
    {
        return $this->I_Status === self::STATUS_PENDING;
    }

    public function isInProgress()
    {
        return $this->I_Status === self::STATUS_IN_PROGRESS;
    }

    public function isResolved()
    {
        return $this->I_Status === self::STATUS_RESOLVED;
    }

    public function isClosed()
    {
        return $this->I_Status === self::STATUS_CLOSED;
    }

    public function isApproved()
    {
        return $this->I_Status === self::STATUS_APPROVED || $this->I_Status === 'Approved';
    }

    public function isRejected()
    {
        return $this->I_Status === self::STATUS_REJECTED || $this->I_Status === 'Rejected';
    }

    public function isAssigned()
    {
        return $this->complaints()->exists();
    }

    // Get all available statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->I_Status] ?? $this->I_Status;
    }

    // Check if inquiry can be edited
    public function canBeEdited()
    {
        // Inquiry can be edited if status is Pending or In Progress
        return in_array($this->I_Status, [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            'Pending',
            'In Progress'
        ]);
    }

    // Get status badge color for styling
    public function getStatusBadgeColor()
    {
        $status = $this->getSafeAttribute('I_Status');
        switch (strtolower($status)) {
            case 'pending':
            case self::STATUS_PENDING:
                return 'warning'; // yellow
            case 'approved':
            case self::STATUS_APPROVED:
                return 'primary'; // blue
            case 'rejected':
            case self::STATUS_REJECTED:
                return 'danger'; // red
            case 'in progress':
            case 'in_progress':
            case self::STATUS_IN_PROGRESS:
                return 'info'; // blue
            case 'resolved':
            case self::STATUS_RESOLVED:
                return 'success'; // green
            case 'closed':
            case self::STATUS_CLOSED:
                return 'secondary'; // gray
            case 'rejected':
                return 'danger'; // red
            default:
                return 'secondary'; // gray
        }
    }

    // Helper method to safely get attribute values, handling arrays
    public function getSafeAttribute($attribute)
    {
        $value = $this->getAttribute($attribute);
        
        if (is_array($value)) {
            // If it's an array, convert to JSON or return first element
            return is_array($value) && count($value) === 1 ? $value[0] : json_encode($value);
        }
        
        return $value ?? '';
    }

    // Safe accessors for problematic fields
    public function getSafeTitleAttribute()
    {
        return $this->getSafeAttribute('I_Title');
    }

    public function getSafeCategoryAttribute()
    {
        return $this->getSafeAttribute('I_Category');
    }

    public function getSafeStatusAttribute()
    {
        return $this->getSafeAttribute('I_Status');
    }

    public function getSafeDescriptionAttribute()
    {
        return $this->getSafeAttribute('I_Description');
    }
}