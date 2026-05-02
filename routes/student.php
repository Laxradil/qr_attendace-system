<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/attendance', [StudentController::class, 'attendance'])->name('student.attendance');
    Route::get('/student/classes', [StudentController::class, 'myClasses'])->name('student.classes');
});
