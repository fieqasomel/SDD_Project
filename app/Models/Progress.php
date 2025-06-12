<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';
    protected $primaryKey = 'P_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'P_ID',
        'I_ID',
        'C_ID',
        'P_Title',
        'P_Description',
        'P_Date',
        'P_Status'
    ];

    protected $casts = [
        'P_Date' => 'date',
    ];

    // Relationship with Inquiry
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'I_ID', 'I_ID');
    }

    // Relationship with Complaint
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'C_ID', 'C_ID');
    }
}