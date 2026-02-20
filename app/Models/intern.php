<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Intern extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic
        'name',
        'email',
        'contact',

        // Auth
        'password',
        'plain_password',

        // IDs & role
        'random_id',
        'intern_code',
        'role',

        // Personal details
        'gender',
        'dob',
        'blood_group',
        'marital_status',
        'nationality',

        // Address
        'address',
        'city',
        'state',
        'pin',

        // Work profile
        'designation',

        // Profile image
        'img',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'plain_password',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => 'intern',
    ];

    /**
     * Booted method to handle model events.
     */
    protected static function booted()
    {
        static::creating(function ($intern) {
            // Auto-generate random_id
            if (empty($intern->random_id)) {
                $intern->random_id = Str::random(10);
            }

            // Default role
            if (empty($intern->role)) {
                $intern->role = 'intern';
            }
        });
    }

    /**
     * Relationship: Intern has many attendance records.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
