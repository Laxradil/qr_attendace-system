@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard')
@section('subheader', "Welcome back, Admin! Here's what's happening in the system.")

@section('content')
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('admin.users') }}">
        <div class="stat-val">{{ $totalUsers }}</div>
        <div class="stat-label">Total Users</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('admin.professors') }}">
        <div class="stat-val">{{ $totalProfessors }}</div>
        <div class="stat-label">Professors</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('admin.students') }}">
        <div class="stat-val">{{ $totalStudents }}</div>
        <div class="stat-label">Students</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('admin.classes') }}">
        <div class="stat-val">{{ $totalClasses }}</div>
        <div class="stat-label">Classes</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
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
                    <div class="stat-val" style="font-size:18px;color:var(--blue);">{{ $totalAttendance }}</div>
                    <div class="stat-label">Total Records</div>
                </div>
            </div>
            <a class="btn btn-sm" href="{{ route('admin.reports') }}" style="width:100%;justify-content:center;margin-top:8px;">View Full Report -></a>
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
            <a class="btn btn-sm" href="{{ route('admin.logs') }}" style="width:100%;justify-content:center;margin-top:6px;">View All Activities -></a>
        </div>
    </div>

    <div>
        <div class="sh">System Overview</div>
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);"><span style="font-size:11px;">Active QR Codes</span><span style="font-size:13px;font-weight:700;color:var(--green);">{{ $activeQRCodes }}</span></div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);"><span style="font-size:11px;">Today's Records</span><span style="font-size:13px;font-weight:700;">{{ $todayRecords }}</span></div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);"><span style="font-size:11px;">Total Classes</span><span style="font-size:13px;font-weight:700;">{{ $totalClasses }}</span></div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;"><span style="font-size:11px;">System Status</span><span class="badge bg">All Systems Operational</span></div>
        </div>

        <div class="sh">Quick Actions</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <a class="btn" href="{{ route('admin.users.create') }}" style="justify-content:center;">Add User</a>
            <a class="btn" href="{{ route('admin.classes.create') }}" style="justify-content:center;">Add Class</a>
            <a class="btn" href="{{ route('admin.students') }}" style="justify-content:center;">Manage Students</a>
            <a class="btn" href="{{ route('admin.qr-codes') }}" style="justify-content:center;">Generate QR</a>
        </div>
    </div>
</div>
@endsection
