<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Intern extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'random_id',
        'intern_code',
        'contact',   // new field
        'role',      // new field
    ];

    protected static function booted()
    {
        static::creating(function ($intern) {
            // Auto-generate random_id if not set
            if (!$intern->random_id) {
                $intern->random_id = Str::random(10);
            }

            // Auto-generate intern_code if not set
            if (!$intern->intern_code) {
                $intern->intern_code = 'INT' . Carbon::now()->format('y') . Str::random(4);
            }

            // Default role if not set
            if (!$intern->role) {
                $intern->role = 'intern';
            }
        });
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
