<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
<<<<<<< HEAD
    use HasApiTokens, HasFactory, Notifiable;
=======
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
<<<<<<< HEAD
        'U_Name',
        'U_Email',
        'U_Password',
        'U_Role',
=======
        'name',
        'email',
        'password',
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
<<<<<<< HEAD
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
=======
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
            'password' => 'hashed',
        ];
    }
}
>>>>>>> 4359da4baaff1ab2cb6f67b12512ab9f32b9b586
