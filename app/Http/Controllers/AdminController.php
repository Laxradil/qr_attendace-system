<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classe;
use App\Models\QRCode;
use App\Models\AttendanceRecord;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

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

            // Get QR codes and today records
            $activeQRCodes = QRCode::where('is_used', false)->count();
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
                'activeQRCodes' => $activeQRCodes,
                'todayRecords' => $todayRecords,
            ];
        });

        // Cache recent logs for 1 minute
        $recentLogs = Cache::remember('admin_recent_logs', 60, function () {
            return SystemLog::with('user')->latest()->limit(10)->get();
        });

        return view('admin.dashboard', array_merge($stats, ['recentLogs' => $recentLogs]));
    }

    // Users Management

    public function users(): View
    {
        // Cache users for 1 minute
        $users = Cache::remember('admin_users_page_' . request('page', 1), 60, function () {
            return User::with('classes', 'attendanceRecords')->paginate(20);
        });
        return view('admin.users', ['users' => $users]);
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

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
        ]);

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'other',
            'description' => 'Created new user: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

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
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
            'is_active' => $validated['is_active'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
        ]);

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_user',
            'description' => 'Updated user: ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user): RedirectResponse
    { 
         $name = $user->name; 
         $user->delete();

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_user',
            'description' => 'Deleted user: ' . $name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    // Professors Management

    public function professors(): View
    {
        // Cache professors for 1 minute
        $professors = Cache::remember('admin_professors_page_' . request('page', 1), 60, function () {
            return User::with('classes')->where('role', 'professor')->paginate(20);
        });
        return view('admin.professors', ['professors' => $professors]);
    }

    // Students Management

    public function students(): View
    {
        // Cache students for 1 minute
        $students = Cache::remember('admin_students_page_' . request('page', 1), 60, function () {
            return User::with('attendanceRecords', 'enrolledClasses')->where('role', 'student')->paginate(20);
        });
        return view('admin.students', ['students' => $students]);
    }

    // Classes Management
    public function classes(): View
    {
        // Cache classes for 1 minute
        $classes = Cache::remember('admin_classes_page_' . request('page', 1), 60, function () {
            return Classe::with('professor', 'students')->paginate(20);
        });
        return view('admin.classes', ['classes' => $classes]);
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
            'professor_id' => 'required|exists:users,id',
        ]);

        Classe::create($validated);

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_class',
            'description' => 'Created class: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.classes')->with('success', 'Class created successfully');
    }

    public function editClass(Classe $classe): View
    {
        $professors = User::where('role', 'professor')->get();
        return view('admin.edit-class', ['classe' => $classe, 'professors' => $professors]);
    }

    public function updateClass(Request $request, Classe $classe): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classes,code,' . $classe->id . '|max:20',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'professor_id' => 'required|exists:users,id',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Cache::forget('admin_classes_page_1');
        $classe->update($validated);

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_class',
            'description' => 'Updated class: ' . $classe->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.classes')->with('success', 'Class updated successfully');
    }

    public function deleteClass(Classe $classe): RedirectResponse
    {
        $name = $classe->name;
        $classe->delete();

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_class',
            'description' => 'Deleted class: ' . $name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.classes')->with('success', 'Class deleted successfully');
    }

    // QR Code Management
    public function qrCodes(): View
{
    // Add 'professor' if you display who generated it
    $qrCodes = QRCode::with(['classe', 'professor'])->paginate(20);
    $classes = Classe::orderBy('name')->get();

    return view('admin.qr-codes', compact('qrCodes', 'classes'));
}

    public function qrCodeImage(string $uuid): \Illuminate\Http\Response
{
    $qrCode = QRCode::where('uuid', $uuid)->first();
    
    if (!$qrCode) {
        return response('QR Code not found', 404);
    }
    
    // Generate QR code as SVG with real data
    $qrData = json_encode([
        'type' => 'class_qr',
        'uuid' => $qrCode->uuid,
        'class_id' => $qrCode->class_id,
        'class_name' => $qrCode->classe->name ?? '',
        'class_code' => $qrCode->classe->code ?? '',
    ]);
    
    $svg = $this->generateQRCodeSVG($qrData);
    
    return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
}

    private function generateQRCodeSVG(string $data): string
{
    // Simple QR code SVG generation
    // Using a basic QR code pattern for the UUID
    $size = 100;
    $moduleSize = 2;
    $padding = 10;
    
    // Generate a simple QR-like pattern based on the data
    $hash = md5($data);
    $pattern = [];
    
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

    public function generateQRCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'count' => 'required|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:today',
        ]);

        for ($i = 0; $i < $validated['count']; $i++) {
            QRCode::create([
                'uuid' => Str::uuid(),
                'class_id' => $validated['class_id'],
                'professor_id' => auth()->id(),
                'expires_at' => $validated['expires_at'] ?? null,
            ]);
        }

        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'generate_qr',
            'description' => 'Generated ' . $validated['count'] . ' QR codes for class',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', $validated['count'] . ' QR codes generated successfully');
    }

    // Attendance Management
    public function attendanceRecords(): View
    {
        $records = AttendanceRecord::with('student', 'classe')
            ->paginate(20);
        return view('admin.attendance-records', ['records' => $records]);
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

    // System Logs
    public function logs(): View
    {
        // Cache logs for 1 minute
        $logs = Cache::remember('admin_logs_page_' . request('page', 1), 60, function () {
            return SystemLog::with('user')->latest()->paginate(20);
        });
        return view('admin.logs', ['logs' => $logs]);
    }
}