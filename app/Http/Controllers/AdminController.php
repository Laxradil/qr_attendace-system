<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classe;
use App\Models\AttendanceRecord;
use App\Models\DropRequest;
use App\Models\SystemLog;
use App\Models\QRCode;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        // Get pending drop requests count
        $dropRequests = DropRequest::where('status', 'pending')->count();

        return view('admin.dashboard-new', array_merge($stats, ['recentLogs' => $recentLogs, 'dropRequests' => $dropRequests]));
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
            'role' => 'required|in:admin,professor,student',
            'student_id' => 'nullable|string|unique:users|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
        ]);

        // Create QR code for students
        if ($validated['role'] === 'student') {
            QRCode::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'student_id' => $user->id,
            ]);
        }

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'other',
            'description' => 'Created new user: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin_user_stats');
        Cache::forget('admin_stats');

        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    public function editUser(User $user): View
    {
        return view('admin.edit-user', ['user' => $user]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,professor,student',
            'student_id' => 'nullable|string|unique:users,student_id,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
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

        Cache::forget('admin_user_stats');
        Cache::forget('admin_stats');

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'professors' => 'required|array|min:1',
            'professors.*' => 'required|exists:users,id',
        ]);

        $professors = $validated['professors'];
        $primaryProfessorId = $professors[0];

        $classe = Classe::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'professor_id' => $primaryProfessorId,
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
        $classe->load('professors');
        $professors = User::where('role', 'professor')->get();
        return view('admin.edit-class', ['classe' => $classe, 'professors' => $professors]);
    }

    public function updateClass(Request $request, Classe $classe): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classes,code,' . $classe->id . '|max:20',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'professors' => 'required|array|min:1',
            'professors.*' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $professors = $validated['professors'];
        $primaryProfessorId = $professors[0];

        $classe->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'professor_id' => $primaryProfessorId,
            'is_active' => $validated['is_active'] ?? false,
        ]);

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

        $classe->students()->syncWithoutDetaching($studentIds);

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

    public function studentQrCode(User $student)
    {
        abort_if(!$student->isStudent(), 404);

        $qrCode = QRCode::where('student_id', $student->id)->first();

        $qrData = json_encode([
            'type' => 'student_attendance',
            'student_id' => $student->id,
            'student_name' => $student->name,
            'student_email' => $student->email,
            'uuid' => $qrCode?->uuid ?? \Illuminate\Support\Str::uuid()->toString(),
            'generated_at' => now()->toIso8601String(),
        ]);

        $svg = QrCodeFacade::format('svg')
            ->size(180)
            ->margin(1)
            ->encoding('UTF-8')
            ->generate($qrData);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    // Attendance Management
    public function attendanceRecords(): View
    {
        $records = AttendanceRecord::with('student', 'classe')
            ->paginate(20);
        $attendanceRecords = $records;
        return view('admin.attendance-records-new', ['attendanceRecords' => $attendanceRecords]);
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

    public function dropRequests(): View
    {
        $requests = DropRequest::with(['classe', 'student', 'professor', 'admin'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $dropRequests = $requests;
        return view('admin.drop-requests-new', [
            'dropRequests' => $dropRequests,
        ]);
    }

    public function approveDropRequest(Request $request, DropRequest $dropRequest): RedirectResponse
    {
        if ($dropRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $dropRequest->update([
            'status' => 'approved',
            'admin_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $dropRequest->classe->students()->detach($dropRequest->student_id);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'other',
            'description' => 'Approved drop request for ' . $dropRequest->student->name . ' from ' . $dropRequest->classe->code,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Drop request approved and student removed from the class.');
    }

    public function rejectDropRequest(Request $request, DropRequest $dropRequest): RedirectResponse
    {
        if ($dropRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $dropRequest->update([
            'status' => 'rejected',
            'admin_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'other',
            'description' => 'Rejected drop request for ' . $dropRequest->student->name . ' from ' . $dropRequest->classe->code,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Drop request rejected.');
    }

    // System Logs
    public function logs(): View
    {
        // Cache logs for 1 minute
        $logs = Cache::remember('admin_logs_page_' . request('page', 1), 60, function () {
            return SystemLog::with('user')->latest()->paginate(20);
        });
        return view('admin.logs-new', ['logs' => $logs]);
    }
}