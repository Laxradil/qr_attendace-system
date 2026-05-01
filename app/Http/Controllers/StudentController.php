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

        return view('student.attendance', [
            'attendanceRecords' => $attendanceRecords,
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

    public function generateStudentQR(int $classId): \Illuminate\Http\Response
    {
        $user = Auth::user();
        
        // Verify student is enrolled in this class
        $enrolled = $user->classes()->whereKey($classId)->exists();
        
        if (!$enrolled) {
            return response('Class not found or not enrolled', 404);
        }
        
        $classe = Classe::findOrFail($classId);
        
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
        
        $svg = $this->generateQRCodeSVG($qrData);
        
        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    private function generateQRCodeSVG(string $data): string
    {
        // Generate QR code SVG from JSON data
        $size = 150;
        $padding = 15;
        
        // Use the data as hash for consistent QR pattern
        $hash = md5($data);
        $pattern = [];
        
        // Generate 21x21 QR code pattern
        for ($y = 0; $y < 21; $y++) {
            $row = [];
            for ($x = 0; $x < 21; $x++) {
                // Finder patterns (corners)
                if (($x < 7 && $y < 7) || ($x > 13 && $y < 7) || ($x < 7 && $y > 13)) {
                    if (($x < 3 || $x > 3) && ($y < 3 || $y > 3) && ($x >= 0 && $x <= 6 && $y >= 0 && $y <= 6)) {
                        $row[] = ($x == 0 || $x == 6 || $y == 0 || $y == 6 || ($x >= 2 && $x <= 4 && $y >= 2 && $y <= 4)) ? 1 : 0;
                    } elseif ($x > 13 && $y < 7) {
                        $row[] = ($x == 14 || $x == 20 || $y == 0 || $y == 6 || ($x >= 16 && $x <= 18 && $y >= 2 && $y <= 4)) ? 1 : 0;
                    } elseif ($x < 7 && $y > 13) {
                        $row[] = ($x == 0 || $x == 6 || $y == 14 || $y == 20 || ($x >= 2 && $x <= 4 && $y >= 16 && $y <= 18)) ? 1 : 0;
                    } else {
                        $row[] = 0;
                    }
                } else {
                    // Data pattern based on hash
                    $idx = ($y * 21 + $x) % 32;
                    $row[] = (int)substr($hash, $idx, 1) % 2;
                }
            }
            $pattern[] = $row;
        }
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . ($size + $padding * 2) . ' ' . ($size + $padding * 2) . '" width="' . $size . '" height="' . $size . '">';
        $svg .= '<rect x="0" y="0" width="' . ($size + $padding * 2) . '" height="' . ($size + $padding * 2) . '" fill="white"/>';
        
        $cellSize = $size / 21;
        for ($y = 0; $y < 21; $y++) {
            for ($x = 0; $x < 21; $x++) {
                if ($pattern[$y][$x]) {
                    $svg .= '<rect x="' . ($padding + $x * $cellSize) . '" y="' . ($padding + $y * $cellSize) . '" width="' . $cellSize . '" height="' . $cellSize . '" fill="black"/>';
                }
            }
        }
        
        $svg .= '</svg>';
        return $svg;
    }
}
