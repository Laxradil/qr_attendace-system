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

    <div class="section-head">
      <h3 style="font-size:16px; margin-top: 12px;">⚡ Recent Activities</h3>
      <a href="{{ route('professor.logs') }}" style="color: #ffffff;">View all →</a>
    </div>
    @forelse($recentLogs as $log)
      <div class="activity">
        <div class="act-icon @if($log->action == 'create') create @elseif($log->action == 'update') edit @elseif($log->action == 'delete') drop @elseif($log->action == 'scan_qr') scan @else add @endif">
          @if($log->action == 'create') ✨ @elseif($log->action == 'update') ✏️ @elseif($log->action == 'delete') 🗑 @elseif($log->action == 'scan_qr') 📷 @else ➕ @endif
        </div>
        <div><b style="font-size:14px;color:#ffffff">{{ $log->user->name }} {{ ucwords(str_replace('_', ' ', $log->action)) }}</b><p style="font-size:12px;color:#ffffff">{{ $log->description }}</p></div>
        <time style="font-size:12px">{{ $log->created_at->format('h:i A') }}</time>
      </div>
    @empty
      <p style="color:#ffffff;padding:20px;text-align:center;font-size:13px">No recent activities</p>
    @endforelse
  </div>

  <div class="dash-right" style="display:flex;flex-direction:column;gap:12px">
    <div class="card glass">
      <div class="section-head"><h3 style="font-size:16px">📅 Today's Schedule</h3></div>
      @forelse($todaySchedules as $schedule)
        <div class="row-item">
          <div>
            <div style="font-weight:800;font-size:14px">{{ $schedule->subject_code }} · {{ $schedule->subject_name }}</div>
            <div style="font-size:12px;color:#ffffff;margin-top:2px">{{ $schedule->subject_code }} · Room {{ $schedule->room }}</div>
          </div>
          <span style="font-family:var(--mono);font-size:12px;font-weight:700;color:var(--text)">{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time ?? '00:00:00')->format('g:i A') }}</span>
        </div>
      @empty
        <div style="padding:14px;color:#ffffff;text-align:center;">No classes scheduled for today</div>
      @endforelse
    </div>

    <div style="padding:8px;height:320px;display:flex;flex-direction:column;">
      <div class="section-head" style="margin-bottom:8px;"><h3 style="font-size:16px">🏆 Attendance Leaderboard</h3></div>
      <div style="overflow-y:auto;max-height:260px;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
          <thead>
            <tr style="background:rgba(139,92,255,0.12);color:#ffffff;border-bottom:3px solid rgba(139,92,255,0.6);">
              <th style="text-align:left;padding:8px 10px;font-weight:600;font-size:12px;color:#ffffff;">Class</th>
              <th style="text-align:left;padding:8px 10px;font-weight:600;font-size:12px;color:#ffffff;">Attendance %</th>
            </tr>
          </thead>
          <tbody>
            @forelse($leaderboard as $row)
              <tr style="border-bottom:2px solid rgba(139,92,255,0.85);color:#ffffff;">
                <td style="padding:10px 12px;color:#ffffff;font-size:13px;">{{ $row['name'] }}</td>
                <td style="padding:10px 12px;text-align:right;font-weight:700;color:#18f08b;font-size:13px;">{{ $row['rate'] }}%</td>
              </tr>
            @empty
              <tr><td colspan="2" style="padding:18px;text-align:center;color:#ffffff;">No attendance data yet</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

<style>
  /* Light theme solid overrides */
  body.theme-light .ghost-stat {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .ghost-stat:hover {
    background: #f3e8ff !important;
    border-color: #8b5cff !important;
  }
  
  /* Overview cards */
  body.theme-light div[style*="background:rgba(255,255,255,.055)"] {
    background: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
  }
  
  /* Icon backgrounds in overview */
  body.theme-light div[style*="background:rgba(24,240,139,.18)"] {
    background: #dcfce7 !important;
  }
  
  body.theme-light div[style*="background:rgba(255,199,90,.18)"] {
    background: #fef3c7 !important;
  }
  
  body.theme-light div[style*="background:rgba(255,61,114,.18)"] {
    background: #fee2e2 !important;
  }
  
  body.theme-light div[style*="background:rgba(139,92,255,.18)"] {
    background: #ede9fe !important;
  }
  
  body.theme-light div[style*="background:rgba(67,166,255,.18)"] {
    background: #dbeafe !important;
  }
  
  /* Leaderboard table */
  body.theme-light tr[style*="background:rgba(139,92,255,0.12)"] {
    background: #f3e8ff !important;
  }
  
  body.theme-light tr[style*="border-bottom:3px solid rgba(139,92,255,0.6)"] {
    border-bottom: 3px solid #8b5cff !important;
  }
  
  body.theme-light tr[style*="border-bottom:2px solid rgba(139,92,255,0.85)"] {
    border-bottom: 2px solid #8b5cff !important;
  }
  
  /* Text colors */
  body.theme-light [style*="color:#ffffff"] {
    color: #000000 !important;
  }
  
  body.theme-light [style*="color:#18f08b"] {
    color: #166534 !important;
  }
  
  body.theme-light [style*="color:#ffc75a"] {
    color: #92400e !important;
  }
  
  body.theme-light [style*="color:#ff3d72"] {
    color: #991b1b !important;
  }
  
  body.theme-light [style*="color:#8b5cff"] {
    color: #7c3aed !important;
  }
  
  body.theme-light [style*="color:#43a6ff"] {
    color: #2563eb !important;
  }
</style>
