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
    gap:10px;
  }
  .dashboard-stats .stat{
    border:none;
    background:transparent;
    box-shadow:none;
    backdrop-filter:none;
    -webkit-backdrop-filter:none;
    padding:12px 14px;
    cursor:pointer;
    transition:transform .25s ease, background .25s ease, box-shadow .25s ease, border-color .25s ease, padding .25s ease;
  }
  .dashboard-stats .stat:hover{
    transform:translateY(-3px);
    padding:18px;
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
    height:42px;
    border-radius:16px;
    font-size:14px;
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
  .attendance-panel .mini-grid{
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:10px;
    align-content:start;
    justify-content:stretch;
    margin-bottom:10px;
  }
  .attendance-panel .mini{
    aspect-ratio:1 / 1;
    min-height:144px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    gap:6px;
    padding:12px;
    background:rgba(255,255,255,.055);
    border-radius:12px;
    border:1px solid rgba(255,255,255,.10);
    transition:transform .2s ease, background .2s ease, box-shadow .2s ease;
  }
  .attendance-panel .mini:hover{
    transform:translateY(-3px);
    background:rgba(255,255,255,.085);
    box-shadow:0 12px 24px rgba(0,0,0,.22);
  }
  .attendance-panel .mini b{
    font-size:36px;
    line-height:1;
  }
  .attendance-panel .mini small{
    margin-top:2px;
    font-size:12px;
    line-height:1;
  }
  .attendance-panel .mini .mini-icon{
    width:34px;
    height:34px;
    border-radius:50%;
    font-size:20px;
    margin-bottom:4px;
  }
  .attendance-panel .mini > div:last-child{
    display:flex;
    flex-direction:column;
    gap:0;
    align-items:center;
  }
  .attendance-panel .mini .mini-label{
    font-size:13px;
    margin-top:4px;
    font-weight:700;
  }
  .attendance-panel .mini.present{ border-bottom:3px solid #18f08b; }
  .attendance-panel .mini.late{ border-bottom:3px solid #ffc75a; }
  .attendance-panel .mini.absent{ border-bottom:3px solid #ff3d72; }
  .attendance-panel .mini.total{ border-bottom:3px solid #43a6ff; }
  @media (max-width: 900px){
    .attendance-panel .mini-grid{
      grid-template-columns:repeat(2, minmax(120px, 1fr));
      justify-content:normal;
    }
    .attendance-panel .mini{
      aspect-ratio:1 / 1;
      min-height:120px;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:10px;
    }
    .attendance-panel .mini b{
      font-size:34px;
    }
    .attendance-panel .mini .mini-label{
      font-size:14px;
    }
  }
  @media (max-width: 560px){
    .attendance-panel .mini-grid{
      grid-template-columns:1fr;
    }
  }
  .quick-grid .quick div{
    color:#fff;
  }
  .dashboard > div:first-child{
    gap:12px !important;
  }
  .dashboard .card{
    padding:18px;
  }
  .dashboard .section-head{
    margin-bottom:12px;
  }
  .dashboard .activity{
    padding:10px 0;
  }
  .dashboard .activity b{
    font-size:13px;
  }
  .dashboard .activity p,
  .dashboard .activity time,
  .dashboard .row-item,
  .dashboard .quick span{
    font-size:11.5px;
  }
  .dashboard .row-item{
    padding:8px 10px;
  }
  .dashboard .quick{
    padding:10px 12px;
  }
  .dashboard .card.system-overview-card,
  .dashboard .card:nth-child(2),
  .dashboard .card:nth-child(3){
    padding:16px;
  }
  .attendance-graph-card {
    border:1px solid var(--stroke);
    background:rgba(255,255,255,.18);
  }
  .attendance-graph {
    display:grid;
    gap:18px;
    margin-top:10px;
  }
  .attendance-graph .chart-grid {
    display:grid;
    grid-template-columns:32px minmax(0,1fr);
    gap:18px;
    align-items:start;
  }
  .attendance-graph .y-axis {
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    height:160px;
    color:var(--muted);
    font-size:12px;
    text-align:right;
    padding-right:10px;
  }
  .attendance-graph .y-axis span {
    display:block;
  }
  .attendance-graph .bars-column {
    display:grid;
    gap:14px;
  }
  .attendance-graph .chart-bars {
    display:grid;
    grid-template-columns:repeat(4,minmax(48px,1fr));
    align-items:start;
    gap:18px;
    min-height:160px;
    border-bottom:1px solid rgba(255,255,255,.12);
  }
  .attendance-graph .chart-bar {
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:flex-start;
    gap:10px;
  }
  .attendance-graph .bar-track {
    width:100%;
    height:160px;
    display:flex;
    align-items:flex-end;
    background:rgba(255,255,255,.06);
    border-radius:18px 18px 0 0;
    overflow:hidden;
    position:relative;
  }
  .attendance-graph .bar-track::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(to top, rgba(255,255,255,.05) 0%, transparent 16%);
  }
  .attendance-graph .bar-fill {
    display:block;
    width:100%;
    border-radius:18px 18px 0 0;
    transition:height .4s ease;
    min-height:8px;
  }
  .attendance-graph .bar-fill.present { background:#18f08b; }
  .attendance-graph .bar-fill.late { background:#ffc75a; }
  .attendance-graph .bar-fill.absent { background:#ff3d72; }
  .attendance-graph .bar-fill.total { background:#43a6ff; }
  .attendance-graph .bar-label {
    font-size:11px;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.08em;
    text-align:center;
  }
  .attendance-graph .bar-count {
    font-size:14px;
    font-weight:900;
    color:#fff;
  }
  .attendance-graph-card .small-link {
    color:rgba(255,255,255,.75);
    text-decoration:none;
  }
  .attendance-graph-card .small-link:hover {
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
      </div>
      <div class="mini-grid">
        <div class="mini present">
          <div class="mini-icon" style="background:rgba(24,240,139,.18)">👤</div>
          <div>
            <b style="color:#18f08b">{{ App\Models\AttendanceRecord::where('status', 'present')->count() }}</b>
            <div class="mini-label" style="color:#18f08b">Present</div>
          </div>
        </div>
        <div class="mini late">
          <div class="mini-icon" style="background:rgba(255,199,90,.18)">⏱️</div>
          <div>
            <b style="color:#ffc75a">{{ App\Models\AttendanceRecord::where('status', 'late')->count() }}</b>
            <div class="mini-label" style="color:#ffc75a">Late</div>
          </div>
        </div>
        <div class="mini absent">
          <div class="mini-icon" style="background:rgba(255,61,114,.18)">⊘</div>
          <div>
            <b style="color:#ff3d72">{{ App\Models\AttendanceRecord::where('status', 'absent')->count() }}</b>
            <div class="mini-label" style="color:#ff3d72">Absent</div>
          </div>
        </div>
        <div class="mini total">
          <div class="mini-icon" style="background:rgba(67,166,255,.18)">👥</div>
          <div>
            <b style="color:#43a6ff">{{ App\Models\AttendanceRecord::count() }}</b>
            <div class="mini-label" style="color:#43a6ff">Total</div>
          </div>
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
      <!-- Pending Drops removed: professors handle drops directly -->
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

    <div class="card glass attendance-graph-card">
      <div class="section-head" style="justify-content:space-between;gap:12px">
        <h3>📈 Attendance Graph</h3>
        <a href="{{ route('admin.attendance-records') }}" class="small-link" style="font-size:13px;color:var(--text-muted);">View full report →</a>
      </div>
      @php
        $presentCount = App\Models\AttendanceRecord::where('status', 'present')->count();
        $lateCount = App\Models\AttendanceRecord::where('status', 'late')->count();
        $absentCount = App\Models\AttendanceRecord::where('status', 'absent')->count();
        $totalAttendance = App\Models\AttendanceRecord::count();
        $maxCount = max($totalAttendance, 1);
        $presentHeight = round($presentCount / $maxCount * 100);
        $lateHeight = round($lateCount / $maxCount * 100);
        $absentHeight = round($absentCount / $maxCount * 100);
        $totalHeight = round($totalAttendance / $maxCount * 100);
        $stepValue = ceil($maxCount / 4);
      @endphp
      <div class="attendance-graph">
        <div class="chart-grid">
          <div class="y-axis">
            <span>{{ $stepValue * 4 }}</span>
            <span>{{ $stepValue * 3 }}</span>
            <span>{{ $stepValue * 2 }}</span>
            <span>{{ $stepValue }}</span>
            <span>0</span>
          </div>
          <div class="bars-column">
            <div class="chart-bars">
              <div class="chart-bar">
                <div class="bar-track"><div class="bar-fill present" style="height:{{ $presentHeight }}%"></div></div>
                <div class="bar-count">{{ $presentCount }}</div>
                <div class="bar-label">Present</div>
              </div>
              <div class="chart-bar">
                <div class="bar-track"><div class="bar-fill late" style="height:{{ $lateHeight }}%"></div></div>
                <div class="bar-count">{{ $lateCount }}</div>
                <div class="bar-label">Late</div>
              </div>
              <div class="chart-bar">
                <div class="bar-track"><div class="bar-fill absent" style="height:{{ $absentHeight }}%"></div></div>
                <div class="bar-count">{{ $absentCount }}</div>
                <div class="bar-label">Absent</div>
              </div>
              <div class="chart-bar">
                <div class="bar-track"><div class="bar-fill total" style="height:{{ $totalHeight }}%"></div></div>
                <div class="bar-count">{{ $totalAttendance }}</div>
                <div class="bar-label">Total</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // No JS needed; chart heights are set inline.
</script>
@endsection
