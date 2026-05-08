<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/attendance', [StudentController::class, 'attendance'])->name('student.attendance');
    Route::get('/student/classes', [StudentController::class, 'myClasses'])->name('student.classes');

    // Settings page
    Route::get('/student/settings', [StudentController::class, 'settings'])->name('student.settings');
    Route::put('/student/settings', [StudentController::class, 'updateSettings'])->name('student.settings.update');
    Route::put('/student/settings/password', [StudentController::class, 'updatePassword'])->name('student.settings.password');
});
