<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\QRCode;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfessorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:professor');
    }

    public function dashboard(): View
    {
        $user = auth()->user();
        // Use withCount() to get student counts in the initial query instead of calling count() in loop
        $classes = $user->classes()
            ->withCount('students')
            ->with('students')
            ->get();
        
        $totalClasses = $classes->count();
        $totalStudents = $classes->sum('students_count'); // Use the counted value, not a new query
        
        $attendanceStats = AttendanceRecord::whereIn('class_id', $classes->pluck('id'))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $todaySchedules = $user->classes()
            ->with('schedules')
            ->get()
            ->flatMap(fn($c) => $c->schedules)
            ->filter(fn($s) => str_contains($s->days, now()->format('l')));

        return view('professor.dashboard', [
            'totalClasses' => $totalClasses,
            'totalStudents' => $totalStudents,
            'attendanceStats' => $attendanceStats,
            'todaySchedules' => $todaySchedules,
        ]);
    }

    public function myClasses(): View
    {
        $classes = auth()->user()->classes()
            ->withCount('students')
            ->get();

        return view('professor.classes', [
            'classes' => $classes,
        ]);
    }

    public function showClass(Classe $classe): View
    {
        $this->authorize('view', $classe);

        return view('professor.class-detail', [
            'classe' => $classe->load('students', 'schedules'),
        ]);
    }

    public function scanQR(): View
    {
        $classes = auth()->user()->classes()->get();
        return view('professor.scan-qr', ['classes' => $classes]);
    }

    public function recordAttendance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'qr_code' => 'required|uuid',
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $qrCode = QRCode::where('uuid', $validated['qr_code'])->firstOrFail();
        
        if (!$qrCode->isValid()) {
            return back()->with('error', 'Invalid or expired QR code');
        }

        AttendanceRecord::create([
            'class_id' => $validated['class_id'],
            'student_id' => $validated['student_id'],
            'qr_code_id' => $qrCode->id,
            'status' => 'present',
            'recorded_at' => now(),
        ]);

        $qrCode->update(['is_used' => true, 'used_at' => now()]);

        return back()->with('success', 'Attendance recorded successfully');
    }

    public function attendanceRecords(Request $request): View
    {
        $classes = auth()->user()->classes()->get();
        $classId = $request->query('class_id');
        $date = $request->query('date');

        $query = AttendanceRecord::whereIn('class_id', $classes->pluck('id'));

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($date) {
            $query->whereDate('recorded_at', $date);
        }

        $records = $query->with('student', 'classe')
            ->orderBy('recorded_at', 'desc')
            ->paginate(20);

        return view('professor.attendance-records', [
            'records' => $records,
            'classes' => $classes,
        ]);
    }

    public function schedules(): View
    {
        $schedules = auth()->user()->classes()
            ->with('schedules')
            ->get()
            ->flatMap(fn($c) => $c->schedules);

        return view('professor.schedules', [
            'schedules' => $schedules,
        ]);
    }

    public function reports(): View
    {
        $classes = auth()->user()->classes()->with('students')->get();
        $classId = request()->query('class_id');

        if ($classId) {
            $classe = $classes->find($classId);
            $students = $classe->students;
            
            // Get ALL attendance data in ONE query (not per-student loops)
            $allStats = AttendanceRecord::where('class_id', $classe->id)
                ->selectRaw('student_id, COUNT(*) as total, SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
                ->groupBy('student_id')
                ->get()
                ->keyBy('student_id');

            // Map students with their attendance stats
            $attendanceData = $students->map(function($student) use ($allStats) {
                $stats = $allStats[$student->id] ?? null;
                $total = $stats?->total ?? 0;
                $present = $stats?->present ?? 0;

                return [
                    'student' => $student,
                    'total' => $total,
                    'present' => $present,
                    'percentage' => $total > 0 ? round(($present / $total) * 100) : 0,
                ];
            });
        } else {
            $attendanceData = null;
        }

        return view('professor.reports', [
            'classes' => $classes,
            'attendanceData' => $attendanceData,
        ]);
    }

    public function students(): View
    {
        $students = auth()->user()->classes()
            ->with('students')
            ->get()
            ->flatMap(fn($c) => $c->students)
            ->unique('id')
            ->values();

        return view('professor.students', [
            'students' => $students,
        ]);
    }

    public function logs(): View
    {
        $logs = auth()->user()->logs()
            ->latest()
            ->paginate(20);

        return view('professor.logs', [
            'logs' => $logs,
        ]);
    }

    public function settings(): View
    {
        return view('professor.settings', [
            'user' => auth()->user(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);

        return back()->with('success', 'Settings updated successfully');
    }
}
