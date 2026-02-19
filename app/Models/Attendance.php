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
     * Correct casting
     * ⚠️ in_time & out_time are TIME fields, not DATETIME
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'in_time'  => 'string',
        'out_time' => 'string',
    ];

    /**
     * Append virtual attribute
     */
    protected $appends = ['working_hours'];

    /**
     * Attendance belongs to Intern
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class)->withDefault([
            'name' => 'Deleted Intern',
            'intern_code' => 'N/A',
        ]);
    }

    /**
     * Helper: today date
     */
    public static function currentDate()
    {
        return now()->toDateString();
    }

    /**
     * Accessor: calculate working duration
     */
    public function getWorkingHoursAttribute()
    {
        if ($this->in_time && $this->out_time) {
            return Carbon::createFromFormat('H:i:s', $this->in_time)
                ->diff(Carbon::createFromFormat('H:i:s', $this->out_time))
                ->format('%H:%I');
        }

        return '--:--';
    }
}
