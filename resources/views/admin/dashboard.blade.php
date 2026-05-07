@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard')
@section('subheader', "Welcome back, Admin! Here's what's happening in the system.")

@section('content')
<div class="stats stats-4">
  <a href="{{ route('admin.users') }}" class="stat glass">
    <div class="stat-icon blue">👥</div>
    <div class="stat-body">
      <strong>{{ $totalUsers }}</strong>
      <span>Total Users</span>
      <div class="trend up">↑ Managing system users</div>
    </div>
  </a>
  <a href="{{ route('admin.professors') }}" class="stat glass">
    <div class="stat-icon purple">🎓</div>
    <div class="stat-body">
      <strong>{{ $totalProfessors }}</strong>
      <span>Professors</span>
      <div class="trend up">↑ Teaching staff</div>
    </div>
  </a>
  <a href="{{ route('admin.students') }}" class="stat glass">
    <div class="stat-icon yellow">🧑‍🎓</div>
    <div class="stat-body">
      <strong>{{ $totalStudents }}</strong>
      <span>Students</span>
      <div class="trend up">↑ Enrolled students</div>
    </div>
  </a>
  <a href="{{ route('admin.classes') }}" class="stat glass">
    <div class="stat-icon green">📘</div>
    <div class="stat-body">
      <strong>{{ $totalClasses }}</strong>
      <span>Classes</span>
      <div class="trend up">↑ Active sections</div>
    </div>
  </a>
</div>

<div class="dashboard">
  <div class="dash-left">
    <!-- Attendance overview -->
    <div class="card glass">
      <div class="section-head">
        <h3>📊 Attendance Overview</h3>
        <a href="{{ route('admin.reports') }}">View Full Report →</a>
      </div>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:10px;">
        <div>
          <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;color:#4dffa0;">{{ $presentCount }}</div>
          <div style="font-size:11px;color:var(--muted);margin-top:2px;">Present</div>
        </div>
        <div>
          <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;color:#ffd584;">{{ $lateCount }}</div>
          <div style="font-size:11px;color:var(--muted);margin-top:2px;">Late</div>
        </div>
        <div>
          <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;color:#ff7f96;">{{ $absentCount }}</div>
          <div style="font-size:11px;color:var(--muted);margin-top:2px;">Absent</div>
        </div>
        <div>
          <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;">{{ $totalAttendance }}</div>
          <div style="font-size:11px;color:var(--muted);margin-top:2px;">Total</div>
        </div>
      </div>
      <!-- Attendance bar -->
      <div style="height:6px;border-radius:99px;background:rgba(255,255,255,.1);overflow:hidden;margin-bottom:10px;flex-shrink:0">
        <div style="height:100%;width:{{ $totalAttendance > 0 ? ($presentCount / $totalAttendance * 100) : 0 }}%;background:linear-gradient(90deg,var(--green),var(--blue));border-radius:99px;box-shadow:0 0 10px rgba(24,240,139,.5)"></div>
      </div>
      <a href="{{ route('admin.attendance-records') }}" class="btn primary" style="width:100%;justify-content:center;">View Full Attendance Report →</a>
    </div>

    <!-- Recent activities -->
    <div class="card glass stretch">
      <div class="section-head">
        <h3>⚡ Recent Activities</h3>
        <a href="{{ route('admin.logs') }}">View All →</a>
      </div>
      <div style="flex:1;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent;">
        @forelse($recentLogs as $log)
          <div style="display:flex;gap:10px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.06);align-items:flex-start;">
            <div style="width:34px;height:34px;border-radius:11px;display:grid;place-items:center;font-size:14px;background:rgba(139,92,255,.15);border:1px solid rgba(139,92,255,.2);flex-shrink:0;">
              {{ $log->action === 'ADD_STUDENT' ? '➕' : ($log->action === 'UPDATE_USER' ? '✏️' : ($log->action === 'SCAN_QR' ? '📷' : '⚙️')) }}
            </div>
            <div style="flex:1;min-width:0;">
              <b style="font-size:13px;font-weight:700;display:block;">{{ $log->user->name ?? 'System' }} {{ str_replace('_', ' ', $log->action) }}</b>
              @if($log->description)
                <p style="margin:2px 0 0;color:var(--muted);font-size:11.5px;line-height:1.3;">{{ $log->description }}</p>
              @endif
            </div>
            <time style="font-size:11px;color:var(--faint);font-variant-numeric:tabular-nums;white-space:nowrap;font-family:var(--mono);">{{ $log->created_at?->tz('UTC')->setTimezone('Asia/Manila')->diffForHumans() }}</time>
          </div>
        @empty
          <div style="color:var(--muted);font-size:11.5px;">No recent activity.</div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="dash-right">
    <!-- System overview -->
    <div class="card glass">
      <div class="section-head"><h3>🖥 System Overview</h3></div>
      <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.055);border-radius:12px;padding:9px 12px;margin-bottom:6px;font-weight:700;font-size:13px;">
        <span>Today's Records</span>
        <b>{{ $todayRecords }}</b>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.055);border-radius:12px;padding:9px 12px;margin-bottom:6px;font-weight:700;font-size:13px;">
        <span>Total Classes</span>
        <b>{{ $totalClasses }}</b>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.055);border-radius:12px;padding:9px 12px;">
        <span>System Status</span>
        <span class="pill green"><span class="status-dot" style="color:var(--green);background:var(--green)"></span> Operational</span>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card glass">
      <div class="section-head"><h3>🚀 Quick Actions</h3></div>
      <div style="display:grid;grid-template-columns:1fr;gap:8px;">
        <a href="{{ route('admin.users.create') }}" class="btn primary" style="justify-content:center;gap:6px;"><span>➕</span> <span>Add User</span></a>
        <a href="{{ route('admin.classes.create') }}" class="btn primary" style="justify-content:center;gap:6px;"><span>📘</span> <span>Add Class</span></a>
        <a href="{{ route('admin.students') }}" class="btn" style="justify-content:center;gap:6px;"><span>👥</span> <span>Students</span></a>
        <a href="{{ route('admin.qr-codes') }}" class="btn" style="justify-content:center;gap:6px;"><span>▦</span> <span>QR Codes</span></a>
      </div>
    </div>

    <!-- Alerts -->
    @if($dropRequests > 0)
      <div class="card glass" style="border-radius:var(--radius-lg);padding:18px;background:rgba(255,61,114,.08);border:1px solid rgba(255,61,114,.22)">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
          <span style="font-size:20px">⚠️</span>
          <b style="font-size:14px">{{ $dropRequests }} Pending Drop Request</b>
        </div>
        <p style="color:var(--muted);font-size:13px;margin-bottom:13px">Review and approve or reject drop requests</p>
        <div style="display:flex;gap:8px">
          <a href="{{ route('admin.drop-requests') }}" class="btn primary slim" style="flex:1;justify-content:center;">Review →</a>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
