<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EodReport extends Model
{
    use HasFactory;

    protected $table = 'eod_reports';

    protected $fillable = [
        'intern_id',
        'report_date',
        'tasks_completed',
        'challenges_faced',
        'plan_for_tomorrow',
    ];

    protected $casts = [
        'report_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: EOD belongs to an Intern.
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    public function adminEodIndex()
{
    $reports = EodReport::with('intern')
        ->latest('report_date')
        ->latest('created_at')
        ->get();

    return view('admin.eod.index', compact('reports'));
}
}