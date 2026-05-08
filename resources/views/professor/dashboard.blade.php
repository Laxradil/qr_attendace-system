@extends('layouts.professor')

@section('title', 'Dashboard - Professor')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->name . '. Here is your class activity overview.')

@section('content')
<style>
  .search-bar {
    display: none !important;
  }
  .ghost-stat {
    position: relative;
    background: transparent !important;
    border-color: transparent !important;
  }
  .ghost-stat:hover {
    background: rgba(139, 92, 255, 0.2) !important;
    border-color: rgba(139, 92, 255, 0.4) !important;
  }
  .ghost-stat:hover .stat-body {
    opacity: 1 !important;
  }
  .ghost-stat .stat-body {
    opacity: 0.85 !important;
  }
</style>

<div class="stats">
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon blue">▤</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $totalClasses }}</strong>
      <span>Total Classes</span>
      <div class="trend up">↑ All active</div>
    </div>
  </div>
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon green">🧑‍🎓</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $totalStudents }}</strong>
      <span>Students</span>
      <div class="trend up">↑ Across all classes</div>
    </div>
  </div>
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon yellow">📋</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $totalRecords }}</strong>
      <span>Attendance Records</span>
      <div class="trend up">↑ Today</div>
    </div>
  </div>
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon purple">📊</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $attendanceRate }}%</strong>
      <span>Attendance Rate</span>
      <div class="trend {{ $attendanceRate >= 80 ? 'up' : 'down' }}">{{ $attendanceRate >= 80 ? '↑' : '↓' }} This week</div>
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
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;font-size:14px">
        <div style="padding:12px;background:rgba(24,240,139,.1);border-radius:8px;text-align:center;border:1px solid rgba(24,240,139,.2);aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="font-weight:800;font-size:24px;color:#18f08b">{{ $presentCount }}</div>
          <div style="color:#18f08b;margin-top:6px;font-weight:600">Present</div>
        </div>
        <div style="padding:12px;background:rgba(240,180,24,.1);border-radius:8px;text-align:center;border:1px solid rgba(240,180,24,.2);aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="font-weight:800;font-size:24px;color:#f0b418">{{ $lateCount }}</div>
          <div style="color:#f0b418;margin-top:6px;font-weight:600">Late</div>
        </div>
        <div style="padding:12px;background:rgba(240,24,24,.1);border-radius:8px;text-align:center;border:1px solid rgba(240,24,24,.2);aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="font-weight:800;font-size:24px;color:#f01818">{{ $absentCount }}</div>
          <div style="color:#f01818;margin-top:6px;font-weight:600">Absent</div>
        </div>
        <div style="padding:12px;background:rgba(139,92,255,.1);border-radius:8px;text-align:center;border:1px solid rgba(139,92,255,.2);aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="font-weight:800;font-size:24px;color:#8b5cff">{{ $excusedCount }}</div>
          <div style="color:#8b5cff;margin-top:6px;font-weight:600">Excused</div>
        </div>
        <div style="padding:12px;background:rgba(67,166,255,.1);border-radius:8px;text-align:center;border:1px solid rgba(67,166,255,.2);aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="font-weight:800;font-size:24px;color:#43a6ff">{{ $totalRecords }}</div>
          <div style="color:#43a6ff;margin-top:6px;font-weight:600">Total</div>
        </div>
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
          <div class="stat-icon blue" style="width:28px;height:28px;border-radius:8px;font-size:14px">▦</div>
          <strong>Scan QR</strong>
          <span>Record attendance</span>
        </div>
        <div class="quick" onclick="window.location.href='{{ route('professor.attendance-records') }}'">
          <div class="stat-icon yellow" style="width:28px;height:28px;border-radius:8px;font-size:14px">📋</div>
          <strong>Attendance</strong>
          <span>View records</span>
        </div>
        <div class="quick" onclick="window.location.href='{{ route('professor.classes') }}'">
          <div class="stat-icon green" style="width:28px;height:28px;border-radius:8px;font-size:14px">▤</div>
          <strong>My Classes</strong>
          <span>Manage classes</span>
        </div>
        <div class="quick" onclick="window.location.href='{{ route('professor.reports') }}'">
          <div class="stat-icon purple" style="width:28px;height:28px;border-radius:8px;font-size:14px">📊</div>
          <strong>Reports</strong>
          <span>View reports</span>
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
