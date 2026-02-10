<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Intern;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * OVERALL DASHBOARD REPORT (ALL INTERNS)
     */
    public function index(Request $request)
    {
        // Date filters
        $fromDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : null;

        $toDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        // Attendance query
        $query = Attendance::with('intern')->orderBy('date', 'desc');

        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        }

        $attendances = $query->get();

        // KPI counts
        $totalDays    = $attendances->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $halfDayCount = $attendances->where('status', 'half_day')->count();

        // Intern-wise summary
        $interns = Intern::orderBy('name')->get();
        $internSummaries = [];

        foreach ($interns as $intern) {
            $records = $attendances->where('intern_id', $intern->id);

            $internSummaries[] = [
                'id'       => $intern->id,
                'name'     => $intern->name,
                'present'  => $records->where('status', 'present')->count(),
                'half_day' => $records->where('status', 'half_day')->count(),
                'absent'   => $records->where('status', 'absent')->count(),
                'total'    => $records->count(),
            ];
        }

        /**
         * BAR CHART DATA (LAST 7 DAYS)
         */
        $last7Dates = [];
        $last7PresentCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();

            $count = Attendance::whereDate('date', $date)
                ->where('status', 'present')
                ->count();

            $last7Dates[] = Carbon::parse($date)->format('d M');
            $last7PresentCounts[] = $count;
        }

        return view('report', compact(
            'attendances',
            'totalDays',
            'presentCount',
            'absentCount',
            'halfDayCount',
            'internSummaries',
            'last7Dates',
            'last7PresentCounts',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * SINGLE INTERN REPORT
     */
    public function show(Request $request, Intern $intern)
    {
        $fromDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : null;

        $toDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        $query = Attendance::where('intern_id', $intern->id)
            ->orderBy('date', 'desc');

        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        }

        $attendances = $query->get();

        $totalDays    = $attendances->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $halfDayCount = $attendances->where('status', 'half_day')->count();

        return view('report.single', compact(
            'intern',
            'attendances',
            'totalDays',
            'presentCount',
            'absentCount',
            'halfDayCount',
            'fromDate',
            'toDate'
        ));
    }
}
