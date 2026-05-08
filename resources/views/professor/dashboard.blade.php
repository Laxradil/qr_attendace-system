@extends('layouts.professor')

@section('title', 'Dashboard - Professor')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->name . '. Here is your class activity overview.')

@section('content')
<div class="stats">
  <div class="stat glass">
    <div class="stat-icon blue">▤</div>
    <div class="stat-body">
      <strong>{{ $totalClasses }}</strong>
      <span>Total Classes</span>
      <div class="trend up">↑ All active</div>
      <a href="{{ route('professor.classes') }}">View classes →</a>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon green">🧑‍🎓</div>
    <div class="stat-body">
      <strong>{{ $totalStudents }}</strong>
      <span>Students</span>
      <div class="trend up">↑ Across all classes</div>
      <a href="{{ route('professor.students') }}">View students →</a>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon yellow">📋</div>
    <div class="stat-body">
      <strong>{{ $totalRecords }}</strong>
      <span>Attendance Records</span>
      <div class="trend up">↑ Today</div>
      <a href="{{ route('professor.attendance-records') }}">View records →</a>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon purple">📊</div>
    <div class="stat-body">
      <strong>{{ $attendanceRate }}%</strong>
      <span>Attendance Rate</span>
      <div class="trend {{ $attendanceRate >= 80 ? 'up' : 'down' }}">{{ $attendanceRate >= 80 ? '↑' : '↓' }} This week</div>
      <a href="{{ route('professor.attendance-records') }}">View reports →</a>
    </div>
  </div>
</div>

<div class="dashboard">
  <div class="dash-left">
    <div class="card glass">
      <div class="section-head">
        <h3>📊 Attendance Overview</h3>
        <a href="{{ route('professor.attendance-records') }}">View Full Report →</a>
      </div>
      <div class="mini-grid">
        <div class="mini">
          <div class="mini-icon stat-icon green" style="width:32px;height:32px;border-radius:10px;font-size:14px">✓</div>
          <div><b style="color:#4dffa0">{{ $presentCount }}</b><small>Present</small></div>
        </div>
        <div class="mini">
          <div class="mini-icon stat-icon yellow" style="width:32px;height:32px;border-radius:10px;font-size:14px">◷</div>
          <div><b style="color:#ffd584">{{ $lateCount }}</b><small>Late</small></div>
        </div>
        <div class="mini">
          <div class="mini-icon stat-icon red" style="width:32px;height:32px;border-radius:10px;font-size:14px">✕</div>
          <div><b style="color:#ff7f96">{{ $absentCount }}</b><small>Absent</small></div>
        </div>
        <div class="mini">
          <div class="mini-icon stat-icon blue" style="width:32px;height:32px;border-radius:10px;font-size:14px">▤</div>
          <div><b>{{ $totalRecords }}</b><small>Total</small></div>
        </div>
      </div>
      <div style="height:6px;border-radius:99px;background:rgba(255,255,255,.1);overflow:hidden;margin-bottom:10px">
        <div style="height:100%;width:{{ $attendanceRate }}%;background:linear-gradient(90deg,var(--green),var(--blue));border-radius:99px;box-shadow:0 0 10px rgba(24,240,139,.5)"></div>
      </div>
      <button class="report-btn" onclick="window.location.href='{{ route('professor.attendance-records') }}'">View Full Attendance Report →</button>
    </div>

    <div class="card glass">
      <div class="section-head">
        <h3>⚡ Recent Activities</h3>
        <a href="{{ route('professor.logs') }}">View all →</a>
      </div>
      @forelse($recentLogs as $log)
        <div class="activity">
          <div class="act-icon att">📋</div>
          <div>
            <b>{{ ucfirst($log->action) }}</b>
            <p>{{ $log->description }}</p>
          </div>
          <time>{{ $log->created_at->diffForHumans() }}</time>
        </div>
      @empty
        <div style="padding:14px;color:var(--muted);text-align:center;">No recent activities</div>
      @endforelse
    </div>
  </div>

  <div class="dash-right">
    <div class="card glass">
      <div class="section-head"><h3>📅 Today's Schedule</h3></div>
      @forelse($todaySchedules as $schedule)
        <div class="row-item">
          <div>
            <div style="font-weight:800;font-size:13.5px">{{ $schedule->subject_code }} · {{ $schedule->subject_name }}</div>
            <div style="font-size:11.5px;color:var(--muted);margin-top:2px">{{ $schedule->subject_code }} · Room {{ $schedule->room }}</div>
          </div>
          <span style="font-family:var(--mono);font-size:12px;font-weight:700;color:var(--text)">{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time ?? '00:00:00')->format('g:i A') }}</span>
        </div>
      @empty
        <div style="padding:14px;color:var(--muted);text-align:center;">No classes scheduled for today</div>
      @endforelse
    </div>

    <div class="card glass">
      <div class="section-head"><h3>⚡ Quick Actions</h3></div>
      <div class="quick-grid">
        <div class="quick" onclick="window.location.href='{{ route('professor.scan-qr') }}'">
          <div class="stat-icon blue" style="width:36px;height:36px;border-radius:11px;font-size:16px;flex-shrink:0">▦</div>
          <div><strong>Scan QR</strong><span>Record attendance</span></div>
        </div>
        <div class="quick" onclick="window.location.href='{{ route('professor.attendance-records') }}'">
          <div class="stat-icon yellow" style="width:36px;height:36px;border-radius:11px;font-size:16px;flex-shrink:0">📋</div>
          <div><strong>Attendance</strong><span>View records</span></div>
        </div>
        <div class="quick" onclick="window.location.href='{{ route('professor.classes') }}'">
          <div class="stat-icon green" style="width:36px;height:36px;border-radius:11px;font-size:16px;flex-shrink:0">▤</div>
          <div><strong>My Classes</strong><span>Manage classes</span></div>
        </div>
        <div class="quick" onclick="window.location.href='{{ route('professor.reports') }}'">
          <div class="stat-icon purple" style="width:36px;height:36px;border-radius:11px;font-size:16px;flex-shrink:0">📊</div>
          <div><strong>Reports</strong><span>View reports</span></div>
        </div>
      </div>
    </div>

    <div class="card glass" style="background:linear-gradient(135deg,rgba(139,92,255,.22),rgba(67,166,255,.12));border-color:rgba(139,92,255,.35)">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
        <div style="font-size:26px">▦</div>
        <div>
          <div style="font-size:14px;font-weight:800">Ready to take attendance?</div>
          <div style="font-size:12px;color:var(--muted);margin-top:2px">Scan a student QR code and record attendance instantly.</div>
        </div>
      </div>
      <button class="report-btn" onclick="window.location.href='{{ route('professor.scan-qr') }}'">Start Scanning →</button>
    </div>
  </div>
</div>
@endsection
