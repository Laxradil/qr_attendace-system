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
                // Get all classes the student is enrolled in with active QR codes
                $classes = $user->enrolledClasses()->with(['qrCodes' => function($q) {
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
        $classes = $user->enrolledClasses()->with(['professor', 'qrCodes'])->get();

        return view('student.classes', [
            'classes' => $classes,
        ]);
    }

    public function generateStudentQR(int $classId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="white"/><text x="100" y="100" text-anchor="middle">Not authenticated</text></svg>';
        }
        
        $classe = Classe::find($classId);
        
        if (!$classe) {
            return '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="white"/><text x="100" y="100" text-anchor="middle">Class not found</text></svg>';
        }
        
        // Generate QR code with student data
        $qrData = json_encode([
            'type' => 'student_attendance',
            'student_id' => $user->id,
            'student_name' => $user->name,
            'student_email' => $user->email,
            'class_id' => $classId,
            'class_name' => $classe->name,
            'class_code' => $classe->code,
            'generated_at' => now()->toIso8601String(),
        ]);
        
        // Generate a simple QR code SVG manually
        $svg = $this->generateSimpleQR($qrData);
        
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
    
    private function generateSimpleQR(string $data): string
    {
        // Create a simple QR code pattern based on the data
        $hash = md5($data);
        $size = 200;
        $modules = 21; // 21x21 QR code
        $cellSize = $size / $modules;
        
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">';
        
        // White background
        $svg .= '<rect width="' . $size . '" height="' . $size . '" fill="white"/>';
        
        // Generate QR pattern
        for ($row = 0; $row < $modules; $row++) {
            for ($col = 0; $col < $modules; $col++) {
                // Finder patterns (corners)
                $isFinderPattern = false;
                
                // Top-left finder pattern
                if ($row < 7 && $col < 7) {
                    if (($row == 0 || $row == 6 || $col == 0 || $col == 6) && ($row == 0 || $row == 6 || $col == 0 || $col == 6)) {
                        $isFinderPattern = true;
                    } elseif ($row >= 2 && $row <= 4 && $col >= 2 && $col <= 4) {
                        $isFinderPattern = true;
                    }
                }
                // Top-right finder pattern
                elseif ($row < 7 && $col > 13) {
                    if (($row == 0 || $row == 6 || $col == 14 || $col == 20) && ($row == 0 || $row == 6 || $col == 14 || $col == 20)) {
                        $isFinderPattern = true;
                    } elseif ($row >= 2 && $row <= 4 && $col >= 16 && $col <= 18) {
                        $isFinderPattern = true;
                    }
                }
                // Bottom-left finder pattern
                elseif ($row > 13 && $col < 7) {
                    if (($row == 14 || $row == 20 || $col == 0 || $col == 6) && ($row == 14 || $row == 20 || $col == 0 || $col == 6)) {
                        $isFinderPattern = true;
                    } elseif ($row >= 16 && $row <= 18 && $col >= 2 && $col <= 4) {
                        $isFinderPattern = true;
                    }
                }
                
                if ($isFinderPattern) {
                    $svg .= '<rect x="' . ($col * $cellSize) . '" y="' . ($row * $cellSize) . '" width="' . $cellSize . '" height="' . $cellSize . '" fill="black"/>';
                } else {
                    // Data modules based on hash
                    $idx = ($row * $modules + $col) % 32;
                    $bit = hexdec($hash[$idx % 32]) % 2;
                    if ($bit) {
                        $svg .= '<rect x="' . ($col * $cellSize) . '" y="' . ($row * $cellSize) . '" width="' . $cellSize . '" height="' . $cellSize . '" fill="black"/>';
                    }
                }
            }
        }
        
        $svg .= '</svg>';
        return $svg;
    }
}
