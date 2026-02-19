<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\InternLoginController;
use App\Http\Controllers\Intern\ProfileController as InternProfileController;


/*
|--------------------------------------------------------------------------
| ROOT ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::guard('intern')->check()) {
        return redirect()->route('empdashboard');
    }

    return redirect()->route('empcode');
});


/*
|--------------------------------------------------------------------------
| EMPLOYEE / INTERN DASHBOARD PAGES (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:intern')->group(function () {
    
    // EMP DASHBOARD
    Route::get('/empdashboard', [AttendanceController::class, 'empDashboard'])
        ->name('empdashboard');

    // EMP ATTENDANCE
    Route::get('/empattendance', [AttendanceController::class, 'empAttendance'])
        ->name('empattendance');

    // Punch IN / OUT
    Route::post('/empattendance/store', [AttendanceController::class, 'publicStoreByToken'])
        ->name('attendance.publicStoreByToken');

    /*
    |--------------------------------------------------------------------------
    | EMP REPORT  ✅ FIXED (NOW CONNECTED TO CONTROLLER)
    |--------------------------------------------------------------------------
    */
    Route::get('/empreport', [AttendanceController::class, 'empReport'])
        ->name('empreport');

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
| PUBLIC ATTENDANCE SEARCH
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


Route::middleware(['auth:intern'])->group(function () {

    // 🔹 Update profile image
    Route::post(
        '/intern/profile/image',
        [InternProfileController::class, 'updateImage']
    )->name('intern.profile.image');

    // 🔹 Update personal information
    Route::post(
        '/intern/profile/personal',
        [InternProfileController::class, 'updatePersonal']
    )->name('intern.profile.personal');

    // 🔹 Update contact & address
    Route::post(
        '/intern/profile/contact',
        [InternProfileController::class, 'updateContact']
    )->name('intern.profile.contact');

});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (WEB GUARD ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('interns', InternController::class);

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/attendance/intern/{intern}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/intern/{intern}', [ReportController::class, 'show'])
        ->name('report.intern');

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('holidays', \App\Http\Controllers\Admin\HolidayController::class);
        });

            Route::post('/admin/logout', function () {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login'); // auth/login.blade.php
        })->name('admin.logout');

});


/*
|--------------------------------------------------------------------------
| DEFAULT ADMIN AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
