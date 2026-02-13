<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\InternLoginController;

/*
|--------------------------------------------------------------------------
| ROOT ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // ✅ INTERN logged in → EMP DASHBOARD
    if (Auth::guard('intern')->check()) {
        return redirect()->route('empdashboard');
    }

    // ❌ Not logged in → EMP CODE PAGE
    return redirect()->route('empcode');
});

/*
|--------------------------------------------------------------------------
| EMPLOYEE / INTERN DASHBOARD PAGES (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:intern')->group(function () {
    
    // EMP DASHBOARD – provides stats
    Route::get('/empdashboard', [AttendanceController::class, 'empDashboard'])
        ->name('empdashboard');

    // EMP ATTENDANCE – shows attendance page and allows punch in/out
    Route::get('/empattendance', [AttendanceController::class, 'empAttendance'])
        ->name('empattendance');

    // Punch IN / OUT action (AJAX)
    Route::post('/empattendance/store', [AttendanceController::class, 'publicStoreByToken'])
        ->name('attendance.publicStoreByToken');

    // EMP REPORT
    Route::get('/empreport', function () {
        return view('attendance.empReport', [
            'intern' => Auth::guard('intern')->user()
        ]);
    })->name('empreport');

    // EMP PROFILE
    Route::get('/empprofile', function () {
        return view('attendance.empProfile', [
            'intern' => Auth::guard('intern')->user()
        ]);
    })->name('empprofile');
});

/*
|--------------------------------------------------------------------------
| EMP CODE (PUBLIC – BEFORE LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/enter-empcode', [AttendanceController::class, 'searchEmpCode'])
    ->name('empcode');

Route::post('/search-empcode', [AttendanceController::class, 'searchByEmployeeId'])
    ->name('submit.empcode');

/*
|--------------------------------------------------------------------------
| PUBLIC ATTENDANCE SEARCH (NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::get('/attendance/search', [AttendanceController::class, 'searchEmpCode'])
    ->name('attendance.search');

/*
|--------------------------------------------------------------------------
| INTERN LOGIN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login-intern', [InternLoginController::class, 'showLoginForm'])
    ->name('intern.login');

Route::post('/login-intern', [InternLoginController::class, 'login'])
    ->name('intern.login.submit');

Route::post('/logout-intern', [InternLoginController::class, 'logout'])
    ->name('intern.logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (WEB GUARD ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->group(function () {

    // ADMIN DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // INTERN MANAGEMENT
    Route::resource('interns', InternController::class);

    // ATTENDANCE (ADMIN)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/attendance/intern/{intern}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    // REPORTS
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/intern/{intern}', [ReportController::class, 'show'])
        ->name('report.intern');

    // HOLIDAYS
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('holidays', \App\Http\Controllers\Admin\HolidayController::class);
        });
});

/*
|--------------------------------------------------------------------------
| DEFAULT ADMIN AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
