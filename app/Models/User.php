<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'U_Name',
        'U_Email',
        'U_Password',
        'U_Role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'U_Password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'U_Password' => 'hashed',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->U_Password;
    }

    /**
     * Get the column name for the "email" attribute.
     *
     * @return string
     */
    public function getEmailColumn()
    {
        return 'U_Email';
    }

    /**
     * Accessor for name attribute
     */
    public function getNameAttribute()
    {
        return $this->U_Name;
    }

    /**
     * Accessor for email attribute
     */
    public function getEmailAttribute()
    {
        return $this->U_Email;
    }

    /**
     * Accessor for password attribute
     */
    public function getPasswordAttribute()
    {
        return $this->U_Password;
    }

    /**
     * Mutator for name attribute
     */
    public function setNameAttribute($value)
    {
        $this->attributes['U_Name'] = $value;
    }

    /**
     * Mutator for email attribute
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['U_Email'] = $value;
    }

    /**
     * Mutator for password attribute
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['U_Password'] = $value;
    }
}