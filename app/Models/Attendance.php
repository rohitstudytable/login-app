<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'intern_id',
        'date',
        'status',
        'location',
        'in_time',
        'out_time',
        'photo',
    ];

    /**
     * Correct casts
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relation: Attendance belongs to Intern / Employee
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Helper: get current working date
     */
    public static function currentDate()
    {
        return now()->toDateString();
    }

    /**
     * Accessor: format in_time
     */
    public function getInTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i') : null;
    }

    /**
     * Accessor: format out_time
     */
    public function getOutTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('H:i:s', $value)->format('H:i') : null;
    }

    /**
     * Helper: calculate working duration
     */
    public function getWorkingHoursAttribute()
    {
        if ($this->in_time && $this->out_time) {
            $in  = Carbon::createFromFormat('H:i', $this->in_time);
            $out = Carbon::createFromFormat('H:i', $this->out_time);

            return $in->diff($out)->format('%H:%I');
        }

        return null;
    }
}
