@extends('layouts.admin-new')

@section('title', 'Dashboard - QR Attendance Admin')
@section('pageTitle', 'Dashboard')
@section('pageSubtitle', "Welcome back, Admin! Here's what's happening in the system.")

@section('content')
@php
  $attendanceTotal = App\Models\AttendanceRecord::count();
  $attendancePresent = App\Models\AttendanceRecord::where('status', 'present')->count();
  $attendanceProgress = $attendanceTotal > 0 ? ($attendancePresent / $attendanceTotal) * 100 : 0;
@endphp

<style>
  .dashboard-stats{
    gap:12px;
  }
  .dashboard-stats .stat{
    border:none;
    background:transparent;
    box-shadow:none;
    backdrop-filter:none;
    -webkit-backdrop-filter:none;
    padding:16px;
    cursor:pointer;
    transition:transform .25s ease, background .25s ease, box-shadow .25s ease, border-color .25s ease, padding .25s ease;
  }
  .dashboard-stats .stat:hover{
    transform:translateY(-3px);
    padding:22px;
    border:1px solid rgba(255,255,255,.22);
    background:linear-gradient(135deg,rgba(255,255,255,.1),rgba(255,255,255,.03) 40%,rgba(255,255,255,.07));
    backdrop-filter:var(--blur);
    -webkit-backdrop-filter:var(--blur);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.2),0 24px 60px rgba(0,0,0,.4);
    z-index:10;
  }
  .dashboard-stats .stat-body a,
  .dashboard-stats .stat-body .trend{
    opacity:0;
    max-height:0;
    overflow:hidden;
    margin-top:0;
    transform:translateY(-4px);
    transition:opacity .2s ease, max-height .2s ease, margin-top .2s ease, transform .2s ease;
    pointer-events:none;
  }
  .dashboard-stats .stat:hover .stat-body a,
  .dashboard-stats .stat:hover .stat-body .trend{
    opacity:1;
    max-height:40px;
    margin-top:6px;
    transform:none;
    pointer-events:auto;
  }
  .attendance-panel .report-btn{
    display:flex;
    align-items:center;
    justify-content:center;
    width:100%;
    height:50px;
    border-radius:18px;
    font-size:15px;
    font-weight:800;
    letter-spacing:.01em;
    background:linear-gradient(90deg,#8f5bff 0%,#5d63ff 45%,#2d68b8 100%);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.22),0 10px 26px rgba(78,88,255,.28);
    transition:transform .2s ease, box-shadow .2s ease, filter .2s ease;
    text-decoration:none;
    color:#fff;
  }
  .attendance-panel .report-btn:hover{
    transform:translateY(-2px);
    filter:saturate(1.05);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.22),0 14px 34px rgba(78,88,255,.36);
  }
  .quick-grid .quick div{
    color:#fff;
  }
</style>

<div class="stats dashboard-stats">
  <div class="stat">
    <div class="stat-icon blue">👥</div>
    <div class="stat-body">
      <strong>{{ App\Models\User::count() }}</strong>
      <span>Total Users</span>
      <div class="trend up">↑ {{ App\Models\User::where('created_at', '>=', now()->subWeek())->count() }} this week</div>
      <a href="{{ route('admin.users') }}">View all →</a>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon green">🎓</div>
    <div class="stat-body">
      <strong>{{ App\Models\User::where('role', 'professor')->count() }}</strong>
      <span>Professors</span>
      <div class="trend up">↑ All active</div>
      <a href="{{ route('admin.professors') }}">View all →</a>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon yellow">🧑‍🎓</div>
    <div class="stat-body">
      <strong>{{ App\Models\User::where('role', 'student')->count() }}</strong>
      <span>Students</span>
      <div class="trend up">↑ {{ App\Models\User::where('role', 'student')->where('created_at', '>=', now()->subWeek())->count() }} new</div>
      <a href="{{ route('admin.students') }}">View all →</a>
    </div>
  </div>
  <div class="stat">
    <div class="stat-icon purple">📘</div>
    <div class="stat-body">
      <strong>{{ App\Models\Classe::count() }}</strong>
      <span>Classes</span>
      <div class="trend up">↑ All active</div>
      <a href="{{ route('admin.classes') }}">View all →</a>
    </div>
  </div>
</div>

