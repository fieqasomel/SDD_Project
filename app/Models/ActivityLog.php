<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with MCMC
    public function mcmcUser()
    {
        return $this->belongsTo(MCMC::class, 'user_id', 'M_ID');
    }

    // Activity types constants
    const ACTION_VALIDATE_INQUIRY = 'validate_inquiry';
    const ACTION_REJECT_INQUIRY = 'reject_inquiry';
    const ACTION_MARK_NON_SERIOUS = 'mark_non_serious';
    const ACTION_ASSIGN_INQUIRY = 'assign_inquiry';
    const ACTION_GENERATE_REPORT = 'generate_report';
    const ACTION_EXPORT_REPORT = 'export_report';

    public static function log($userId, $action, $description, $metadata = null)
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }
}
