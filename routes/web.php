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
*/
Route::get('/', function () {

    if (Auth::guard('intern')->check()) {
        $intern = Auth::guard('intern')->user();

        return redirect()->route('attendance.publicFormByToken', [
            'date' => Carbon::now()->format('Y-m-d'),
            'token' => $intern->intern_code,
        ]);
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

Route::get('/attendance/public/{date}/{token}', [AttendanceController::class, 'publicFormByToken'])
    ->name('attendance.publicFormByToken');

Route::post('/attendance/public', [AttendanceController::class, 'publicStoreByToken'])
    ->name('attendance.publicStoreByToken');

/*
|--------------------------------------------------------------------------
| Intern Login Routes
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
| ADMIN ROUTES (web guard ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ INTERN MANAGEMENT (ADMIN ONLY)
    Route::resource('interns', InternController::class);

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/attendance/intern/{intern}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/intern/{intern}', [ReportController::class, 'show'])
        ->name('report.intern');
});

/*
|--------------------------------------------------------------------------
| Laravel Default Auth (Admin Login)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';




Route::get('/', function () {
    return view('attendance.empDashboard');
});
Route::get('/empattendance', function () {
    return view('attendance.empAttendance');
});
Route::get('/empreport', function () {
    return view('attendance.empReport');
});
Route::get('/empprofile', function () {
    return view('attendance.empProfile');
});