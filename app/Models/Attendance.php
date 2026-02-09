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

    protected $casts = [
        'date' => 'date',
        'in_time'  => 'datetime:H:i',
        'out_time' => 'datetime:H:i',
    ];

    protected $appends = ['working_hours'];

    /**
     * Relation: Attendance belongs to Intern
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
     * Accessor: calculate working duration
     */
    public function getWorkingHoursAttribute()
    {
        if ($this->in_time && $this->out_time) {
            return Carbon::parse($this->in_time)
                ->diff(Carbon::parse($this->out_time))
                ->format('%H:%I');
        }

        return null;
    }
}
