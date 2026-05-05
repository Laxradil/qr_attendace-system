<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRecord;
use App\Models\Classe;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    /**
     * Return students enrolled in a class (AJAX)
     */
    public function getClassStudents($id)
    {
        $classe = \App\Models\Classe::with('students')->findOrFail($id);
        $students = $classe->students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
            ];
        });
        return response()->json(['students' => $students]);
    }
    public function dashboard(): View
    {
        $user = Auth::user();
        $classes = $user->enrolledClasses()->with('professor')->get();

        // Get recent attendance records
        $recentAttendance = AttendanceRecord::where('student_id', $user->id)
            ->with(['classe'])
            ->orderBy('recorded_at', 'desc')
            ->limit(10)
            ->get();

        // Get attendance statistics
        $totalPresent = AttendanceRecord::where('student_id', $user->id)
            ->where('status', 'present')
            ->count();
        $totalLate = AttendanceRecord::where('student_id', $user->id)
            ->where('status', 'late')
            ->count();
        $totalAbsent = AttendanceRecord::where('student_id', $user->id)
            ->where('status', 'absent')
            ->count();

        return view('student.dashboard', [
            'classes' => $classes,
            'recentAttendance' => $recentAttendance,
            'totalPresent' => $totalPresent,
            'totalLate' => $totalLate,
            'totalAbsent' => $totalAbsent,
        ]);
    }

    public function attendance(): View
    {
        $user = Auth::user();
        
        $attendanceRecords = AttendanceRecord::where('student_id', $user->id)
            ->with(['classe', 'qrCode'])
            ->orderBy('recorded_at', 'desc')
            ->paginate(20);

        $totalPresent = AttendanceRecord::where('student_id', $user->id)
            ->where('status', 'present')
            ->count();
        $totalLate = AttendanceRecord::where('student_id', $user->id)
            ->where('status', 'late')
            ->count();
        $totalAbsent = AttendanceRecord::where('student_id', $user->id)
            ->where('status', 'absent')
            ->count();
        $totalRecords = AttendanceRecord::where('student_id', $user->id)->count();

        return view('student.attendance', [
            'attendanceRecords' => $attendanceRecords,
            'totalPresent' => $totalPresent,
            'totalLate' => $totalLate,
            'totalAbsent' => $totalAbsent,
            'totalRecords' => $totalRecords,
        ]);
    }

    public function myClasses(): View
    {
        $user = Auth::user();
        $classes = $user->enrolledClasses()->with('professor')->get();

        return view('student.classes', [
            'classes' => $classes,
        ]);
    }

    public function generateStudentQR()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="white"/><text x="100" y="100" text-anchor="middle">Not authenticated</text></svg>', 401, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]);
        }

        $qrData = json_encode([
            'type' => 'student_attendance',
            'student_id' => $user->id,
            'student_name' => $user->name,
            'student_email' => $user->email,
            'generated_at' => now()->toIso8601String(),
        ]);

        $svg = QrCode::format('svg')
            ->size(280)
            ->margin(1)
            ->encoding('UTF-8')
            ->generate($qrData);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}

