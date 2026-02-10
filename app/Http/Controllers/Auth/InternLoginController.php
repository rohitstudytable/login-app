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
     * Show the intern login form.
     *
     * If the intern is already logged in, redirect to their attendance page.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $intern = Auth::user();

            // Optional: Only redirect if it's an intern (role check)
            if ($intern->role === 'intern') {
                $date = Carbon::now()->format('Y-m-d');
                return redirect()->route('attendance.publicFormByToken', [
                    'date' => $date,
                    'token' => $intern->intern_code, 
                ]);
            }
        }

        return view('auth.intern-login'); // Blade view for login
    }

    /**
     * Handle intern login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'intern_code' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find intern by code
        $intern = Intern::where('intern_code', $request->intern_code)->first();

        if (!$intern) {
            return back()->withInput()->with('error', 'Invalid Employee/Intern code.');
        }

        // Verify password
        if (!Hash::check($request->password, $intern->password)) {
            return back()->withInput()->with('error', 'Invalid password.');
        }

        // Login the intern
        Auth::login($intern);

        // Redirect to public attendance page
        $date = Carbon::now()->format('Y-m-d');
        return redirect()->route('attendance.publicFormByToken', [
            'date' => $date,
            'token' => $intern->intern_code,
        ])->with('success', 'Logged in successfully.');
    }

    /**
     * Handle intern logout.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to Employee/Intern code entry page
        return redirect()->route('empcode')->with('success', 'Logged out successfully.');
    }
}
