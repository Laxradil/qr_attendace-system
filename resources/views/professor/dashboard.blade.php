@extends('layouts.professor')

@section('title', 'Dashboard - Professor')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->name . '. Here is your class activity overview.')

@section('content')
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('professor.classes') }}">
        <div class="stat-val">{{ $totalClasses }}</div>
        <div class="stat-label">Total Classes</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View classes -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('professor.students') }}">
        <div class="stat-val">{{ $totalStudents }}</div>
        <div class="stat-label">Students</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View students -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('professor.attendance-records') }}">
        <div class="stat-val">{{ $totalRecords }}</div>
        <div class="stat-label">Attendance Records</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View records -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('professor.reports') }}">
        <div class="stat-val">{{ $attendanceRate }}%</div>
        <div class="stat-label">Attendance Rate</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View reports -></div>
    </a>
</div>

<div class="g-6-4">
    <div>
        <div class="sh">Attendance Overview</div>
        <div class="card">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--green);">{{ $presentCount }}</div>
                    <div class="stat-label">Present</div>
                </div>
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--amber);">{{ $lateCount }}</div>
                    <div class="stat-label">Late</div>
                </div>
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--red);">{{ $absentCount }}</div>
                    <div class="stat-label">Absent</div>
                </div>
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--blue);">{{ $totalRecords }}</div>
                    <div class="stat-label">Total Records</div>
                </div>
            </div>
            <a class="btn btn-sm" href="{{ route('professor.attendance-records') }}" style="width:100%;justify-content:center;margin-top:8px;">Manage Attendance -></a>
        </div>

        <div class="sh">Recent Activities</div>
        <div class="card">
            @forelse($recentLogs as $log)
                <div style="display:flex;gap:9px;padding:7px 0;border-bottom:1px solid var(--border2);align-items:flex-start;">
                    <div style="width:26px;height:26px;border-radius:6px;background:var(--purple-glow);display:flex;align-items:center;justify-content:center;font-size:10px;">{{ strtoupper(substr($log->action, 0, 1)) }}</div>
                    <div style="font-size:11px;flex:1;">
                        <strong>{{ $log->user->name ?? 'System' }}</strong> {{ str_replace('_', ' ', $log->action) }}
                        @if($log->description)
                            <div style="font-size:10px;color:var(--text2);margin-top:1px;">{{ $log->description }}</div>
                        @endif
                    </div>
                    <div style="font-size:9px;color:var(--text3);white-space:nowrap;font-family:'JetBrains Mono',monospace;">{{ $log->created_at?->format('h:i A') }}</div>
                </div>
            @empty
                <div style="color:var(--text2);font-size:11px;">No recent activity.</div>
            @endforelse
            <a class="btn btn-sm" href="{{ route('professor.logs') }}" style="width:100%;justify-content:center;margin-top:6px;">View All Activities -></a>
        </div>
    </div>

    <div>
        <div class="sh">Today's Schedule</div>
        <div class="card">
            @forelse($todaySchedules as $schedule)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);gap:12px;">
                    <div>
                        <div style="font-size:11px;font-weight:600;">{{ $schedule->subject_name }}</div>
                        <div style="font-size:10px;color:var(--text2);">{{ $schedule->subject_code }} · Room {{ $schedule->room }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:11px;font-weight:700;">{{ $schedule->time }}</div>
                        <div style="font-size:9px;color:var(--text3);">{{ $schedule->days }}</div>
                    </div>
                </div>
            @empty
                <div style="color:var(--text2);font-size:11px;">No classes scheduled for today.</div>
            @endforelse
        </div>

        <div class="sh">Quick Actions</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <a class="btn" href="{{ route('professor.scan-qr') }}" style="justify-content:center;">Scan QR</a>
            <a class="btn" href="{{ route('professor.attendance-records') }}" style="justify-content:center;">Attendance</a>
            <a class="btn" href="{{ route('professor.classes') }}" style="justify-content:center;">My Classes</a>
            <a class="btn" href="{{ route('professor.reports') }}" style="justify-content:center;">Reports</a>
        </div>
    </div>
</div>

<div style="margin-top:14px;background:linear-gradient(135deg,var(--purple2),var(--blue));border-radius:var(--radius-lg);padding:14px 18px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:12px;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <div>
            <div style="font-weight:600;color:#fff;font-size:12px;">Ready to take attendance?</div>
            <div style="font-size:10px;color:rgba(255,255,255,.7);">Scan a student QR code and record attendance instantly.</div>
        </div>
    </div>
    <a class="btn" href="{{ route('professor.scan-qr') }}" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.25);color:#fff;">Start Scanning →</a>
</div>
@endsection
