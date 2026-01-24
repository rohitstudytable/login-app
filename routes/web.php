<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Intern Management
    Route::resource('interns', InternController::class);

    // Admin Attendance (internal)
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])
        ->name('attendance.store');

    // View attendance history of a specific intern (admin)
    Route::get('/attendance/intern/{intern}', [AttendanceController::class, 'show'])
        ->name('attendance.show');
});

/*
|--------------------------------------------------------------------------
| Public Attendance URLs for interns
|--------------------------------------------------------------------------
*/
// Public attendance form by token
Route::get('/attendance/public/{token}', [AttendanceController::class, 'publicFormByToken'])
    ->name('attendance.publicFormByToken');

// Store attendance from public form
Route::post('/attendance/public/{token}', [AttendanceController::class, 'publicStoreByToken'])
    ->name('attendance.publicStoreByToken');
/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
