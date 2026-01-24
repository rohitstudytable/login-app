<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Optional if Laravel can infer 'attendances' automatically.
     */
    protected $table = 'attendances';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'intern_id',
        'status',
        'photo',
        'created_at', // optional if you want to override timestamp
        'date',
    ];

    /**
     * Cast attributes to proper types
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'attendance_date' => 'date', // generated column in DB
    ];

    /**
     * Attendance belongs to an intern
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Optional helper: get date only for attendance
     */
    // public function getDateAttribute()
    // {
    //     return $this->created_at->toDateString();
    // }
}
