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

        /* ================= KPI COUNTS ================= */
        $totalDays = $attendances->count();

        $presentCount = $attendances->where('status', 'present')->count();
        $halfDayCount = $attendances->where('status', 'half_day')->count();
        $belowHalfDayCount = $attendances->where('status', 'below_half_day')->count();
        $overtimeCount = $attendances->where('status', 'overtime')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $paidLeaveCount = $attendances->where('status', 'paid_leave')->count();
        $lateCheckinCheckoutCount = $attendances->where('status', 'late_checkin_checkout')->count();

        /* ================= INTERN SUMMARY ================= */
        $interns = Intern::orderBy('name')->get();
        $internSummaries = [];

        foreach ($interns as $intern) {
            $records = $attendances->where('intern_id', $intern->id);

            $internSummaries[] = [
                'id' => $intern->id,
                'name' => $intern->name,
                'present' => $records->where('status', 'present')->count(),
                'half_day' => $records->where('status', 'half_day')->count(),
                'below_half_day' => $records->where('status', 'below_half_day')->count(),
                'overtime' => $records->where('status', 'overtime')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'paid_leave' => $records->where('status', 'paid_leave')->count(),
                'late_checkin_checkout' => $records->where('status', 'late_checkin_checkout')->count(),
                'total' => $records->count(),
            ];
        }

        /* ================= BAR CHART (LAST 7 DAYS) ================= */
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
            'halfDayCount',
            'belowHalfDayCount',
            'overtimeCount',
            'absentCount',
            'paidLeaveCount',
            'lateCheckinCheckoutCount',
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

        $totalDays = $attendances->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $halfDayCount = $attendances->where('status', 'half_day')->count();
        $belowHalfDayCount = $attendances->where('status', 'below_half_day')->count();
        $overtimeCount = $attendances->where('status', 'overtime')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $paidLeaveCount = $attendances->where('status', 'paid_leave')->count();
        $lateCheckinCheckoutCount = $attendances->where('status', 'late_checkin_checkout')->count();

        return view('report.single', compact(
            'intern',
            'attendances',
            'totalDays',
            'presentCount',
            'halfDayCount',
            'belowHalfDayCount',
            'overtimeCount',
            'absentCount',
            'paidLeaveCount',
            'lateCheckinCheckoutCount',
            'fromDate',
            'toDate'
        ));
    }
}