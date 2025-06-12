<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'U_Name',
        'U_Email',
        'U_Password',
        'U_Role',
    ];

    protected $hidden = [
        'U_Password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'U_Password' => 'hashed',
        ];
    }

    // Override the default password field
    public function getAuthPassword()
    {
        return $this->U_Password;
    }

    // Override the default email field
    public function getEmailForPasswordReset()
    {
        return $this->U_Email;
    }

    // Override the default username field
    public function getAuthIdentifierName()
    {
        return 'U_Email';
    }

    public function getAuthIdentifier()
    {
        return $this->U_Email;
    }
}