<div class="dashboard">
  <div style="display:grid;gap:16px">
    <!-- Attendance overview -->
    <div class="card glass attendance-panel">
      <div class="section-head">
        <h3>📊 Attendance Overview</h3>
        <a href="{{ route('admin.attendance-records') }}">View Full Report →</a>
      </div>
      <div class="mini-grid">
        <div class="mini">
          <div class="mini-icon stat-icon green" style="width:38px;height:38px;border-radius:12px;font-size:16px">✓</div>
          <div><b style="color:#4dffa0">{{ App\Models\AttendanceRecord::where('status', 'present')->count() }}</b><small>Present</small></div>
        </div>
        <div class="mini">
          <div class="mini-icon stat-icon yellow" style="width:38px;height:38px;border-radius:12px;font-size:16px">◷</div>
          <div><b style="color:#ffd584">{{ App\Models\AttendanceRecord::where('status', 'late')->count() }}</b><small>Late</small></div>
        </div>
        <div class="mini">
          <div class="mini-icon stat-icon red" style="width:38px;height:38px;border-radius:12px;font-size:16px">✕</div>
          <div><b style="color:#ff7f96">{{ App\Models\AttendanceRecord::where('status', 'absent')->count() }}</b><small>Absent</small></div>
        </div>
        <div class="mini">
          <div class="mini-icon stat-icon blue" style="width:38px;height:38px;border-radius:12px;font-size:16px">▤</div>
          <div><b>{{ App\Models\AttendanceRecord::count() }}</b><small>Total</small></div>
        </div>
      </div>
      <a href="{{ route('admin.attendance-records') }}" class="report-btn">View Full Attendance Report →</a>
    </div>

    <!-- Recent activities -->
    <div class="card glass">
      <div class="section-head">
        <h3>⚡ Recent Activities</h3>
        <a href="{{ route('admin.logs') }}">View All →</a>
      </div>
      @forelse(App\Models\SystemLog::latest()->take(4)->get() as $log)
      <div class="activity">
        <div class="act-icon @if($log->action == 'create') create @elseif($log->action == 'update') edit @elseif($log->action == 'delete') drop @elseif($log->action == 'scan_qr') scan @else add @endif">
          @if($log->action == 'create') ✨ @elseif($log->action == 'update') ✏️ @elseif($log->action == 'delete') 🗑 @elseif($log->action == 'scan_qr') 📷 @else ➕ @endif
        </div>
        <div><b>{{ $log->user->name }} {{ ucwords(str_replace('_', ' ', $log->action)) }}</b><p>{{ $log->description }}</p></div>
        <time>{{ $log->created_at->format('h:i A') }}</time>
      </div>
      @empty
      <p style="color:var(--muted);padding:20px;text-align:center">No recent activities</p>
      @endforelse
    </div>
  </div>

  <div class="right-stack">
    <!-- System overview -->
    <div class="card glass system-overview-card">
      <div class="section-head"><h3>🖥 System Overview</h3></div>
      <div class="row-item">
        <span>Today's Records</span>
        <b>{{ App\Models\AttendanceRecord::whereDate('created_at', today())->count() }}</b>
      </div>
      <div class="row-item">
        <span>Total Classes</span>
        <b>{{ App\Models\Classe::count() }}</b>
      </div>
      <div class="row-item">
        <span>Pending Drops</span>
        <b style="color:#ffd584">{{ App\Models\DropRequest::where('status', 'pending')->count() }}</b>
      </div>
      <div class="row-item">
        <span>System Status</span>
        <span class="pill green"><span class="status-dot" style="color:var(--green);background:var(--green)"></span> Operational</span>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card glass">
      <div class="section-head"><h3>🚀 Quick Actions</h3></div>
      <div class="quick-grid">
        <a href="{{ route('admin.users.create') }}" class="quick">
          <div class="mini-icon stat-icon blue" style="width:40px;height:40px;border-radius:13px;font-size:18px">＋</div>
          <div><strong>Add User</strong><span>Create an account</span></div>
        </a>
        <a href="{{ route('admin.classes.create') }}" class="quick">
          <div class="mini-icon stat-icon purple" style="width:40px;height:40px;border-radius:13px;font-size:18px">▣</div>
          <div><strong>Add Class</strong><span>New class section</span></div>
        </a>
        <a href="{{ route('admin.students') }}" class="quick">
          <div class="mini-icon stat-icon green" style="width:40px;height:40px;border-radius:13px;font-size:18px">👥</div>
          <div><strong>Students</strong><span>View records</span></div>
        </a>
        <a href="{{ route('admin.qr-codes') }}" class="quick">
          <div class="mini-icon stat-icon yellow" style="width:40px;height:40px;border-radius:13px;font-size:18px">▦</div>
          <div><strong>QR Codes</strong><span>Manage codes</span></div>
        </a>
      </div>
    </div>

    <!-- Drop requests alert -->
    @php
      $pendingDropCount = App\Models\DropRequest::where('status', 'pending')->count();
      $firstPendingDrop = App\Models\DropRequest::where('status', 'pending')->with('student', 'classe')->first();
    @endphp
    <div class="card" style="border-radius:var(--radius-lg);padding:18px;background:rgba(255,61,114,.08);border:1px solid rgba(255,61,114,.22)">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
        <span style="font-size:20px">⚠️</span>
        <b style="font-size:14px">{{ $pendingDropCount }} Pending Drop Request(s)</b>
      </div>
      @if($pendingDropCount > 0)
        <p style="color:var(--muted);font-size:13px;margin-bottom:13px">{{ $firstPendingDrop?->student?->name ?? 'N/A' }} · {{ $firstPendingDrop?->classe?->code ?? 'N/A' }} · {{ now()->format('M d') }}</p>
      @else
        <p style="color:var(--muted);font-size:13px;margin-bottom:13px">No pending drop requests at this time</p>
      @endif
      <div style="display:flex;gap:8px">
        <a href="{{ route('admin.drop-requests') }}" class="btn primary slim" style="flex:1">Review →</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.querySelectorAll('.attendance-fill').forEach((bar) => {
    bar.style.width = `${bar.dataset.progress || 0}%`;
  });
</script>
@endsection
