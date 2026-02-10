<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Intern;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($request, $attendanceDate) {

            foreach ($request->interns as $internId => $data) {

                // 🔴 UNMARK → DELETE
                if ($data['status'] === 'unmark') {
                    Attendance::where('intern_id', $internId)
                        ->whereDate('date', $attendanceDate)
                        ->delete();
                    continue;
                }

                // Get existing record (if any)
                $existingAttendance = Attendance::where('intern_id', $internId)
                    ->whereDate('date', $attendanceDate)
                    ->first();

                // 🟡 LEAVE / ABSENT
                if (in_array($data['status'], ['paid_leave', 'absent'])) {
                    Attendance::updateOrCreate(
                        [
                            'intern_id' => $internId,
                            'date' => $attendanceDate,
                        ],
                        [
                            'status' => $data['status'],
                            'location' => $data['location'] ?? 'Admin Marked',
                            'in_time' => null,
                            'out_time' => null,
                        ]
                    );
                    continue;
                }

                // 🟢 PRESENT / HALF DAY (ADMIN CAN SWITCH WITHOUT TIME)
                Attendance::updateOrCreate(
                    [
                        'intern_id' => $internId,
                        'date' => $attendanceDate,
                    ],
                    [
                        'status' => $data['status'],

                        'location' => $data['location']
                            ?? ($existingAttendance->location ?? 'Admin Marked'),

                        'in_time' => $data['in_time']
                            ?? ($existingAttendance->in_time ?? null),

                        'out_time' => $data['out_time']
                            ?? ($existingAttendance->out_time ?? null),
                    ]
                );
            }
        });

        return redirect()
            ->route('attendance.index', [
                'filter_date' => $attendanceDate,
                'role' => $request->role
            ])
            ->with('success', 'Attendance updated for ' . $attendanceDate);
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
     * PUBLIC – Search page
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
        $request->validate([
            'employee_id' => 'required',
        ]);

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
     * PUBLIC – Attendance form
     */
    public function publicFormByToken($date, $token)
    {
        $intern = Intern::where('intern_code', $token)->firstOrFail();
        $date = Carbon::parse($date)->format('Y-m-d');

        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $date)
            ->first();

        return view('attendance.public', compact(
            'intern',
            'date',
            'todayAttendance'
        ));
    }

    /**
     * PUBLIC – Punch IN / Punch OUT
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

        $attendance = Attendance::where('intern_id', $request->intern_id)
            ->whereDate('date', $attendanceDate)
            ->first();

        // 🟢 PUNCH IN
        if (!$attendance) {
            Attendance::create([
                'intern_id' => $request->intern_id,
                'date' => $attendanceDate,
                'status' => 'present',
                'location' => $request->location,
                'in_time' => $currentTime,
                'out_time' => null,
            ]);

            return redirect()->route('empcode')
                ->with('success', 'Punch IN recorded at ' . $currentTime);
        }

        // 🔵 PUNCH OUT
        if ($attendance->in_time && !$attendance->out_time) {
            $attendance->update([
                'out_time' => $currentTime,
            ]);

            return redirect()->route('empcode')
                ->with('success', 'Punch OUT recorded at ' . $currentTime);
        }

        return redirect()->route('empcode')
            ->with('error', 'Attendance already completed for today.');
    }
}
