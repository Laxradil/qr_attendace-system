<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRecord;
use App\Models\QRCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;

class StudentController extends Controller
{
    /**
     * Return students enrolled in a class (AJAX)
     */
    public function getClassStudents(int $id)
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
        $classes = $user->enrolledClasses()->with('professors')->get();

        // Get recent attendance records
        $recentAttendance = AttendanceRecord::where('student_id', $user->id)
            ->with(['classe'])
            ->orderBy('recorded_at', 'desc')
            ->limit(10)
            ->get();

        // Get all attendance statistics in a single query
        $stats = AttendanceRecord::where('student_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused
            ")
            ->first();

        return view('student.dashboard', [
            'classes' => $classes,
            'recentAttendance' => $recentAttendance,
            'totalPresent' => $stats->present ?? 0,
            'totalLate' => $stats->late ?? 0,
            'totalAbsent' => $stats->absent ?? 0,
            'totalExcused' => $stats->excused ?? 0,
            'totalRecords' => $stats->total ?? 0,
        ]);
    }

    public function attendance(): View
    {
        $user = Auth::user();

        $attendanceRecords = AttendanceRecord::where('student_id', $user->id)
            ->with(['classe'])
            ->orderBy('recorded_at', 'desc')
            ->paginate(20);

        // Get all attendance statistics in a single query
        $stats = AttendanceRecord::where('student_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused
            ")
            ->first();

        return view('student.attendance', [
            'attendanceRecords' => $attendanceRecords,
            'totalPresent' => $stats->present ?? 0,
            'totalLate' => $stats->late ?? 0,
            'totalAbsent' => $stats->absent ?? 0,
            'totalExcused' => $stats->excused ?? 0,
            'totalRecords' => $stats->total ?? 0,
        ]);
    }

    public function myClasses(): View
    {
        $user = Auth::user();
        $classes = $user->enrolledClasses()->with('professors')->get();

        // Get all attendance statistics
        $stats = AttendanceRecord::where('student_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused
            ")
            ->first();

        return view('student.classes', [
            'classes' => $classes,
            'totalPresent' => $stats->present ?? 0,
            'totalLate' => $stats->late ?? 0,
            'totalAbsent' => $stats->absent ?? 0,
            'totalExcused' => $stats->excused ?? 0,
        ]);
    }

    private function createStudentQrSvg($user): string
    {
        $qrCode = QRCode::where('student_id', $user->id)->first();

        $qrData = json_encode([
            'type' => 'student_attendance',
            'student_id' => $user->id,
            'student_name' => $user->name,
            'student_email' => $user->email,
            'uuid' => $qrCode?->uuid ?? \Illuminate\Support\Str::uuid()->toString(),
            'generated_at' => now()->toIso8601String(),
        ]);

        return QrCodeFacade::format('svg')
            ->size(280)
            ->margin(1)
            ->encoding('UTF-8')
            ->generate($qrData);
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

        $qrCode = QRCode::where('student_id', $user->id)->first();

        $qrData = json_encode([
            'type' => 'student_attendance',
            'student_id' => $user->id,
            'student_name' => $user->name,
            'student_email' => $user->email,
            'uuid' => $qrCode?->uuid ?? \Illuminate\Support\Str::uuid()->toString(),
            'generated_at' => now()->toIso8601String(),
        ]);

        $svg = QrCodeFacade::format('svg')
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

