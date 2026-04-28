<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Schedule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Redirect non-admins
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        // Get counts
        $professorsCount = User::where('role', 'professor')->count();
        $studentsCount = User::where('role', 'student')->count();
        $schedulesCount = Schedule::count();
        $classesToday = Schedule::count(); // Placeholder

        // Get users and schedules
        $users = User::orderBy('name')->get();
        $schedules = Schedule::orderBy('subject_code')->get();
        $professors = User::where('role', 'professor')->get();

        return view('admin', compact(
            'professorsCount',
            'studentsCount',
            'schedulesCount',
            'classesToday',
            'users',
            'schedules',
            'professors'
        ));
    }

    public function accounts()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }
        $users = User::orderBy('name')->get();
        return view('admin.accounts', compact('users'));
    }

    public function schedules()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }
        $schedules = Schedule::orderBy('subject_code')->get();
        $professors = User::where('role', 'professor')->get();
        return view('admin.schedules', compact('schedules', 'professors'));
    }

    public function reports()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }
        return view('admin.reports');
    }

    public function createAccount(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,professor,student',
            'password' => 'required|string|min:6',
        ]);

        // Generate username from name if not provided
        $username = $request->username ?: strtolower(str_replace(' ', '_', $request->name));

        User::create([
            'name' => $request->name,
            'email' => $request->email ?? $username . '@example.com',
            'username' => $username,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Account created successfully!');
    }

    public function createSchedule(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $request->validate([
            'subject_code' => 'required|string|max:20',
            'subject_name' => 'required|string|max:255',
            'professor_id' => 'required|exists:users,id',
            'days' => 'required|string|max:20',
            'time' => 'required|string|max:50',
            'room' => 'required|string|max:20',
        ]);

        $professor = User::find($request->professor_id);

        Schedule::create([
            'subject_code' => $request->subject_code,
            'subject_name' => $request->subject_name,
            'professor_id' => $request->professor_id,
            'professor' => $professor->name,
            'days' => $request->days,
            'time' => $request->time,
            'room' => $request->room,
        ]);

        return redirect()->back()->with('success', 'Schedule created successfully!');
    }

    public function showUser($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }
        
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,professor,student',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
    }

    public function resetPassword(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $newPassword = substr(md5(time()), 0, 8);
        $user = User::findOrFail($id);
        $user->update(['password' => bcrypt($newPassword)]);

        return response()->json(['success' => true, 'new_password' => $newPassword]);
    }
}