<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\QRCode;
use App\Models\AttendanceRecord;
use App\Models\SystemLog;
use App\Models\User;
use Carbon\Carbon;
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

        $attendanceSummary = AttendanceRecord::whereIn('class_id', $classes->pluck('id'))
            ->selectRaw("COUNT(*) as total_records, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count, SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count, SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count")
            ->first();

        $totalRecords = (int) ($attendanceSummary->total_records ?? 0);
        $presentCount = (int) ($attendanceSummary->present_count ?? 0);
        $lateCount = (int) ($attendanceSummary->late_count ?? 0);
        $absentCount = (int) ($attendanceSummary->absent_count ?? 0);

        $recentLogs = $user->logs()
            ->latest()
            ->limit(5)
            ->get();

        $todaySchedules = $user->classes()
            ->with('schedules')
            ->get()
            ->flatMap(fn($c) => $c->schedules)
            ->filter(fn($s) => str_contains($s->days, now()->format('l')));

        return view('professor.dashboard', [
            'totalClasses' => $totalClasses,
            'totalStudents' => $totalStudents,
            'totalRecords' => $totalRecords,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'attendanceRate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100) : 0,
            'recentLogs' => $recentLogs,
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

        abort_unless(auth()->user()->classes()->whereKey($validated['class_id'])->exists(), 403);

        $qrCode = QRCode::where('uuid', $validated['qr_code'])->firstOrFail();
        $student = User::findOrFail($validated['student_id']);
        
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

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'scan_qr',
            'description' => 'Scanned QR and recorded attendance for ' . $student->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

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

        $summaryQuery = clone $query;
        $summary = $summaryQuery
            ->selectRaw("COUNT(*) as total_records, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count, SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count, SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count")
            ->first();

        $records = $query->with('student', 'classe')
            ->orderBy('recorded_at', 'desc')
            ->paginate(20);

        return view('professor.attendance-records', [
            'records' => $records,
            'classes' => $classes,
            'totalRecords' => (int) ($summary->total_records ?? 0),
            'presentCount' => (int) ($summary->present_count ?? 0),
            'lateCount' => (int) ($summary->late_count ?? 0),
            'absentCount' => (int) ($summary->absent_count ?? 0),
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
            abort_unless($classe, 403);
            $students = $classe->students;
            
            // Get ALL attendance data in ONE query (not per-student loops)
            $allStats = AttendanceRecord::where('class_id', $classe->id)
                ->selectRaw("student_id, COUNT(*) as total, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
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

    public function editAttendanceRecord(AttendanceRecord $attendanceRecord): View
    {
        abort_unless(auth()->user()->classes()->whereKey($attendanceRecord->class_id)->exists(), 403);

        return view('professor.edit-attendance', [
            'record' => $attendanceRecord->load('student', 'classe'),
        ]);
    }

    public function updateAttendanceRecord(Request $request, AttendanceRecord $attendanceRecord): RedirectResponse
    {
        abort_unless(auth()->user()->classes()->whereKey($attendanceRecord->class_id)->exists(), 403);

        $validated = $request->validate([
            'status' => 'required|in:present,late,absent',
            'recorded_at' => 'required|date',
            'minutes_late' => 'nullable|integer|min:0',
        ]);

        $minutesLate = $validated['status'] === 'late'
            ? (int) ($validated['minutes_late'] ?? 0)
            : 0;

        $attendanceRecord->update([
            'status' => $validated['status'],
            'minutes_late' => $minutesLate,
            'recorded_at' => Carbon::parse($validated['recorded_at']),
        ]);

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'attendance_record',
            'description' => 'Updated attendance for ' . $attendanceRecord->student->name . ' in ' . $attendanceRecord->classe->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('professor.attendance-records', ['class_id' => $attendanceRecord->class_id])
            ->with('success', 'Attendance updated successfully');
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
