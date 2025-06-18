<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Agency extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'agency';
    protected $primaryKey = 'A_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'A_ID',
        'A_Name',
        'A_userName',
        'A_Address',
        'A_Email',
        'A_PhoneNum',
        'A_Category',
        'A_ProfilePicture',
        'A_Password'
    ];

    protected $hidden = [
        'A_Password',
    ];

    // Override the default password field
    public function getAuthPassword()
    {
        return $this->A_Password;
    }

    // Override the default email field
    public function getEmailForPasswordReset()
    {
        return $this->A_Email;
    }

    // Override the default username field
    public function getAuthIdentifierName()
    {
        return 'A_userName';
    }

    public function getAuthIdentifier()
    {
        return $this->A_userName;
    }

    // Override the key name for sessions
    public function getKey()
    {
        return $this->A_ID;
    }

    // Mutator to hash password
    public function setAPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['A_Password'] = Hash::make($value);
        }
    }

    // Accessor for password field name
    public function getPasswordAttribute()
    {
        return $this->A_Password;
    }
}