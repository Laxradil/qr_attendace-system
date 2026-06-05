<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classe;
use App\Models\AttendanceRecord;
use App\Models\SystemLog;
use App\Models\QRCode;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function dashboard(): View
    {
        // Cache totals for 10 minutes to save Supabase resources
        $stats = Cache::remember('admin_stats', 600, function () {
            // Combine all user counts into single query instead of 3 separate queries
            $userStats = User::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN role = 'professor' THEN 1 ELSE 0 END) as professors,
                SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) as students
            ")->first();

            $totalUsers = $userStats->total;
            $totalProfessors = $userStats->professors ?? 0;
            $totalStudents = $userStats->students ?? 0;
            $totalClasses = Classe::count();

            // Combine attendance counts into a single query
            $attendanceCounts = AttendanceRecord::selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN status = \'present\' THEN 1 END) as present,
                COUNT(CASE WHEN status = \'late\' THEN 1 END) as late,
                COUNT(CASE WHEN status = \'absent\' THEN 1 END) as absent
            ')->first();

            $todayRecords = AttendanceRecord::whereDate('recorded_at', now())->count();

            return [
                'totalUsers' => $totalUsers,
                'totalProfessors' => $totalProfessors,
                'totalStudents' => $totalStudents,
                'totalClasses' => $totalClasses,
                'totalAttendance' => $attendanceCounts->total ?? 0,
                'presentCount' => $attendanceCounts->present ?? 0,
                'lateCount' => $attendanceCounts->late ?? 0,
                'absentCount' => $attendanceCounts->absent ?? 0,
                'todayRecords' => $todayRecords,
            ];
        });

        // Cache recent logs for 1 minute
        $recentLogs = Cache::remember('admin_recent_logs', 60, function () {
            return SystemLog::with('user')->latest()->limit(10)->get();
        });

        return view('admin.dashboard-new', array_merge($stats, ['recentLogs' => $recentLogs]));
    }

    private function getUserStats(): array
    {
        return Cache::remember('admin_user_stats', 300, function () {
            return [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'professors' => User::where('role', 'professor')->count(),
                'students' => User::where('role', 'student')->count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
            ];
        });
    }

    private function getAdminUsersCacheKeys(): array
    {
        return Cache::get('admin_users_cache_keys', []);
    }

    private function addAdminUsersCacheKey(string $cacheKey): void
    {
        $keys = $this->getAdminUsersCacheKeys();
        if (!in_array($cacheKey, $keys, true)) {
            $keys[] = $cacheKey;
            Cache::forever('admin_users_cache_keys', $keys);
        }
    }

    private function clearAdminUsersCache(): void
    {
        $keys = $this->getAdminUsersCacheKeys();
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('admin_users_cache_keys');
    }

    // Users Management

    public function users(): View
    {
        $role = request('role');
        $status = request('status');

        // Build query with filters
        $query = User::with('classes', 'attendanceRecords');

        if ($role && in_array($role, ['admin', 'professor', 'student'])) {
            $query->where('role', $role);
        }

        if ($status !== null && in_array($status, ['0', '1'])) {
            $query->where('is_active', (bool) $status);
        }

        // Cache with filter params
        $cacheKey = 'admin_users_' . md5($role . $status . request('page', 1));
        $users = Cache::remember($cacheKey, 60, function () use ($query) {
            return $query->paginate(20);
        });
        $this->addAdminUsersCacheKey($cacheKey);

        $stats = $this->getUserStats();

        return view('admin.users-new', [
            'users' => $users,
            'stats' => $stats,
            'filters' => [
                'role' => $role,
                'status' => $status,
            ]
        ]);
    }

    // Debug helper to inspect computed user stats
    public function debugUserStats()
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403);
        }

        return response()->json($this->getUserStats());
    }

    public function createUser(): View
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users|max:255',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,professor',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'student_id' => null,
            'section' => null,
        ]);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'add_user',
            'description' => 'Created new user: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin_user_stats');
        Cache::forget('admin_stats');
        $this->clearAdminUsersCache();

        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    public function editUser(User $user): View
    {
        // Admin should not edit student user records anymore.
        if ($user->isStudent()) {
            abort(403);
        }

        return view('admin.edit-user', ['user' => $user]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        // Admin should not update student user records.
        if ($user->isStudent()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,professor',
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $oldRole = $user->role;

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
            'section' => $validated['role'] === 'student' ? ($validated['section'] ?? null) : null,
            'is_active' => $validated['is_active'] ?? false,
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_user',
            'description' => 'Updated user: ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // If role changed from student -> non-student, detach enrolled classes
        if ($oldRole === 'student' && ($validated['role'] ?? '') !== 'student') {
            try {
                $user->enrolledClasses()->detach();
            } catch (\Exception $e) {
                // don't block the update flow on detach errors
            }
        }

        Cache::forget('admin_user_stats');
        Cache::forget('admin_stats');
        $this->clearAdminUsersCache();

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        $name = $user->name;
        $actorId = Auth::id();

        SystemLog::create([
            'user_id' => $actorId,
            'action' => 'delete_user',
            'description' => 'Deleted user: ' . $name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $user->delete();

        Cache::forget('admin_user_stats');
        Cache::forget('admin_stats');
        $this->clearAdminUsersCache();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    // Professors Management

    public function professors(): View
    {
        // Cache professors for 1 minute
        $professors = Cache::remember('admin_professors_page_' . request('page', 1), 60, function () {
            return User::with('classes')->where('role', 'professor')->paginate(20);
        });
        return view('admin.professors-new', ['professors' => $professors]);
    }

    // Students Management

    public function students(): View
    {
        // Cache class-based student groups for 1 minute
        $classes = Cache::remember('admin_students_by_class_page_' . request('page', 1), 60, function () {
            return Classe::with(['students', 'professors'])->paginate(10);
        });
        return view('admin.students-new', ['classes' => $classes]);
    }

    // Classes Management
    public function classes(): View
    {
        // Cache classes for 1 minute
        $classes = Cache::remember('admin_classes_page_' . request('page', 1), 60, function () {
            return Classe::with('professors', 'students')->paginate(20);
        });
        return view('admin.classes-new', ['classes' => $classes]);
    }

    public function createClass(): View
    {
        $professors = User::where('role', 'professor')->get();
        return view('admin.create-class', ['professors' => $professors]);
    }

    public function storeClass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classes|max:20',
            'room_code' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days' => 'required|array|min:1',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'professors' => 'required|array|min:1',
            'professors.*' => 'required|exists:users,id',
        ]);

        $professors = $validated['professors'];
        $primaryProfessorId = $professors[0];

        $classe = Classe::create([
            'code' => $validated['code'],
            'room_code' => $validated['room_code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'professor_id' => $primaryProfessorId,
        ]);

        $primaryProfessor = User::findOrFail($primaryProfessorId);

        $daysValue = implode(', ', $validated['days']);

        Schedule::create([
            'class_id' => $classe->id,
            'subject_code' => $validated['code'],
            'subject_name' => $validated['name'],
            'professor_id' => $primaryProfessor->id,
            'professor' => $primaryProfessor->name,
            'days' => $daysValue,
            'time' => \Carbon\Carbon::createFromFormat('H:i', $validated['start_time'])->format('g:i A') . ' - ' . \Carbon\Carbon::createFromFormat('H:i', $validated['end_time'])->format('g:i A'),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room' => $validated['room_code'],
        ]);

        $classe->professors()->attach($professors);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_class',
            'description' => 'Created class: ' . $validated['name'] . ' with ' . count($professors) . ' professor(s)',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin_stats');
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('admin_classes_page_' . $i);
        }

        return redirect()->route('admin.classes')->with('success', 'Class created successfully');
    }

    public function editClass(Classe $classe): View
    {
        $classe->load('professors', 'schedules');
        $professors = User::where('role', 'professor')->get();
        return view('admin.edit-class', [
            'classe' => $classe,
            'professors' => $professors,
            'schedule' => $classe->schedules->first(),
        ]);
    }

    public function updateClass(Request $request, Classe $classe): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classes,code,' . $classe->id . '|max:20',
            'room_code' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days' => 'required|array|min:1',
            'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'professors' => 'required|array|min:1',
            'professors.*' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $professors = $validated['professors'];
        $primaryProfessorId = $professors[0];

        $classe->update([
            'code' => $validated['code'],
            'room_code' => $validated['room_code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'professor_id' => $primaryProfessorId,
            'is_active' => $validated['is_active'] ?? false,
        ]);

        $daysValue = implode(', ', $validated['days']);

        Schedule::updateOrCreate(
            ['class_id' => $classe->id],
            [
                'subject_code' => $validated['code'],
                'subject_name' => $validated['name'],
                'professor_id' => $primaryProfessorId,
                'professor' => User::findOrFail($primaryProfessorId)->name,
                'days' => $daysValue,
                'time' => \Carbon\Carbon::createFromFormat('H:i', $validated['start_time'])->format('g:i A') . ' - ' . \Carbon\Carbon::createFromFormat('H:i', $validated['end_time'])->format('g:i A'),
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'room' => $validated['room_code'],
            ]
        );

        $classe->professors()->sync($professors);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_class',
            'description' => 'Updated class: ' . $classe->name . ' with ' . count($professors) . ' professor(s)',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin_stats');
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('admin_classes_page_' . $i);
        }

        return redirect()->route('admin.classes')->with('success', 'Class updated successfully');
    }

    public function enrollClassForm(Classe $classe): View
    {
        $classe->load('professors', 'students');
        $availableStudents = User::where('role', 'student')
            ->whereNotIn('id', $classe->students->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('admin.enroll-class', [
            'classe' => $classe,
            'availableStudents' => $availableStudents,
        ]);
    }

    public function storeClassEnrollment(Request $request, Classe $classe): RedirectResponse
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:users,id',
        ]);

        $studentIds = User::whereIn('id', $validated['student_ids'])
            ->where('role', 'student')
            ->pluck('id')
            ->toArray();

        if (empty($studentIds)) {
            return back()->with('error', 'Please select valid students to enroll.');
        }

        $alreadyEnrolledStudents = $classe->students()
            ->whereIn('users.id', $studentIds)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        if (!empty($alreadyEnrolledStudents)) {
            return back()->with('error', 'The following student(s) are already enrolled in this class: ' . implode(', ', $alreadyEnrolledStudents));
        }

        $classe->students()->attach($studentIds);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'add_student',
            'description' => 'Enrolled ' . count($studentIds) . ' student(s) into class: ' . $classe->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin_stats');
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('admin_classes_page_' . $i);
            Cache::forget('admin_students_by_class_page_' . $i);
        }

        return redirect()->route('admin.classes')->with('success', 'Students enrolled successfully');
    }

    public function deleteClass(Classe $classe): RedirectResponse
    {
        $name = $classe->name;
        $classe->delete();

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_class',
            'description' => 'Deleted class: ' . $name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Cache::forget('admin_stats');
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('admin_classes_page_' . $i);
        }

        return redirect()->route('admin.classes')->with('success', 'Class deleted successfully');
    }

    // QR Code Management
    public function qrCodes(): View
    {
        $students = User::where('role', 'student')
              ->with(['enrolledClasses', 'studentQrCode'])
            ->orderBy('name')
            ->paginate(20, ['*'], 'students_page');

        return view('admin.qr-codes-new', compact('students'));
    }

    public function downloadAllQrCodesZip()
    {
        $students = User::where('role', 'student')
            ->with(['enrolledClasses', 'studentQrCode'])
            ->orderBy('name')
            ->get();

        $zipPath = tempnam(sys_get_temp_dir(), 'qr_codes_zip_');

        if ($zipPath === false) {
            abort(500, 'Unable to create temporary archive.');
        }

        $zipFile = $zipPath . '.zip';
        @unlink($zipPath);

        $zip = new \ZipArchive();

        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Unable to create QR code archive.');
        }

        foreach ($students as $student) {
            $fileName = preg_replace('/[^A-Za-z0-9_-]+/', '_', trim($student->name)) ?: 'student';
            $qrPng = $this->buildStudentQrPng($student);
            $zip->addFromString($fileName . '-qr.png', $qrPng);
        }

        $zip->close();

        return response()->download($zipFile, 'student-qr-codes.zip')->deleteFileAfterSend(true);
    }

    public function studentQrCode(User $student)
    {
        abort_if(!$student->isStudent(), 404);

        $svg = $this->buildStudentQrSvg($student);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    private function buildStudentQrSvg(User $student): string
    {
        $qrCode = QRCode::where('student_id', $student->id)->first();

        $qrData = json_encode([
            'type' => 'student_attendance',
            'student_id' => $student->id,
            'student_name' => $student->name,
            'student_email' => $student->email,
            'uuid' => $qrCode?->uuid ?? \Illuminate\Support\Str::uuid()->toString(),
            'generated_at' => now()->toIso8601String(),
        ]);

        return QrCodeFacade::format('svg')
            ->size(180)
            ->margin(1)
            ->encoding('UTF-8')
            ->generate($qrData);
    }

    // Attendance Management
    public function attendanceRecords(): View
    {
        $records = AttendanceRecord::with('student', 'classe')
            ->paginate(20);
        
        $attendanceStats = AttendanceRecord::selectRaw(
            'COUNT(*) as total_records, 
             SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) as present_count,
             SUM(CASE WHEN status = \'late\' THEN 1 ELSE 0 END) as late_count,
             SUM(CASE WHEN status = \'absent\' THEN 1 ELSE 0 END) as absent_count,
             SUM(CASE WHEN status = \'excused\' THEN 1 ELSE 0 END) as excused_count'
        )->first();
        
        $totalRecords = (int) ($attendanceStats?->total_records ?? 0);
        $presentCount = (int) ($attendanceStats?->present_count ?? 0);
        $lateCount = (int) ($attendanceStats?->late_count ?? 0);
        $absentCount = (int) ($attendanceStats?->absent_count ?? 0);
        $excusedCount = (int) ($attendanceStats?->excused_count ?? 0);
        
        return view('admin.attendance-records-new', [
            'attendanceRecords' => $records,
            'totalRecords' => $totalRecords,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'excusedCount' => $excusedCount,
        ]);
    }

    // Reports
    public function reports(): View
    {
        // Optimize: Get all attendance stats in a single query
        $attendanceStats = AttendanceRecord::selectRaw(
            'COUNT(*) as total_records, 
               SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) as present_count,
               SUM(CASE WHEN status = \'late\' THEN 1 ELSE 0 END) as late_count,
               SUM(CASE WHEN status = \'absent\' THEN 1 ELSE 0 END) as absent_count'
        )->first();

        $totalStudents = User::where('role', 'student')->count();

        $topClasses = Classe::with('professor')
            ->withCount([
                'attendanceRecords as total_records',
                'attendanceRecords as present_records' => function ($query) {
                    $query->where('status', 'present');
                },
            ])
            ->orderByDesc('present_records')
            ->limit(5)
            ->get();

        return view('admin.reports', [
            'totalStudents' => $totalStudents,
            'totalRecords' => $attendanceStats->total_records,
            'presentCount' => $attendanceStats->present_count,
            'lateCount' => $attendanceStats->late_count,
            'absentCount' => $attendanceStats->absent_count,
            'topClasses' => $topClasses,
        ]);
    }

    // Drop approvals removed: professors handle drops directly now.

    // System Logs
    public function logs(): View
    {
        // Cache logs for 1 minute
        $logs = Cache::remember('admin_logs_page_' . request('page', 1), 60, function () {
            return SystemLog::with('user')->latest()->paginate(20);
        });
        return view('admin.logs-new', ['logs' => $logs]);
    }

    // Settings
    public function settings(): View
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // If only theme is being updated, validate and save theme
        if ($request->has('theme') && !$request->has('name') && !$request->has('email')) {
            $request->validate([
                'theme' => 'required|in:light,ash,dark,onyx',
            ]);
            $user->theme = $request->input('theme');
            $user->save();
            return back()->with('success', 'Theme updated successfully.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'theme' => 'nullable|in:light,ash,dark,onyx',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['theme'])) {
            $user->theme = $validated['theme'];
        }
        $user->save();

        // Log the activity
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => Auth::id(),
            'description' => 'Updated profile settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            Auth::user()->update([
                'password' => bcrypt($validated['password']),
            ]);

            // Log the activity
            SystemLog::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'model_type' => 'User',
                'model_id' => Auth::id(),
                'description' => 'Changed password',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Password updated successfully.');
        }

        return back()->with('info', 'No password change made.');
    }
}