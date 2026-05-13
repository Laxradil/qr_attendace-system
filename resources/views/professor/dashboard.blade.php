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

  .attendance-ring {
    animation: ring-spin 12s linear infinite;
    transform-origin: center;
    transform: rotate(-90deg);
  }
  .attendance-ring-inner {
    animation: ring-fade 0.7s ease both;
  }
  @keyframes ring-spin {
    0% { transform: rotate(-90deg); }
    100% { transform: rotate(270deg); }
  }
  @keyframes ring-fade {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
  }
</style>

<div class="stats">
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon blue">▤</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $totalClasses }}</strong>
      <span>Total Classes</span>
      <div class="trend up" style="font-size:12px;color:#a8b8ff">↑ All active</div>
    </div>
  </div>
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon green">🧑‍🎓</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $totalStudents }}</strong>
      <span>Students</span>
      <div class="trend up" style="font-size:12px;color:#a8b8ff">↑ Across all classes</div>
    </div>
  </div>
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon yellow">📋</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $totalRecords }}</strong>
      <span>Attendance Records</span>
      <div class="trend up" style="font-size:12px;color:#a8b8ff">↑ Today</div>
    </div>
  </div>
  <div class="stat ghost-stat" style="transition:all 0.3s ease">
    <div class="stat-icon purple">📊</div>
    <div class="stat-body" style="transition:opacity 0.3s ease">
      <strong>{{ $attendanceRate }}%</strong>
      <span>Attendance Rate</span>
      <div class="trend {{ $attendanceRate >= 80 ? 'up' : 'down' }}" style="font-size:12px;color:#a8b8ff">{{ $attendanceRate >= 80 ? '↑' : '↓' }} This week</div>
    </div>
  </div>
</div>

