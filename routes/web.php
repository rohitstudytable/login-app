<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\InternLoginController;

/*
|--------------------------------------------------------------------------
| Root Route
|--------------------------------------------------------------------------
|
| Redirect logged-in interns to their public attendance page.
| Otherwise, redirect to Employee/Intern code entry page.
|
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect only if the user is an intern
        if ($user->role === 'intern') {
            $date = Carbon::now()->format('Y-m-d');
            return redirect()->route('attendance.publicFormByToken', [
                'date' => $date,
                'token' => $user->intern_code,
            ]);
        }
    }

    return redirect()->route('empcode');
});

/*
|--------------------------------------------------------------------------
| Public Attendance Routes (Interns)
|--------------------------------------------------------------------------
*/
Route::get('/enter-empcode', [AttendanceController::class, 'searchEMpCode'])
    ->name('empcode');

Route::post('/search-empcode', [AttendanceController::class, 'searchByEmployeeId'])
    ->name('submit.empcode');

// Public attendance form by token
Route::get('/attendance/public/{date}/{token}', [AttendanceController::class, 'publicFormByToken'])
    ->name('attendance.publicFormByToken');

Route::post('/attendance/public', [AttendanceController::class, 'publicStoreByToken'])
    ->name('attendance.publicStoreByToken');

/*
|--------------------------------------------------------------------------
| Intern / Employee Login Routes
|--------------------------------------------------------------------------
*/
Route::get('/login-intern', [InternLoginController::class, 'showLoginForm'])
    ->name('intern.login');

Route::post('/login-intern', [InternLoginController::class, 'login'])
    ->name('intern.login.submit');

Route::post('/logout-intern', [InternLoginController::class, 'logout'])
    ->name('intern.logout'); // logout route for interns

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Intern Management
    Route::resource('interns', InternController::class);

    // Admin Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // Attendance history of a specific intern (admin view)
    Route::get('/attendance/intern/{intern}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    // Reports
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/intern/{intern}', [ReportController::class, 'show'])->name('report.intern');
});

/*
|--------------------------------------------------------------------------
| Laravel Default Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';




Route::get('/', function () {
    return view('attendance.empDashboard');
});
