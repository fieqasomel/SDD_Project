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

    protected $casts = [
        'A_Category' => 'array',
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

    // Relationship with Complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'A_ID', 'A_ID');
    }

    // Helper method to check if agency handles a specific category
    // Modified to allow agencies to accept all categories of inquiry
    public function canHandle($category)
    {
        // Allow agencies to accept all categories regardless of their specified categories
        return true;
        
        // Original logic (commented out):
        // return in_array($category, $this->A_Category ?? []);
    }

    // Helper method to get categories as array
    public function getCategoriesAttribute()
    {
        return $this->A_Category ?? [];
    }

    // Helper method to get categories as string for display
    public function getCategoriesStringAttribute()
    {
        if (is_array($this->A_Category)) {
            return implode(', ', $this->A_Category);
        }
        return $this->A_Category ?? 'N/A';
    }

    // Scope to find agencies that handle a specific category
    // Modified to return all agencies regardless of category
    public function scopeHandlesCategory($query, $category)
    {
        // Return all agencies since they can now handle any category
        return $query;
        
        // Original logic (commented out):
        // return $query->whereRaw('JSON_CONTAINS(A_Category, ?)', [json_encode($category)]);
    }
}