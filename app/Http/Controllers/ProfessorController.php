<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\AttendanceRecord;
use App\Models\DropRequest;
use App\Models\SystemLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfessorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:professor');
    }

    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        // Use withCount() to get student counts in the initial query instead of calling count() in loop
        $classes = $user->assignedClasses()
            ->withCount('students')
            ->with(['students', 'schedules'])
            ->get();
        
        $totalClasses = $classes->count();
        $totalStudents = $classes->sum('students_count'); // Use the counted value, not a new query

        $attendanceSummary = AttendanceRecord::whereIn('class_id', $classes->pluck('id'))
            ->selectRaw("COUNT(*) as total_records, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count, SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count, SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count, SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused_count")
            ->first();

        $totalRecords = (int) ($attendanceSummary->total_records ?? 0);
        $presentCount = (int) ($attendanceSummary->present_count ?? 0);
        $lateCount = (int) ($attendanceSummary->late_count ?? 0);
        $absentCount = (int) ($attendanceSummary->absent_count ?? 0);
        $excusedCount = (int) ($attendanceSummary->excused_count ?? 0);

        $recentLogs = $user->logs()
            ->latest()
            ->limit(5)
            ->get();

        $todaySchedules = $user->assignedClasses()
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
            'excusedCount' => $excusedCount,
            'attendanceRate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100) : 0,
            'recentLogs' => $recentLogs,
            'todaySchedules' => $todaySchedules,
            'classes' => $classes,
        ]);
    }

    public function myClasses(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $classes = $user->assignedClasses()
            ->withCount('students')
            ->with(['schedules', 'students:id'])
            ->get();

        $availableStudents = User::where('role', 'student')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('professor.classes', [
            'classes' => $classes,
            'availableStudents' => $availableStudents,
        ]);
    }

    public function updateClass(Request $request, Classe $classe): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->assignedClasses()->whereKey($classe->id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'code' => 'required|string|unique:classes,code,' . $classe->id . '|max:20',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'schedule_id' => 'nullable|exists:schedules,id',
            'days' => 'required|array|min:1',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string|max:20',
        ]);

        $classe->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $schedule = null;
        if (!empty($validated['schedule_id'])) {
            $schedule = $classe->schedules()->where('id', $validated['schedule_id'])->first();
        }

        if (!$schedule) {
            $schedule = $classe->schedules()->first();
        }

        $daysValue = implode(', ', $validated['days']);

        if (!$schedule) {
            $classe->schedules()->create([
                'class_id' => $classe->id,
                'professor_id' => $user->id,
                'professor' => $user->name,
                'subject_code' => $validated['code'],
                'subject_name' => $validated['name'],
                'days' => $daysValue,
                'time' => $validated['start_time'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'room' => $validated['room'],
            ]);
        } else {
            $schedule->update([
                'days' => $daysValue,
                'time' => $validated['start_time'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'room' => $validated['room'],
            ]);
        }

        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'update_class',
            'description' => 'Updated class information for: ' . $classe->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('professor.classes')->with('success', 'Class updated successfully.');
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
        /** @var User $user */
        $user = Auth::user();
        $classes = $user->assignedClasses()->with('students')->get();
        $selectedClassId = request()->query('class_id');

        return view('professor.scan-qr', [
            'classes' => $classes,
            'selectedClassId' => $selectedClassId,
        ]);
    }

    public function recordAttendance(Request $request): Response|RedirectResponse|JsonResponse
    {
        // Check if it's a student QR code (JSON data) or class QR code UUID
        $qrData = $request->input('qr_code');

        // Try to decode as JSON (student QR code)
        $decoded = json_decode($qrData, true);

        if ($decoded && isset($decoded['type']) && $decoded['type'] === 'student_attendance') {
            $selectedClassId = $request->input('class_id');
            $selectedStudentId = $request->input('student_id');
            $studentName = $decoded['student_name'] ?? 'Unknown';

            if (!$selectedClassId || !$selectedStudentId) {
                $message = 'Please select a class and student before submitting.';
                return $request->expectsJson()
                    ? response()->json(['error' => $message], 422)
                    : back()->with('error', $message);
            }

            if ($decoded['student_id'] != $selectedStudentId) {
                $message = 'Scanned QR does not match the selected student.';
                return $request->expectsJson()
                    ? response()->json(['error' => $message], 422)
                    : back()->with('error', $message);
            }

            /** @var User $user */
            $user = Auth::user();

            // Combine multiple validations into a single efficient query
            $classe = Classe::with('schedules')
                ->whereHas('professors', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->whereHas('students', function($q) use ($selectedStudentId) {
                    $q->where('users.id', $selectedStudentId);
                })
                ->find($selectedClassId);

            if (!$classe) {
                $message = !$classe || !$user->assignedClasses()->whereKey($selectedClassId)->exists()
                    ? 'You are not assigned to the selected class.'
                    : 'This student is not enrolled in the selected class.';
                return $request->expectsJson()
                    ? response()->json(['error' => $message], 403)
                    : back()->with('error', $message);
            }

            // Check if already recorded today
            $alreadyRecorded = AttendanceRecord::where('student_id', $selectedStudentId)
                ->where('class_id', $selectedClassId)
                ->whereDate('recorded_at', today())
                ->exists();

            if ($alreadyRecorded) {
                $message = 'Attendance already recorded for this student today';
                return $request->expectsJson()
                    ? response()->json(['error' => $message], 409)
                    : back()->with('error', $message);
            }

            $schedule = $this->findTodayScheduleForClass($classe);
            $attendanceStatus = 'present';
            $minutesLate = null;
            $recordedAt = now();

            if ($schedule && $schedule->start_time) {
                $sessionStart = Carbon::parse($schedule->start_time)->setDate($recordedAt->year, $recordedAt->month, $recordedAt->day);
                $lateThreshold = $sessionStart->copy()->addMinutes(15);
                $absentThreshold = $sessionStart->copy()->addMinutes(20);

                if ($recordedAt->greaterThanOrEqualTo($absentThreshold)) {
                    $attendanceStatus = 'absent';
                } elseif ($recordedAt->greaterThanOrEqualTo($lateThreshold)) {
                    $attendanceStatus = 'late';
                    $minutesLate = (int) $recordedAt->diffInMinutes($sessionStart);
                }
            }

            AttendanceRecord::create([
                'class_id' => $selectedClassId,
                'student_id' => $selectedStudentId,
                'qr_code_id' => null,
                'status' => $attendanceStatus,
                'minutes_late' => $minutesLate ?? 0,
                'recorded_at' => $recordedAt,
            ]);

            SystemLog::create([
                'user_id' => Auth::id(),
                'action' => 'scan_qr',
                'description' => 'Scanned student QR and recorded attendance for ' . $studentName,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $message = 'Attendance recorded successfully for ' . $studentName;
            return $request->expectsJson()
                ? response()->json(['message' => $message])
                : back()->with('success', $message);
        }

        $message = 'Only student QR codes are supported now. Please scan the student attendance QR code.';
        return $request->expectsJson()
            ? response()->json(['error' => $message], 422)
            : back()->with('error', $message);
    }

    private function findTodayScheduleForClass(Classe $classe)
    {
        $today = now()->format('l');
        return $classe->schedules->first(fn($schedule) => $this->scheduleMatchesDay($schedule, $today));
    }

    private function scheduleMatchesDay($schedule, string $day): bool
    {
        if (empty($schedule->days)) {
            return false;
        }

        $normalizedDay = strtolower($day);
        return collect($this->parseScheduleDays($schedule->days))
            ->map(fn($value) => strtolower($value))
            ->contains($normalizedDay);
    }

    private function parseScheduleDays(string $days): array
    {
        $tokens = preg_split('/[\s,;\/]+/', trim($days));
        $mapped = [];

        foreach ($tokens as $token) {
            $token = trim($token);
            if (!$token) {
                continue;
            }

            $lower = strtolower($token);
            $map = [
                'mon' => 'Monday',
                'monday' => 'Monday',
                'tue' => 'Tuesday',
                'tues' => 'Tuesday',
                'tuesday' => 'Tuesday',
                'wed' => 'Wednesday',
                'wednesday' => 'Wednesday',
                'thu' => 'Thursday',
                'thur' => 'Thursday',
                'thurs' => 'Thursday',
                'thursday' => 'Thursday',
                'fri' => 'Friday',
                'friday' => 'Friday',
                'sat' => 'Saturday',
                'saturday' => 'Saturday',
                'sun' => 'Sunday',
                'sunday' => 'Sunday',
            ];

            if (isset($map[$lower])) {
                $mapped[] = $map[$lower];
            }
        }

        return array_unique($mapped);
    }

    public function attendanceRecords(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();
        $classes = $user->assignedClasses()->get()->unique('id')->values();
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
            ->selectRaw("COUNT(*) as total_records, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count, SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count, SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count, SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused_count")
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
            'excusedCount' => (int) ($summary->excused_count ?? 0),
        ]);
    }

    public function schedules(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $schedules = $user->assignedClasses()
            ->with('schedules')
            ->get()
            ->flatMap(fn($c) => $c->schedules);

        return view('professor.schedules', [
            'schedules' => $schedules,
        ]);
    }

    public function reports(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $classes = $user->assignedClasses()->with('students')->get();
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
        /** @var User $user */
        $user = Auth::user();
        $classes = $user->assignedClasses()
            ->with('students')
            ->get();

        $pendingRequests = [];
        try {
            $pendingRequests = DropRequest::whereIn('class_id', $classes->pluck('id'))
                ->where('status', 'pending')
                ->get()
                ->mapWithKeys(fn (DropRequest $request) => ["{$request->class_id}_{$request->student_id}" => $request]);
        } catch (\Exception) {
            // Table doesn't exist yet - migration hasn't been run
        }

        return view('professor.students', [
            'classes' => $classes,
            'pendingRequests' => $pendingRequests,
        ]);
    }

    public function submitDropRequest(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:users,id',
            'reason' => [
                'required',
                'string',
                'max:500',
                Rule::in([
                    'Schedule conflict',
                    'Transfer to another section',
                    'Medical reason',
                    'Academic performance',
                    'Personal reasons',
                ]),
            ],
        ]);

        $classe = Classe::findOrFail($validated['class_id']);
        $student = User::findOrFail($validated['student_id']);

        abort_unless($user->assignedClasses()->whereKey($classe->id)->exists(), 403);

        if (!$classe->students()->wherePivot('student_id', $student->id)->exists()) {
            return back()->with('error', 'This student is not enrolled in the selected class.');
        }

        $existingRequest = DropRequest::where('class_id', $classe->id)
            ->where('student_id', $student->id)
            ->where('professor_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'A pending or approved drop request already exists for this student in the selected class.');
        }

        DropRequest::create([
            'professor_id' => $user->id,
            'student_id' => $student->id,
            'class_id' => $classe->id,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'other',
            'description' => 'Requested to drop ' . $student->name . ' from ' . $classe->code . ' - ' . $classe->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Drop request submitted and is pending admin approval.');
    }

    public function logs(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $logs = $user->logs()
            ->latest()
            ->paginate(20);

        return view('professor.logs', [
            'logs' => $logs,
        ]);
    }

    public function settings(): View
    {
        /** @var User $user */
        return view('professor.settings', [
            'user' => Auth::user(),
        ]);
    }

    public function editAttendanceRecord(AttendanceRecord $attendanceRecord): View
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->assignedClasses()->whereKey($attendanceRecord->class_id)->exists(), 403);

        return view('professor.edit-attendance', [
            'record' => $attendanceRecord->load('student', 'classe'),
        ]);
    }

    public function updateAttendanceRecord(Request $request, AttendanceRecord $attendanceRecord): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->assignedClasses()->whereKey($attendanceRecord->class_id)->exists(), 403);

        $validated = $request->validate([
            'status' => 'required|in:present,late,absent,excused',
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
            'user_id' => Auth::id(),
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
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);

        return back()->with('success', 'Settings updated successfully');
    }

    public function addStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $classe = Classe::findOrFail($validated['class_id']);
        $student = User::findOrFail($validated['student_id']);

        // Verify the professor is assigned to this class
        if (!$classe->professors()->wherePivot('professor_id', Auth::id())->exists()) {
            return back()->with('error', 'You do not have permission to add students to this class');
        }

        // Verify the user is a student
        if ($student->role !== 'student') {
            return back()->with('error', 'The provided email belongs to a user who is not a student');
        }

        // Check if the student is already enrolled in the class using DB
        $exists = DB::table('class_student')
            ->where('class_id', $classe->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'The student is already enrolled in this class');
        }

        // Enroll the student
        $classe->students()->attach($student->id);

        return back()->with('success', 'Student added to class successfully');
    }

    /**
     * Return students enrolled in a class (AJAX)
     */
    public function getClassStudents(int $id)
    {
        $classe = Classe::with(['students', 'professors'])->findOrFail($id);

        // Verify authorization by checking if current professor is in professors collection
        if (!$classe->professors->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $students = $classe->students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'enrolled_at' => $student->pivot->enrolled_at,
            ];
        });
        return response()->json(['students' => $students]);
    }
}
