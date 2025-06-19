<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
=======
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
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
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249

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
<<<<<<< HEAD
=======
        'name',
        'email',
        'password',
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'U_Password',
<<<<<<< HEAD
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
            'U_Password' => 'hashed',
            'password' => 'hashed',
        ];
    }

    /**
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
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
<<<<<<< HEAD
        return $this->U_Password;
=======
        return $this->U_Password ?? $this->password;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
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
<<<<<<< HEAD
        return $this->U_Name;
=======
        return $this->attributes['name'] ?? $this->U_Name;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
    }

    /**
     * Accessor for email attribute
     */
    public function getEmailAttribute()
    {
<<<<<<< HEAD
        return $this->U_Email;
=======
        return $this->attributes['email'] ?? $this->U_Email;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
    }

    /**
     * Accessor for password attribute
     */
    public function getPasswordAttribute()
    {
<<<<<<< HEAD
        return $this->U_Password;
=======
        return $this->attributes['password'] ?? $this->U_Password;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
    }

    /**
     * Mutator for name attribute
     */
    public function setNameAttribute($value)
    {
        $this->attributes['U_Name'] = $value;
<<<<<<< HEAD
=======
        $this->attributes['name'] = $value;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
    }

    /**
     * Mutator for email attribute
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['U_Email'] = $value;
<<<<<<< HEAD
=======
        $this->attributes['email'] = $value;
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
    }

    /**
     * Mutator for password attribute
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['U_Password'] = $value;
<<<<<<< HEAD
    }
}
=======
        $this->attributes['password'] = $value;
    }
}
>>>>>>> cbe7183a760c500e45566973f9f28657497c8249
