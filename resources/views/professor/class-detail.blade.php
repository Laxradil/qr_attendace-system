@extends('layouts.professor')

@section('title', $classe->name . ' - Class Detail')
@section('header', $classe->name)
@section('subheader', 'View class details, students, and schedules')

@section('content')
<div class="content">
    <!-- Class Header -->
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <div style="font-size:18px;font-weight:700;margin-bottom:2px;">{{ $classe->name }}</div>
                <div style="font-size:11px;color:var(--text2);font-family:'JetBrains Mono',monospace;">{{ $classe->code }}</div>
                @if($classe->description)
                    <div style="font-size:11px;color:var(--text);margin-top:8px;line-height:1.4;">{{ $classe->description }}</div>
                @endif
            </div>
            <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:12px;">
        <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $classe->students->count() }}</div><div class="stat-label">Students</div></div></div>
        <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val">{{ $classe->schedules->count() }}</div><div class="stat-label">Schedules</div></div></div>
        <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val">{{ $classe->attendanceRecords()->count() }}</div><div class="stat-label">Records</div></div></div>
    </div>

    <!-- Students List -->
    <div style="margin-bottom:12px;">
        <div class="sh">Enrolled Students ({{ $classe->students->count() }})</div>
        <div class="tbl-wrap">
            <table>
                <thead><tr><th>Student ID</th><th>Name</th><th>Email</th><th>Enrolled</th></tr></thead>
                <tbody>
                    @forelse($classe->students as $student)
                        <tr>
                            <td class="td-mono">{{ $student->student_id ?? 'N/A' }}</td>
                            <td>{{ $student->name }}</td>
                            <td style="color:var(--text2);">{{ $student->email }}</td>
                            <td style="color:var(--text2);">{{ $student->pivot->enrolled_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:var(--text2);">No students enrolled yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Schedules -->
    @if($classe->schedules->count() > 0)
        <div>
            <div class="sh">Class Schedules ({{ $classe->schedules->count() }})</div>
            <div class="tbl-wrap">
                <table>
                    <thead><tr><th>Days</th><th>Time</th><th>Room</th><th>Professor</th></tr></thead>
                    <tbody>
                        @foreach($classe->schedules as $schedule)
                            <tr>
                                <td style="font-weight:600;">{{ $schedule->days }}</td>
                                <td>{{ $schedule->time }}</td>
                                <td style="text-align:center;">{{ $schedule->room }}</td>
                                <td style="color:var(--text2);">{{ $schedule->professor }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
