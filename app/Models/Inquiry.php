<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
<<<<<<< HEAD
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
=======
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
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
<<<<<<< HEAD
        'InfoPath',
        'mcmc_notes',
        'mcmc_processed_by',
        'mcmc_processed_at',
        'rejection_reason'
=======
        'InfoPath'
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
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

<<<<<<< HEAD
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
=======
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

    // Get all available statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
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
        switch (strtolower($this->I_Status)) {
            case 'pending':
            case self::STATUS_PENDING:
                return 'warning'; // yellow
<<<<<<< HEAD
            case 'approved':
            case self::STATUS_APPROVED:
                return 'primary'; // blue
            case 'rejected':
            case self::STATUS_REJECTED:
                return 'danger'; // red
=======
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
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
<<<<<<< HEAD
=======
            case 'rejected':
                return 'danger'; // red
>>>>>>> 7c0c8ae950046f3a42dd8665bd731039ac9f90ff
            default:
                return 'secondary'; // gray
        }
    }
}