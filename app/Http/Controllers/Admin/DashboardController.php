<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        /* =====================
         | TODAY
         ===================== */
        $presentCount = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->count();

        $absentCount = Attendance::whereDate('date', $today)
            ->where('status', 'absent')
            ->count();

        $halfDayCount = Attendance::whereDate('date', $today)
            ->where('status', 'half_day')
            ->count();

        /* =====================
         | MONTHLY (PRESENT ONLY)
         ===================== */
        $monthlyData = Attendance::where('status', 'present')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->select(
                DB::raw('DAY(date) as day'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        /* =====================
         | LAST 7 DAYS TREND
         ===================== */
        $trendData = Attendance::where('status', 'present')
            ->whereDate('date', '>=', Carbon::now()->subDays(6))
            ->select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', compact(
            'presentCount',
            'absentCount',
            'halfDayCount',
            'monthlyData',
            'trendData'
        ));
    }
}
