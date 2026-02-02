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

        $interns = Intern::orderBy('name')->get();

        // Records for selected date
        $recordsForDate = Attendance::whereDate('date', $attendanceDate)->get();

        // Attendance history
        $allRecordsQuery = Attendance::with('intern')->orderBy('date', 'desc');

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
     * SAVE / UPDATE / UNMARK attendance (ADMIN)
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'interns' => 'required|array',
            'interns.*.status' => 'required|in:present,absent,half_day,unmark',
        ]);

        $attendanceDate = Carbon::parse($request->date)->format('Y-m-d');

        DB::transaction(function () use ($request, $attendanceDate) {
            foreach ($request->interns as $internId => $data) {

                if ($data['status'] === 'unmark') {
                    Attendance::where('intern_id', $internId)
                        ->whereDate('date', $attendanceDate)
                        ->delete();
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'intern_id' => $internId,
                        'date'      => $attendanceDate,
                    ],
                    [
                        'status'    => $data['status'],
                    ]
                );
            }
        });

        return redirect()
            ->route('attendance.index', ['filter_date' => $attendanceDate])
            ->with('success', 'Attendance updated for ' . $attendanceDate);
    }

    /**
     * SINGLE INTERN – Attendance history (ADMIN)
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
            'intern'        => $intern,
            'attendances'   => $attendances,
            'totalDays'     => $attendances->count(),
            'presentCount'  => $attendances->where('status', 'present')->count(),
            'absentCount'   => $attendances->where('status', 'absent')->count(),
            'halfDayCount'  => $attendances->where('status', 'half_day')->count(),
        ]);
    }

    /**
     * PUBLIC: Search Intern Code page
     */
    public function searchEmpCode()
    {
        return view('attendance.empcode');
    }

    /**
     * PUBLIC: Submit Intern Code
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
            'date'  => now()->format('Y-m-d'),
            'token' => $intern->intern_code,
        ]);
    }

    /**
     * PUBLIC: Attendance form
     * NOTE: uses intern_code as token
     */
    public function publicFormByToken($date, $token)
    {
        $intern = Intern::where('intern_code', $token)->firstOrFail();
        $date = Carbon::parse($date)->format('Y-m-d');

        // ✅ CORRECT VIEW NAME
        return view('attendance.public', compact('intern', 'date'));
    }

    /**
     * PUBLIC: Store attendance
     */
    public function publicStoreByToken(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'status'    => 'required|in:present,absent,half_day',
        ]);

        $attendanceDate = $request->date
            ? Carbon::parse($request->date)->format('Y-m-d')
            : now()->format('Y-m-d');

        Attendance::updateOrCreate(
            [
                'intern_id' => $request->intern_id,
                'date'      => $attendanceDate,
            ],
            [
                'status'    => $request->status,
            ]
        );

       return redirect()
            ->route('empcode')
            ->with('success', 'Attendance submitted successfully');
    }
}
