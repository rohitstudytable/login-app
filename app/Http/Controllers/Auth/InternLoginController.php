<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InternLoginController extends Controller
{
    /**
     * Show intern login form
     */
    public function showLoginForm()
    {
        // ✅ If already logged in → go to emp dashboard
        if (Auth::guard('intern')->check()) {
            return redirect()->route('empdashboard');
        }

        // ✅ Otherwise show login page
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

        // 🔍 Find intern
        $intern = Intern::where('intern_code', $request->intern_code)->first();

        if (!$intern || !Hash::check($request->password, $intern->password)) {
            return back()
                ->withInput()
                ->with('error', 'Invalid Employee / Intern Code or Password');
        }

        // 🔒 Secure session
        $request->session()->regenerate();

        // ✅ Login via INTERN guard
        Auth::guard('intern')->login($intern);

        // 🚀 Redirect to EMP DASHBOARD
        return redirect()
            ->route('empdashboard')
            ->with('success', 'Logged in successfully');
    }

    /**
     * Logout intern
     */
    public function logout(Request $request)
    {
        Auth::guard('intern')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('empcode')
            ->with('success', 'Logged out successfully');
    }
}
