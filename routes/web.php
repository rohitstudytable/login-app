<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AllholiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\InternLoginController;
use App\Http\Controllers\Intern\ProfileController as InternProfileController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
| EMPLOYEE / INTERN DASHBOARD (auth:intern)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:intern')->group(function () {

    Route::get('/empdashboard', [AttendanceController::class, 'empDashboard'])
        ->name('empdashboard');

    Route::get('/empattendance', [AttendanceController::class, 'empAttendance'])
        ->name('empattendance');

    Route::post('/empattendance/store', [AttendanceController::class, 'publicStoreByToken'])
        ->name('attendance.publicStoreByToken');

    Route::get('/empreport', [AttendanceController::class, 'empReport'])
        ->name('empreport');

    Route::get('/empprofile', function () {
        return view('attendance.empProfile', [
            'intern' => Auth::guard('intern')->user()
        ]);
    })->name('empprofile');
 Route::get('/storage-link', function () {
        Artisan::call('storage:link');
        return 'Storage link created successfully!';
    });
    Route::post('/intern/profile/image', [InternProfileController::class, 'updateImage'])
        ->name('intern.profile.image');

    Route::post('/intern/profile/personal', [InternProfileController::class, 'updatePersonal'])
        ->name('intern.profile.personal');

    Route::post('/intern/profile/contact', [InternProfileController::class, 'updateContact'])
        ->name('intern.profile.contact');
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (BEFORE LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/enter-empcode', [AttendanceController::class, 'searchEmpCode'])
    ->name('empcode');

Route::post('/search-empcode', [AttendanceController::class, 'searchByEmployeeId'])
    ->name('submit.empcode');

Route::get('/attendance/search', [AttendanceController::class, 'searchEmpCode'])
    ->name('attendance.search');

/*
|--------------------------------------------------------------------------
| INTERN AUTH ROUTES
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
| ADMIN ROUTES (auth:web ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::resource('interns', InternController::class);

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/attendance', [AttendanceController::class, 'store'])
        ->name('attendance.store');

    Route::get('/attendance/intern/{intern}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    Route::get('/report', [ReportController::class, 'index'])
        ->name('report');

    Route::get('/report/intern/{intern}', [ReportController::class, 'show'])
        ->name('report.intern');

    Route::get('/interns/{id}', [InternController::class, 'show'])
        ->name('interns.show');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('holidays', \App\Http\Controllers\Admin\HolidayController::class);
    });

    Route::post('/admin/logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| INTERN HOLIDAYS – ADMIN ONLY (FIXED & ISOLATED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {

    Route::get('/interns/holidayss', [AllholiController::class, 'index'])
        ->name('interns.holidays.page');

    Route::post('/interns/holidayss', [AllholiController::class, 'store'])
        ->name('interns.holidays.store');
});

/*
|--------------------------------------------------------------------------
| DEFAULT AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';