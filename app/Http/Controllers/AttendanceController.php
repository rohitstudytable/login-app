<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Intern;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AttendanceController extends Controller
{

    /*
    |--------------------------------------------------------------------------  
    | ADMIN – DAILY ATTENDANCE PAGE
    |--------------------------------------------------------------------------  
    */
    public function index(Request $request)
    {
        $attendanceDate = $request->filter_date
            ? Carbon::parse($request->filter_date)->format('Y-m-d')
            : now()->format('Y-m-d');

        $internQuery = Intern::query();

        if ($request->filled('role')) {
            $internQuery->where('role', $request->role);
        }

        $interns = $internQuery->orderBy('name')->get();

        $recordsForDate = Attendance::whereDate('date', $attendanceDate)
            ->whereIn('intern_id', $interns->pluck('id'))
            ->get();

        $allRecordsQuery = Attendance::with('intern')
            ->whereIn('intern_id', $interns->pluck('id'))
            ->orderBy('date', 'desc');

        if ($request->filled('filter_date')) {
            $allRecordsQuery->whereDate('date', $request->filter_date);
        }

        if ($request->filled('filter_name')) {
            $allRecordsQuery->whereHas('intern', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->filter_name . '%');
            });
        }

        $allRecords = $allRecordsQuery->get();

        $internIds = $interns->pluck('id');

        $presentCount = Attendance::whereIn('intern_id', $internIds)
            ->where('status', 'present')
            ->count();

        $absentCount = Attendance::whereIn('intern_id', $internIds)
            ->where('status', 'absent')
            ->count();

        $halfDayCount = Attendance::whereIn('intern_id', $internIds)
            ->where('status', 'half_day')
            ->count();

        $paidLeaveCount = Attendance::whereIn('intern_id', $internIds)
            ->where('status', 'paid_leave')
            ->count();

        return view('attendance.index', compact(
            'interns',
            'recordsForDate',
            'allRecords',
            'attendanceDate',
            'presentCount',
            'absentCount',
            'halfDayCount',
            'paidLeaveCount'
        ));
    }

    // ------------------- ADMIN ATTENDCE UPDTED SAVE-------------------------

   public function store(Request $request)
{
    // ✅ VALIDATION (INCLUDING STATUS)
    $request->validate([
        'date' => 'required|date',
        'interns' => 'required|array',
        'interns.*.location' => 'nullable|string|max:255',
        'interns.*.in_time' => 'nullable|date_format:H:i',
        'interns.*.out_time' => 'nullable|date_format:H:i',
        'interns.*.status' => 'nullable|in:present,half_day,below_half_day,overtime,absent,paid_leave,late_checkin_checkout',
    ]);

    $attendanceDate = Carbon::parse($request->date)->format('Y-m-d');

    if ($this->isHolidayOrSunday($attendanceDate)) {
        return back()->with('error', 'Attendance cannot be marked on holidays or Sundays');
    }

    DB::transaction(function () use ($request, $attendanceDate) {

        foreach ($request->interns as $internId => $data) {

            // Save / update attendance
            $attendance = Attendance::updateOrCreate(
                [
                    'intern_id' => $internId,
                    'date' => $attendanceDate,
                ],
                [
                    'location' => $data['location'] ?? 'Admin Marked',
                    'in_time'  => $data['in_time'] ?? null,
                    'out_time' => $data['out_time'] ?? null,
                ]
            );

            // ✅ If admin manually selected status, use it
            if (!empty($data['status'])) {
                $attendance->update([
                    'status' => $data['status'],
                    'worked_minutes' => $attendance->in_time && $attendance->out_time
                        ? \Carbon\Carbon::parse($attendance->in_time)
                            ->diffInMinutes(\Carbon\Carbon::parse($attendance->out_time))
                        : null,
                ]);
            } else {
                // 🔥 AUTO-CALCULATE STATUS FROM TIME if no manual status
                if ($attendance->in_time && $attendance->out_time) {
                    $this->calculateWorkAndStatus($attendance);
                } else {
                    // If no time, mark absent safely
                    $attendance->update([
                        'status' => 'absent',
                        'worked_minutes' => null,
                    ]);
                }
            }
        }
    });

    return redirect()->route('attendance.index', [
        'filter_date' => $attendanceDate,
        'role' => $request->role
    ])->with('success', 'Attendance updated for ' . $attendanceDate);
}

    /*
    |--------------------------------------------------------------------------  
    | ADMIN – SINGLE INTERN ATTENDANCE HISTORY
    |--------------------------------------------------------------------------  
    */
    public function show(Request $request, $id)
    {
        $intern = Intern::findOrFail($id);

        $query = Attendance::where('intern_id', $id);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return view('attendance.show', [
            'intern' => $intern,
            'attendances' => $attendances,
            'totalDays' => $attendances->count(),
            'presentCount' => $attendances->where('status', 'present')->count(),
            'absentCount' => $attendances->where('status', 'absent')->count(),
            'halfDayCount' => $attendances->where('status', 'half_day')->count(),
            'paidLeaveCount' => $attendances->where('status', 'paid_leave')->count(),
        ]);
    }

    public function searchEmpCode()
    {
        return view('attendance.empcode');
    }

    public function searchByEmployeeId(Request $request)
    {
        $request->validate(['employee_id' => 'required']);

        $intern = Intern::where('intern_code', $request->employee_id)->first();

        if (!$intern) {
            return back()->with('error', 'Employee not found');
        }
        
        return redirect()->route('attendance.publicFormByToken', [
            'date' => now()->format('Y-m-d'),
            'token' => $intern->intern_code,
        ]);
    }

    /*
    |--------------------------------------------------------------------------  
    | EMPLOYEE – ATTENDANCE PAGE
    |--------------------------------------------------------------------------  
    */
    public function publicFormByToken($date, $token)
    {
        $intern = Intern::where('intern_code', $token)->firstOrFail();
        $date = Carbon::parse($date)->format('Y-m-d');

        $isHoliday = $this->isHolidayOrSunday($date);

        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $date)
            ->first();

        $recentAttendances = Attendance::where('intern_id', $intern->id)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        $allAttendances = Attendance::where('intern_id', $intern->id)->get();

        return view('attendance.empAttendance', [
            'intern' => $intern,
            'date' => $date,
            'attendance' => $todayAttendance,
            'todayAttendance' => $todayAttendance,
            'isHoliday' => $isHoliday,
            'recentAttendances' => $recentAttendances,
            'totalDays' => $allAttendances->count(),
            'presentCount' => $allAttendances->where('status', 'present')->count(),
            'absentCount' => $allAttendances->where('status', 'absent')->count(),
            'halfDayCount' => $allAttendances->where('status', 'half_day')->count(),
            'paidLeaveCount' => $allAttendances->where('status', 'paid_leave')->count()
        ]);
    }

    /*
    |--------------------------------------------------------------------------  
    | EMPLOYEE – CLOCK IN / OUT (AJAX)
    |--------------------------------------------------------------------------  
    */
    public function publicStoreByToken(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'action' => 'required|in:in,out',
        ]);

        $attendanceDate = Carbon::parse($request->date)->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        $intern = Intern::findOrFail($request->intern_id);

        if ($this->isHolidayOrSunday($attendanceDate)) {
            return response()->json([
                'success' => false,
                'error' => 'Today is holiday. Attendance closed.'
            ]);
        }

        $attendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $attendanceDate)
            ->first();

        // CLOCK IN
        if ($request->action === 'in') {
            if (!$attendance) {
                Attendance::create([
                    'intern_id' => $intern->id,
                    'date' => $attendanceDate,
                    'status' => null,
                    'location' => $request->location,
                    'in_time' => $currentTime,
                ]);

                return response()->json([
                    'success' => true,
                    'action' => 'in',
                    'time' => $currentTime,
                ]);
            } elseif ($attendance->in_time) {
                return response()->json([
                    'success' => false,
                    'error' => 'Already clocked in today.'
                ]);
            }
        }

       // CLOCK OUT
        if ($request->action === 'out') {
            if ($attendance && $attendance->in_time && !$attendance->out_time) {

                $attendance->update([
                    'out_time' => $currentTime
                ]);

                // 🔥 Updated status calculation
                $this->calculateWorkAndStatus($attendance);

                return response()->json([
                    'success' => true,
                    'action' => 'out',
                    'time' => $currentTime,
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'You need to clock in first or already clocked out.'
                ]);
            }
        }

        /* ✅ ADD THIS FINAL RETURN */
        return response()->json([
            'success' => false,
            'error' => 'Attendance already completed for today.'
        ]);

        } // ✅ CLOSE publicStoreByToken()

        private function calculateWorkAndStatus($attendance)
        {
            if (!$attendance->in_time || !$attendance->out_time) {
                return;
            }

            $in = Carbon::parse($attendance->in_time);
            $out = Carbon::parse($attendance->out_time);

            $workedMinutes = $in->diffInMinutes($out);

            // ✅ Map worked minutes to enum values
            if ($workedMinutes >= 540) {
                $status = 'overtime';
            } elseif ($workedMinutes >= 465) {
                $status = 'present';
            } elseif ($workedMinutes >= 420) {
                $status = 'late_checkin_checkout'; // Early Checkout / Late Check-in
            } elseif ($workedMinutes >= 240) {
                $status = 'half_day';
            } elseif ($workedMinutes >= 120) {
                $status = 'below_half_day';
            } else {
                $status = 'absent';
            }

            $attendance->update([
                'worked_minutes' => $workedMinutes,
                'status' => $status,
            ]);
        }
        // ANOTHER
        
    public function empAttendance()
    {
        $intern = Auth::guard('intern')->user();
        return $this->publicFormByToken(now()->format('Y-m-d'), $intern->intern_code);
    }

    public function empDashboard()
    {
        $intern = Auth::guard('intern')->user();
        $allAttendances = Attendance::where('intern_id', $intern->id)->get();

        return view('attendance.empDashboard', [
            'intern' => $intern,
            'totalDays' => $allAttendances->count(),
            'presentCount' => $allAttendances->where('status', 'present')->count(),
            'absentCount' => $allAttendances->where('status', 'absent')->count(),
            'halfDayCount' => $allAttendances->where('status', 'half_day')->count(),
            'paidLeaveCount' => $allAttendances->where('status', 'paid_leave')->count(),
        ]);
    }

    private function isHolidayOrSunday($date)
    {
        $checkDate = Carbon::parse($date);
        if ($checkDate->isSunday())
            return true;
        return Holiday::whereDate('holiday_date', $checkDate)->exists();
    }

    /*
    |--------------------------------------------------------------------------  
    | EMPLOYEE – ATTENDANCE REPORT PAGE
    |--------------------------------------------------------------------------  
    */
    public function empReport(Request $request)
    {
        $intern = Auth::guard('intern')->user();

        if (!$intern) {
            return redirect()->route('login');
        }

        $query = Attendance::where('intern_id', $intern->id);

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return view('attendance.empReport', [
            'intern' => $intern,
            'attendances' => $attendances,
            'presentCount' => $attendances->where('status', 'present')->count(),
            'absentCount' => $attendances->where('status', 'absent')->count(),
            'halfDayCount' => $attendances->where('status', 'half_day')->count(),
            'paidLeaveCount' => $attendances->where('status', 'paid_leave')->count(),
        ]);
    }

}
