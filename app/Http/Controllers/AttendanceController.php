<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Intern;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * ADMIN – Daily attendance page
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

        return view('attendance.index', compact(
            'interns',
            'recordsForDate',
            'allRecords',
            'attendanceDate'
        ));
    }

    /**
     * ADMIN – Save / Update attendance
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'interns' => 'required|array',
            'interns.*.status' => 'required|in:present,absent,half_day,paid_leave,unmark',
            'interns.*.location' => 'nullable|string|max:255',
            'interns.*.in_time' => 'nullable|date_format:H:i',
            'interns.*.out_time' => 'nullable|date_format:H:i',
        ]);

        $attendanceDate = Carbon::parse($request->date)->format('Y-m-d');

        if ($this->isHolidayOrSunday($attendanceDate)) {
            return back()->with('error', 'Attendance cannot be marked on holidays or Sundays');
        }

        DB::transaction(function () use ($request, $attendanceDate) {
            foreach ($request->interns as $internId => $data) {
                if ($data['status'] === 'unmark') {
                    Attendance::where('intern_id', $internId)
                        ->whereDate('date', $attendanceDate)
                        ->delete();
                    continue;
                }

                $existingAttendance = Attendance::where('intern_id', $internId)
                    ->whereDate('date', $attendanceDate)
                    ->first();

                if (in_array($data['status'], ['paid_leave', 'absent'])) {
                    Attendance::updateOrCreate(
                        ['intern_id' => $internId, 'date' => $attendanceDate],
                        [
                            'status' => $data['status'],
                            'location' => $data['location'] ?? 'Admin Marked',
                            'in_time' => null,
                            'out_time' => null
                        ]
                    );
                    continue;
                }

                Attendance::updateOrCreate(
                    ['intern_id' => $internId, 'date' => $attendanceDate],
                    [
                        'status' => $data['status'],
                        'location' => $data['location'] ?? ($existingAttendance->location ?? 'Admin Marked'),
                        'in_time' => $data['in_time'] ?? ($existingAttendance->in_time ?? null),
                        'out_time' => $data['out_time'] ?? ($existingAttendance->out_time ?? null),
                    ]
                );
            }
        });

        return redirect()->route('attendance.index', [
            'filter_date' => $attendanceDate,
            'role' => $request->role
        ])->with('success', 'Attendance updated for ' . $attendanceDate);
    }

    /**
     * ADMIN – Single intern history
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

    /**
     * PUBLIC – Search employee code page
     */
    public function searchEmpCode()
    {
        return view('attendance.empcode');
    }

    /**
     * PUBLIC – Submit employee code
     */
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

    /**
     * EMPLOYEE / INTERN – Attendance page
     */
    public function publicFormByToken($date, $token)
    {
        $intern = Intern::where('intern_code', $token)->firstOrFail();
        $date = Carbon::parse($date)->format('Y-m-d');

        $isHoliday = $this->isHolidayOrSunday($date);

        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $date)
            ->first();

        // ALL-TIME attendance stats
        $allAttendances = Attendance::where('intern_id', $intern->id)->get();

        $totalDays      = $allAttendances->count();
        $presentCount   = $allAttendances->where('status', 'present')->count();
        $absentCount    = $allAttendances->where('status', 'absent')->count();
        $halfDayCount   = $allAttendances->where('status', 'half_day')->count();
        $paidLeaveCount = $allAttendances->where('status', 'paid_leave')->count();

        return view('attendance.empAttendance', compact(
            'intern',
            'date',
            'todayAttendance',
            'isHoliday',
            'totalDays',
            'presentCount',
            'absentCount',
            'halfDayCount',
            'paidLeaveCount'
        ));
    }

    /**
     * EMPLOYEE / INTERN – Store attendance (Punch IN/OUT)
     */
    public function publicStoreByToken(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $attendanceDate = Carbon::parse($request->date)->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        $intern = Intern::findOrFail($request->intern_id);

        if ($this->isHolidayOrSunday($attendanceDate)) {
            return redirect()->route('attendance.publicFormByToken', [
                'date' => $attendanceDate,
                'token' => $intern->intern_code,
            ])->with('error', 'Today is holiday. Attendance closed.');
        }

        $attendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $attendanceDate)
            ->first();

        if (!$attendance) {
            Attendance::create([
                'intern_id' => $intern->id,
                'date' => $attendanceDate,
                'status' => 'present',
                'location' => $request->location,
                'in_time' => $currentTime,
            ]);

            return redirect()->route('attendance.publicFormByToken', [
                'date' => $attendanceDate,
                'token' => $intern->intern_code,
            ])->with('success', 'Punch IN recorded');
        }

        if ($attendance->in_time && !$attendance->out_time) {
            $attendance->update(['out_time' => $currentTime]);

            return redirect()->route('attendance.publicFormByToken', [
                'date' => $attendanceDate,
                'token' => $intern->intern_code,
            ])->with('success', 'Punch OUT recorded');
        }

        return redirect()->route('attendance.publicFormByToken', [
            'date' => $attendanceDate,
            'token' => $intern->intern_code,
        ])->with('error', 'Attendance already completed for today.');
    }

    /**
     * EMPLOYEE / INTERN – Dashboard shortcut
     */
    public function empAttendance()
    {
        $intern = Auth::guard('intern')->user();
        $date = now()->format('Y-m-d');

        return $this->publicFormByToken($date, $intern->intern_code);
    }

    /**
     * EMPLOYEE / INTERN – Dashboard stats for empDashboard.blade.php
     */
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

    /**
     * CHECK HOLIDAY OR SUNDAY
     */
    private function isHolidayOrSunday($date)
    {
        $checkDate = Carbon::parse($date);

        if ($checkDate->isSunday()) return true;

        return Holiday::whereDate('holiday_date', $checkDate)->exists();
    }
}
