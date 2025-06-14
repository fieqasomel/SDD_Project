<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class PublicUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'publicuser';
    protected $primaryKey = 'PU_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'PU_ID',
        'PU_Name',
        'PU_IC',
        'PU_Age',
        'PU_Address',
        'PU_Email',
        'PU_PhoneNum',
        'PU_Gender',
        'PU_Password',
        'PU_ProfilePicture'
    ];

    protected $casts = [
        'PU_Age' => 'integer',
    ];

    protected $hidden = [
        'PU_Password',
    ];

    // Override the default password field
    public function getAuthPassword()
    {
        return $this->PU_Password;
    }

    // Override the default email field
    public function getEmailForPasswordReset()
    {
        return $this->PU_Email;
    }

    // Override the default username field
    public function getAuthIdentifierName()
    {
        return 'PU_Email';
    }

    public function getAuthIdentifier()
    {
        return $this->PU_Email;
    }

    // Override the key name for sessions
    public function getKey()
    {
        return $this->PU_ID;
    }

    // Mutator to hash password
    public function setPuPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['PU_Password'] = Hash::make($value);
        }
    }

    // Accessor for password field name
    public function getPasswordAttribute()
    {
        return $this->PU_Password;
    }
}
