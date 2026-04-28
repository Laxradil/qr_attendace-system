<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/accounts', [AdminController::class, 'accounts'])->name('admin.accounts');
    Route::get('/schedules', [AdminController::class, 'schedules'])->name('admin.schedules');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::post('/account/create', [AdminController::class, 'createAccount'])->name('admin.account.create');
    Route::post('/schedule/create', [AdminController::class, 'createSchedule'])->name('admin.schedule.create');
    
    // User management routes
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password');
});

// Schedule routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
});

// Home route
Route::get('/', function () {
    return redirect('/login');
});
