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

    <div class="card glass" style="padding:8px;height:320px;display:flex;flex-direction:column;">
      <div class="section-head" style="margin-bottom:8px;"><h3 style="font-size:16px">⚡ Quick Actions</h3></div>
      <div class="quick-grid" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;align-content:start;flex:1;height:100%">
        <div class="quick" style="min-height:70px;height:100%;width:100%;flex:1;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;border-radius:14px;border:1px solid rgba(67,166,255,.3);background:rgba(67,166,255,.06);transition:.2s ease;cursor:pointer;position:relative;overflow:hidden" onclick="window.location.href='{{ route('professor.scan-qr') }}'">
          <div class="stat-icon blue" style="width:26px;height:26px;border-radius:8px;font-size:12px;display:grid;place-items:center;">▦</div>
          <strong style="font-size:12px;line-height:1.1;text-align:center;">Scan QR</strong>
          <span style="font-size:10px;color:#aab4dd;line-height:1;">Record</span>
        </div>
        <div class="quick" style="min-height:70px;height:100%;width:100%;flex:1;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;border-radius:14px;border:1px solid rgba(255,199,90,.3);background:rgba(255,199,90,.06);transition:.2s ease;cursor:pointer;position:relative;overflow:hidden" onclick="window.location.href='{{ route('professor.attendance-records') }}'">
          <div class="stat-icon yellow" style="width:26px;height:26px;border-radius:8px;font-size:12px;display:grid;place-items:center;">📋</div>
          <strong style="font-size:12px;line-height:1.1;text-align:center;">Attendance</strong>
          <span style="font-size:10px;color:#aab4dd;line-height:1;">Records</span>
        </div>
        <div class="quick" style="min-height:70px;height:100%;width:100%;flex:1;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;border-radius:14px;border:1px solid rgba(24,240,139,.3);background:rgba(24,240,139,.06);transition:.2s ease;cursor:pointer;position:relative;overflow:hidden" onclick="window.location.href='{{ route('professor.classes') }}'">
          <div class="stat-icon green" style="width:26px;height:26px;border-radius:8px;font-size:12px;display:grid;place-items:center;">▤</div>
          <strong style="font-size:12px;line-height:1.1;text-align:center;">Classes</strong>
          <span style="font-size:10px;color:#aab4dd;line-height:1;">Manage</span>
        </div>
        <div class="quick" style="min-height:70px;height:100%;width:100%;flex:1;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;border-radius:14px;border:1px solid rgba(255,199,90,.3);background:rgba(255,199,90,.06);transition:.2s ease;cursor:pointer;position:relative;overflow:hidden" onclick="window.location.href='{{ route('professor.schedules') }}'">
          <div class="stat-icon yellow" style="width:26px;height:26px;border-radius:8px;font-size:12px;display:grid;place-items:center;">📅</div>
          <strong style="font-size:12px;line-height:1.1;text-align:center;">Schedules</strong>
          <span style="font-size:10px;color:#aab4dd;line-height:1;">View</span>
        </div>
        <div class="quick" style="min-height:70px;height:100%;width:100%;flex:1;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;border-radius:14px;border:1px solid rgba(24,240,139,.3);background:rgba(24,240,139,.06);transition:.2s ease;cursor:pointer;position:relative;overflow:hidden" onclick="window.location.href='{{ route('professor.students') }}'">
          <div class="stat-icon green" style="width:26px;height:26px;border-radius:8px;font-size:12px;display:grid;place-items:center;">🧑‍🎓</div>
          <strong style="font-size:12px;line-height:1.1;text-align:center;">Students</strong>
          <span style="font-size:10px;color:#aab4dd;line-height:1;">View</span>
        </div>
        <div class="quick" style="min-height:70px;height:100%;width:100%;flex:1;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;border-radius:14px;border:1px solid rgba(139,92,255,.3);background:rgba(139,92,255,.06);transition:.2s ease;cursor:pointer;position:relative;overflow:hidden" onclick="window.location.href='{{ route('professor.reports') }}'">
          <div class="stat-icon purple" style="width:26px;height:26px;border-radius:8px;font-size:12px;display:grid;place-items:center;">📊</div>
          <strong style="font-size:12px;line-height:1.1;text-align:center;">Reports</strong>
          <span style="font-size:10px;color:#aab4dd;line-height:1;">View</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
