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

                // UNMARK → delete record
                if ($data['status'] === 'unmark') {
                    Attendance::where('intern_id', $internId)
                        ->whereDate('date', $attendanceDate)
                        ->delete();
                    continue;
                }

                // UPDATE OR CREATE (SAFE – prevents duplicates)
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
     * ✅ UPDATED: Start Date → End Date filter
     */
    public function show(Request $request, $id)
    {
        $intern = Intern::findOrFail($id);

        $query = Attendance::where('intern_id', $id);

        // START DATE
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // END DATE
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // STATUS
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
     * PUBLIC: Search by Employee Code
     */
    public function searchEMpCode()
    {
        return view('attendance.search-empcode');
    }

    /**
     * PUBLIC: Search by Employee ID
     */
    public function searchByEmployeeId(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
        ]);

        $intern = Intern::where('employee_id', $request->employee_id)->first();

        if (!$intern) {
            return back()->with('error', 'Employee not found');
        }

        return redirect()->route('attendance.publicFormByToken', [
            'date'  => now()->format('Y-m-d'),
            'token' => $intern->token,
        ]);
    }

    /**
     * PUBLIC: Attendance form by token
     */
    public function publicFormByToken($date, $token)
    {
        $intern = Intern::where('token', $token)->firstOrFail();
        $date = Carbon::parse($date)->format('Y-m-d');

        return view('attendance.public-form', compact('intern', 'date'));
    }

    /**
     * PUBLIC: Store attendance by token
     */
    public function publicStoreByToken(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'status' => 'required|in:present,absent,half_day',
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

        return back()->with('success', 'Attendance saved for ' . $attendanceDate);
    }
}
