<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InternLoginController extends Controller
{
    /**
     * Show intern login form
     */
    public function showLoginForm()
    {
        // If already logged in as intern
        if (Auth::guard('intern')->check()) {

            $intern = Auth::guard('intern')->user();

            return redirect()->route('attendance.publicFormByToken', [
                'date'  => Carbon::now()->format('Y-m-d'),
                'token' => $intern->intern_code,
            ]);
        }

        return view('auth.intern-login');
    }


    /**
     * Handle intern login
     */
    public function login(Request $request)
    {
        $request->validate([
            'intern_code' => 'required|string',
            'password'    => 'required|string',
        ]);

        // Find intern
        $intern = Intern::where('intern_code', $request->intern_code)->first();

        if (!$intern) {
            return back()->withInput()->with('error', 'Invalid Employee/Intern code.');
        }

        // Check password
        if (!Hash::check($request->password, $intern->password)) {
            return back()->withInput()->with('error', 'Invalid password.');
        }

        // LOGIN using INTERN GUARD (IMPORTANT)
        Auth::guard('intern')->login($intern);

        return redirect()->route('attendance.publicFormByToken', [
            'date'  => Carbon::now()->format('Y-m-d'),
            'token' => $intern->intern_code,
        ])->with('success', 'Logged in successfully.');
    }


    /**
     * Logout intern
     */
    public function logout(Request $request)
    {
        Auth::guard('intern')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('empcode')->with('success', 'Logged out successfully.');
    }
}
