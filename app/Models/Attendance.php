<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'intern_id',
        'date',
        'status',

        // locations
        'location',
        'in_location',
        'out_location',

        // time tracking
        'in_time',
        'out_time',
        'worked_minutes',

        // media
        'photo',
    ];

    /*
    |--------------------------------------------------------------------------
    | TYPE CASTING
    |--------------------------------------------------------------------------
    | TIME fields stored as string (MySQL TIME)
    */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'in_time' => 'string',
        'out_time' => 'string',
        'worked_minutes' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDED ATTRIBUTES
    |--------------------------------------------------------------------------
    */
    protected $appends = [
        'working_hours',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */
    public function intern()
    {
        return $this->belongsTo(Intern::class)->withDefault([
            'name' => 'Deleted Intern',
            'intern_code' => 'N/A',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: CURRENT DATE
    |--------------------------------------------------------------------------
    */
    public static function currentDate()
    {
        return now()->toDateString();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: WORKING HOURS (HH:MM)
    |--------------------------------------------------------------------------
    | Priority:
    | 1. worked_minutes column
    | 2. calculate from in/out time
    */
    public function getWorkingHoursAttribute()
    {
        // ✅ if already calculated
        if (!is_null($this->worked_minutes)) {
            $hours = floor($this->worked_minutes / 60);
            $minutes = $this->worked_minutes % 60;
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        // ✅ fallback calculate from time
        if ($this->in_time && $this->out_time) {
            $in = Carbon::createFromFormat('H:i:s', $this->in_time);
            $out = Carbon::createFromFormat('H:i:s', $this->out_time);

            $minutes = $in->diffInMinutes($out);
            $hours = floor($minutes / 60);
            $remaining = $minutes % 60;

            return sprintf('%02d:%02d', $hours, $remaining);
        }

        return '--:--';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: IS CLOCKED IN
    |--------------------------------------------------------------------------
    */
    public function isClockedIn()
    {
        return !is_null($this->in_time) && is_null($this->out_time);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: IS COMPLETED
    |--------------------------------------------------------------------------
    */
    public function isCompleted()
    {
        return !is_null($this->in_time) && !is_null($this->out_time);
    }
}