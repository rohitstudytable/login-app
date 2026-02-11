<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Enables login via Auth
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Intern extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plain_password',
        'random_id',
        'intern_code', // manually assigned by admin
        'contact',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays (security).
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string,mixed>
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
            // Generate a random_id automatically if not provided
            if (empty($intern->random_id)) {
                $intern->random_id = Str::random(10);
            }

            // Ensure role defaults to 'intern'
            if (empty($intern->role)) {
                $intern->role = 'intern';
            }
        });
    }

    /**
     * Relationship: An intern can have many attendance records.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
