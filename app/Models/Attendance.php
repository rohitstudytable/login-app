<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'intern_id',
        'date',
        'status',
        'photo',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    // Helper to get current working date
    public static function currentDate()
    {
        return now()->toDateString();
        // OR:
        // return self::orderBy('date', 'desc')->value('date') ?? now()->toDateString();
    }
}
