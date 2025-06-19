<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'N_ID';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'N_ID',
        'P_ID',
        'N_Message',
        'N_Timestamp',
        'N_Status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'N_Timestamp' => 'datetime',
    ];

    /**
     * Generate unique notification ID
     */
    public static function generateNotificationId()
    {
        $lastNotification = self::orderBy('N_ID', 'desc')->first();
        
        if (!$lastNotification) {
            return 'NOT0001';
        }
        
        $lastNumber = intval(substr($lastNotification->N_ID, 3));
        $newNumber = $lastNumber + 1;
        
        return 'NOT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create notification for MCMC about rejected assignment
     */
    public static function createRejectionNotification($complaint, $rejectionReason)
    {
        $message = "Assignment #{$complaint->C_ID} for inquiry \"{$complaint->inquiry->I_Title}\" has been rejected by {$complaint->agency->A_Name}. Reason: {$rejectionReason}";
        
        return self::create([
            'N_ID' => self::generateNotificationId(),
            'P_ID' => $complaint->M_ID, // MCMC who assigned it
            'N_Message' => $message,
            'N_Timestamp' => now(),
            'N_Status' => 'UNREAD'
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['N_Status' => 'READ']);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread()
    {
        return $this->N_Status === 'UNREAD';
    }
}