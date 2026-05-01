<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\QRCode;
use App\Models\AttendanceRecord;
use App\Models\Classe;

class StudentController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        // Get all classes the student is enrolled in with active QR codes
        $classes = $user->classes()->with(['qrCodes' => function($q) {
            $q->where('is_used', false)
              ->where(function($query) {
                  $query->where('expires_at', '>', now())
                        ->orWhereNull('expires_at');
              });
        }])->get();

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

        return view('student.attendance', [
            'attendanceRecords' => $attendanceRecords,
        ]);
    }

    public function myClasses(): View
    {
        $user = Auth::user();
        $classes = $user->classes()->with(['professor', 'qrCodes'])->get();

        return view('student.classes', [
            'classes' => $classes,
        ]);
    }
}