<div class="dashboard">
  <div class="dash-left">
    <div class="card glass">
      <div class="section-head">
        <h3 style="font-size:16px">📊 Attendance Overview</h3>
      </div>
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;font-size:14px">
        <div style="padding:16px;background:rgba(255,255,255,.055);border-radius:12px;text-align:center;border:1px solid rgba(255,255,255,.10);border-bottom:3px solid #18f08b;aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(24,240,139,.18);display:grid;place-items:center;margin-bottom:12px;font-size:28px">👤</div>
          <div style="font-weight:800;font-size:28px;color:#18f08b">{{ $presentCount }}</div>
          <div style="color:#18f08b;margin-top:8px;font-weight:700;font-size:12px">Present</div>
        </div>
        <div style="padding:16px;background:rgba(255,255,255,.055);border-radius:12px;text-align:center;border:1px solid rgba(255,255,255,.10);border-bottom:3px solid #ffc75a;aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(255,199,90,.18);display:grid;place-items:center;margin-bottom:12px;font-size:28px">⏱️</div>
          <div style="font-weight:800;font-size:28px;color:#ffc75a">{{ $lateCount }}</div>
          <div style="color:#ffc75a;margin-top:8px;font-weight:700;font-size:12px">Late</div>
        </div>
        <div style="padding:16px;background:rgba(255,255,255,.055);border-radius:12px;text-align:center;border:1px solid rgba(255,255,255,.10);border-bottom:3px solid #ff3d72;aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(255,61,114,.18);display:grid;place-items:center;margin-bottom:12px;font-size:28px">⊘</div>
          <div style="font-weight:800;font-size:28px;color:#ff3d72">{{ $absentCount }}</div>
          <div style="color:#ff3d72;margin-top:8px;font-weight:700;font-size:12px">Absent</div>
        </div>
        <div style="padding:16px;background:rgba(255,255,255,.055);border-radius:12px;text-align:center;border:1px solid rgba(255,255,255,.10);border-bottom:3px solid #8b5cff;aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(139,92,255,.18);display:grid;place-items:center;margin-bottom:12px;font-size:28px">✓</div>
          <div style="font-weight:800;font-size:28px;color:#8b5cff">{{ $excusedCount }}</div>
          <div style="color:#8b5cff;margin-top:8px;font-weight:700;font-size:12px">Excused</div>
        </div>
        <div style="padding:16px;background:rgba(255,255,255,.055);border-radius:12px;text-align:center;border:1px solid rgba(255,255,255,.10);border-bottom:3px solid #43a6ff;aspect-ratio:1;display:flex;flex-direction:column;justify-content:center;align-items:center">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(67,166,255,.18);display:grid;place-items:center;margin-bottom:12px;font-size:28px">👥</div>
          <div style="font-weight:800;font-size:28px;color:#43a6ff">{{ $totalRecords }}</div>
          <div style="color:#43a6ff;margin-top:8px;font-weight:700;font-size:12px">Total</div>
        </div>
      </div>
      <button class="report-btn" onclick="window.location.href='{{ route('professor.attendance-records') }}'">View Full Attendance Report →</button>
    </div>

    <div class="card glass">
      <div class="section-head">
        <h3 style="font-size:16px">⚡ Recent Activities</h3>
        <a href="{{ route('professor.logs') }}">View all →</a>
      </div>
      @forelse($recentLogs as $log)
        <div class="activity">
          <div class="act-icon @if($log->action == 'create') create @elseif($log->action == 'update') edit @elseif($log->action == 'delete') drop @elseif($log->action == 'scan_qr') scan @else add @endif">
            @if($log->action == 'create') ✨ @elseif($log->action == 'update') ✏️ @elseif($log->action == 'delete') 🗑 @elseif($log->action == 'scan_qr') 📷 @else ➕ @endif
          </div>
          <div><b style="font-size:14px">{{ $log->user->name }} {{ ucwords(str_replace('_', ' ', $log->action)) }}</b><p style="font-size:12px;color:#c5d3ff">{{ $log->description }}</p></div>
          <time style="font-size:12px">{{ $log->created_at->format('h:i A') }}</time>
        </div>
      @empty
        <p style="color:#c5d3ff;padding:20px;text-align:center;font-size:13px">No recent activities</p>
      @endforelse
    </div>
  </div>

  <div class="dash-right" style="display:flex;flex-direction:column;gap:12px">
    <div class="card glass">
      <div class="section-head"><h3 style="font-size:16px">📅 Today's Schedule</h3></div>
      @forelse($todaySchedules as $schedule)
        <div class="row-item">
          <div>
            <div style="font-weight:800;font-size:14px">{{ $schedule->subject_code }} · {{ $schedule->subject_name }}</div>
            <div style="font-size:12px;color:#c5d3ff;margin-top:2px">{{ $schedule->subject_code }} · Room {{ $schedule->room }}</div>
          </div>
          <span style="font-family:var(--mono);font-size:12px;font-weight:700;color:var(--text)">{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time ?? '00:00:00')->format('g:i A') }}</span>
        </div>
      @empty
        <div style="padding:14px;color:#c5d3ff;text-align:center;">No classes scheduled for today</div>
      @endforelse
    </div>

    <div class="card glass" style="padding:20px;min-height:320px;display:flex;flex-direction:column;">
      <div class="section-head" style="margin-bottom:12px;"><h3 style="font-size:16px">⚡ Attendance Overview</h3></div>
      @php
        $presentPct = $totalRecords > 0 ? ($presentCount / $totalRecords) * 100 : 0;
        $latePct = $totalRecords > 0 ? ($lateCount / $totalRecords) * 100 : 0;
        $absentPct = $totalRecords > 0 ? ($absentCount / $totalRecords) * 100 : 0;
        $presentEnd = $presentPct;
        $lateEnd = $presentPct + $latePct;
        $absentEnd = $presentPct + $latePct + $absentPct;
      @endphp
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;align-items:center;flex:1;min-height:0;">
        <div style="display:flex;align-items:center;justify-content:center;">
          <div style="position:relative;width:200px;height:200px;">
            <div class="attendance-ring" style="position:absolute;inset:0;border-radius:50%;background:conic-gradient(#18f08b 0% {{ $presentEnd }}%, #ffc75a {{ $presentEnd }}% {{ $lateEnd }}%, #ff3d72 {{ $lateEnd }}% {{ $absentEnd }}%, rgba(255,255,255,.08) {{ $absentEnd }}% 100%);"></div>
            <div class="attendance-ring-inner" style="position:absolute;inset:16px;border-radius:50%;background:rgba(2,4,18,1);display:grid;place-items:center;border:1px solid rgba(255,255,255,.08);">
              <div style="text-align:center;">
                <div style="font-size:36px;font-weight:900;line-height:1;color:#fff;">{{ $attendanceRate }}<span style="font-size:16px;color:var(--muted);">%</span></div>
                <div style="margin-top:6px;font-size:12px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;">Average</div>
              </div>
            </div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:8px;">
          <div style="padding:12px 14px;border-radius:16px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.08);display:flex;justify-content:space-between;align-items:center;min-height:64px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <span style="width:12px;height:12px;border-radius:999px;background:#18f08b;display:inline-block;"></span>
              <div>
                <div style="font-size:12px;color:var(--muted);">Present</div>
                <div style="font-weight:700;color:#fff;">{{ $presentCount }}</div>
              </div>
            </div>
            <div style="font-size:13px;font-weight:700;color:#18f08b;">{{ $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100) : 0 }}%</div>
          </div>

          <div style="padding:12px 14px;border-radius:16px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.08);display:flex;justify-content:space-between;align-items:center;min-height:64px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <span style="width:12px;height:12px;border-radius:999px;background:#ffc75a;display:inline-block;"></span>
              <div>
                <div style="font-size:12px;color:var(--muted);">Late</div>
                <div style="font-weight:700;color:#fff;">{{ $lateCount }}</div>
              </div>
            </div>
            <div style="font-size:13px;font-weight:700;color:#ffc75a;">{{ $totalRecords > 0 ? round(($lateCount / $totalRecords) * 100) : 0 }}%</div>
          </div>

          <div style="padding:12px 14px;border-radius:16px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.08);display:flex;justify-content:space-between;align-items:center;min-height:64px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <span style="width:12px;height:12px;border-radius:999px;background:#ff3d72;display:inline-block;"></span>
              <div>
                <div style="font-size:12px;color:var(--muted);">Absent</div>
                <div style="font-weight:700;color:#fff;">{{ $absentCount }}</div>
              </div>
            </div>
            <div style="font-size:13px;font-weight:700;color:#ff3d72;">{{ $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100) : 0 }}%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
