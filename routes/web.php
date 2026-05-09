<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ScheduleController;

// Student: QR code for attendance (must be before class route to avoid conflict)
Route::get('/student/qr-code', [App\Http\Controllers\StudentController::class, 'generateStudentQR'])
    ->name('student.qr-code')
    ->middleware(['auth', 'role:student']);

// Student: Get students in a class (AJAX)
Route::get('/student/class/{id}/students', [App\Http\Controllers\StudentController::class, 'getClassStudents'])->name('student.class.students');

// Professor: Get students in a class (AJAX)
Route::get('/professor/class/{id}/students', [App\Http\Controllers\ProfessorController::class, 'getClassStudents'])->name('professor.class.students');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.send-reset-link');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset');

// Dashboard route
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isProfessor()) {
            return redirect()->route('professor.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});

// Professor routes
Route::prefix('professor')->middleware(['auth', 'role:professor'])->group(function () {
    Route::get('/', [ProfessorController::class, 'dashboard'])->name('professor.dashboard');
    Route::get('/classes', [ProfessorController::class, 'myClasses'])->name('professor.classes');
    Route::get('/classes/{classe}', [ProfessorController::class, 'showClass'])->name('professor.class-detail');
    Route::put('/classes/{classe}', [ProfessorController::class, 'updateClass'])->name('professor.classes.update');
    Route::get('/scan-qr', [ProfessorController::class, 'scanQR'])->name('professor.scan-qr');
    Route::post('/attendance', [ProfessorController::class, 'recordAttendance'])->name('professor.attendance.store');
    Route::get('/attendance-records', [ProfessorController::class, 'attendanceRecords'])->name('professor.attendance-records');
    Route::get('/attendance-records/{attendanceRecord}/edit', [ProfessorController::class, 'editAttendanceRecord'])->name('professor.attendance-records.edit');
    Route::put('/attendance-records/{attendanceRecord}', [ProfessorController::class, 'updateAttendanceRecord'])->name('professor.attendance-records.update');
    Route::get('/schedules', [ProfessorController::class, 'schedules'])->name('professor.schedules');
    Route::get('/reports', [ProfessorController::class, 'reports'])->name('professor.reports');
    Route::get('/students', [ProfessorController::class, 'students'])->name('professor.students');
    Route::get('/logs', [ProfessorController::class, 'logs'])->name('professor.logs');
    Route::get('/settings', [ProfessorController::class, 'settings'])->name('professor.settings');
    Route::put('/settings', [ProfessorController::class, 'updateSettings'])->name('professor.settings.update');
    Route::put('/settings/password', [ProfessorController::class, 'updatePassword'])->name('professor.settings.password');
    Route::post('/add-student', [ProfessorController::class, 'addStudent'])->name('professor.add-student');
    Route::post('/drop-request', [ProfessorController::class, 'submitDropRequest'])->name('professor.drop-request');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Professors
    Route::get('/professors', [AdminController::class, 'professors'])->name('admin.professors');
    
    // Students
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/students/{student}/qr-code', [AdminController::class, 'studentQrCode'])->name('admin.students.qr-code');
    
    // Classes management
    Route::get('/classes', [AdminController::class, 'classes'])->name('admin.classes');
    Route::get('/classes/create', [AdminController::class, 'createClass'])->name('admin.classes.create');
    Route::post('/classes', [AdminController::class, 'storeClass'])->name('admin.classes.store');
    Route::get('/classes/{classe}/edit', [AdminController::class, 'editClass'])->name('admin.classes.edit');
    Route::get('/classes/{classe}/enroll', [AdminController::class, 'enrollClassForm'])->name('admin.classes.enroll');
    Route::post('/classes/{classe}/enroll', [AdminController::class, 'storeClassEnrollment'])->name('admin.classes.enroll.store');
    Route::put('/classes/{classe}', [AdminController::class, 'updateClass'])->name('admin.classes.update');
    Route::delete('/classes/{classe}', [AdminController::class, 'deleteClass'])->name('admin.classes.delete');
    
    // Student QR management
    Route::get('/qr-codes', [AdminController::class, 'qrCodes'])->name('admin.qr-codes');
    
    // Attendance records
    Route::get('/attendance-records', [AdminController::class, 'attendanceRecords'])->name('admin.attendance-records');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Drop request approvals
    Route::get('/drop-requests', [AdminController::class, 'dropRequests'])->name('admin.drop-requests');
    Route::post('/drop-requests/{dropRequest}/approve', [AdminController::class, 'approveDropRequest'])->name('admin.drop-requests.approve');
    Route::post('/drop-requests/{dropRequest}/reject', [AdminController::class, 'rejectDropRequest'])->name('admin.drop-requests.reject');
    
    // System logs
    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');

<<<<<<< HEAD
    // Admin settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::put('/settings/password', [AdminController::class, 'updatePassword'])->name('admin.settings.password');

=======
>>>>>>> branch_shon
    // Debug: return current user stats (admin-only)
    Route::get('/_debug/user-stats', [AdminController::class, 'debugUserStats'])->name('admin.debug.user-stats');
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

// Student routes
Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/attendance', [App\Http\Controllers\StudentController::class, 'attendance'])->name('student.attendance');
    Route::get('/classes', [App\Http\Controllers\StudentController::class, 'myClasses'])->name('student.classes');
    Route::get('/settings', [App\Http\Controllers\StudentController::class, 'settings'])->name('student.settings');
    Route::put('/settings', [App\Http\Controllers\StudentController::class, 'updateSettings'])->name('student.settings.update');
    Route::put('/settings/password', [App\Http\Controllers\StudentController::class, 'updatePassword'])->name('student.settings.password');
});

// DEBUG: Test student UI without auth (remove in production)
if (env('APP_DEBUG', false)) {
    Route::get('/_debug/student-dashboard', function() {
        $user = \App\Models\User::where('email', 'student@example.com')->firstOrFail();
        Auth::login($user);
        return redirect()->route('student.dashboard');
    });
}
