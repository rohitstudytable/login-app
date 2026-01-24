<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Intern;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Show the attendance page for admin with interns and attendance records
     */
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::today()->toDateString();

        $interns = Intern::orderBy('name')->get();

        $query = Attendance::with('intern');

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $records = $query->orderBy('date', 'desc')->get();

        return view('attendance.index', compact('interns', 'date', 'records'));
    }

    /**
     * Store or update attendance (admin side)
     */
    public function store(Request $request)
    {

        $request->validate([
            'date'       => 'required|string',
            'intern_id'  => 'required|exists:interns,id',
            'status'     => 'required|in:present,absent,half_day',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'photo_data' => 'nullable|string',
        ]);

        $photoPath = $this->handlePhoto($request);

        $attendance = Attendance::updateOrCreate(
            [
                'intern_id' => $request->intern_id,
                'date'      => $request->date,
            ],
            [
                'status' => $request->status,
                'photo'  => $photoPath,
            ]
        );
        if ($attendance->wasRecentlyCreated) {
            return back()->with('success', 'Attendance created successfully');
        } else {
            return back()->with('success', 'Attendance updated successfully');
        }



        // return back()->with('success', 'Attendance saved successfully');
    }



    /**
     * Store public attendance submitted by intern via token
     */
    public function searchEMpCode()
    {
        return view('attendance.empcode');
    }
    public function searchByEmployeeId(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
        ]);

        $intern = Intern::where('Intern_code', $request->employee_id)->first();

        if (!$intern) {
            return back()->with('error', 'Employee ID not found');
        }
        $encryptedDate  = dataEncrypt(now()->toDateString());
        $encryptedToken = dataEncrypt($intern->random_id);
        $encryptedName  = dataEncrypt($intern->name);



        // Show attendance form with token
        return redirect()->route('attendance.publicFormByToken', [
            'date'  => $encryptedDate,
            'token' => $encryptedToken,
            'name'  => $encryptedName,
        ]);
    }
    /**
     * Show public attendance form via token
     */
    public function publicFormByToken($date, $token)
    {
        $decryptToken = dataDecrypt($token);
        $encryptdate = $date;
        $encryptToken = $token;
        $intern = Intern::where('random_id', $decryptToken)->firstOrFail();
        return view('attendance.public', compact('intern', 'encryptdate', 'encryptToken'));
    }

    public function publicStoreByToken(Request $request)
    {
        $decryptToken = dataDecrypt($request->random_code);
        $decryptid = dataDecrypt($request->intern);

        $decryptDate = dataDecrypt($request->date);

        if ($decryptDate != Carbon::today()->toDateString()) {
            return back()->with('error', 'Attendance cannot be submitted for today\'s date.');
        }

        $intern = Intern::where('random_id', $decryptToken)->where('id', $decryptid)->first();
        if (!$intern) {
            return back()->with('error', 'User not found contact Admin');
        }

        $request->validate([
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'photo_data' => 'nullable|string',
        ]);

        $photoPath = $this->handlePhoto($request);

        Attendance::updateOrCreate(
            [
                'intern_id' => $intern->id,
                'date'      => $decryptDate,
            ],
            [
                'status' => 'present',
                'photo'  => $photoPath,
            ]
        );

        return redirect()->route('empcode')->with('success', 'Attendance submitted successfully!');
    }

    /**
     * Show attendance history for a specific intern (admin)
     */
    public function show(Intern $intern)
    {
        $attendances = $intern->attendances()
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.show', compact('intern', 'attendances'));
    }

    /**
     * Helper function to handle mobile upload or webcam Base64 photo
     */
    protected function handlePhoto(Request $request)
    {
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance_photos', 'public');
        } elseif ($request->filled('photo_data')) {
            $data = $request->photo_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
            } else {
                $type = 'png';
            }

            $data = base64_decode($data);
            if ($data === false) {
                return back()->withErrors(['photo_data' => 'Invalid photo data']);
            }

            $fileName = 'attendance_photos/' . Str::uuid() . '.' . $type;
            Storage::disk('public')->put($fileName, $data);
            $photoPath = $fileName;
        }

        return $photoPath;
    }
}
