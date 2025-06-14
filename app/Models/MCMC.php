<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class MCMC extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mcmc';
    protected $primaryKey = 'M_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'M_ID',
        'M_Name',
        'M_userName',
        'M_Address',
        'M_Email',
        'M_PhoneNum',
        'M_Position',
        'M_Password',
        'M_ProfilePicture'
    ];

    protected $hidden = [
        'M_Password',
    ];

    // Override the default password field
    public function getAuthPassword()
    {
        return $this->M_Password;
    }

    // Override the default email field
    public function getEmailForPasswordReset()
    {
        return $this->M_Email;
    }

    // Override the default username field
    public function getAuthIdentifierName()
    {
        return 'M_Email';
    }

    public function getAuthIdentifier()
    {
        return $this->M_Email;
    }

    // Override the key name for sessions
    public function getKey()
    {
        return $this->M_ID;
    }

    // Mutator to hash password
    public function setMPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['M_Password'] = Hash::make($value);
        }
    }

    // Accessor for password field name
    public function getPasswordAttribute()
    {
        return $this->M_Password;
    }

    // Relationships
    public function processedInquiries()
    {
        return $this->hasMany(Inquiry::class, 'processed_by', 'M_ID');
    }

    // Activity logs for audit trail
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'M_ID');
    }
}