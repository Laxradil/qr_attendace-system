<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Only admins can access schedule management
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $schedules = Schedule::orderBy('subject_code')->get();
        $professors = User::where('role', 'professor')->get();

        return view('schedule', compact('schedules', 'professors'));
    }

    public function store(Request $request)
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

    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $request->validate([
            'subject_code' => 'required|string|max:20',
            'subject_name' => 'required|string|max:255',
            'days' => 'required|string|max:20',
            'time' => 'required|string|max:50',
            'room' => 'required|string|max:20',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Schedule deleted successfully!');
    }
}