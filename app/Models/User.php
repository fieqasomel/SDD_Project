<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'U_Password',
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'U_Password' => 'hashed',
            'password' => 'hashed',
        ];
    }

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
        return $this->U_Password ?? $this->password;
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
        return $this->attributes['name'] ?? $this->U_Name;
    }

    /**
     * Accessor for email attribute
     */
    public function getEmailAttribute()
    {
        return $this->attributes['email'] ?? $this->U_Email;
    }

    /**
     * Accessor for password attribute
     */
    public function getPasswordAttribute()
    {
        return $this->attributes['password'] ?? $this->U_Password;
    }

    /**
     * Mutator for name attribute
     */
    public function setNameAttribute($value)
    {
        $this->attributes['U_Name'] = $value;
        $this->attributes['name'] = $value;
    }

    /**
     * Mutator for email attribute
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['U_Email'] = $value;
        $this->attributes['email'] = $value;
    }

    /**
     * Mutator for password attribute
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['U_Password'] = $value;
        $this->attributes['password'] = $value;
    }
}
