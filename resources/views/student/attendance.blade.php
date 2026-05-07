@extends('layouts.student')

@section('title', 'Attendance Review')
@section('subtitle', 'Track your attendance history across all enrolled classes.')

@section('content')
<!-- ══ ATTENDANCE ══ -->
<section class="page active" id="attendance">
  <div class="att-stats stats">
    <div class="stat glass">
      <div class="stat-icon green">✓</div>
      <div class="stat-body">
        <strong>{{ $totalPresent }}</strong>
        <span class="stat-label">Present</span>
        <div style="font-size:12px;color:var(--green);margin-top:3px;font-weight:700">{{ $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0 }}% rate</div>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">◷</div>
      <div class="stat-body">
        <strong>{{ $totalLate }}</strong>
        <span class="stat-label">Late</span>
        <a onclick="showToast('Viewing late records','📋','#ffd584')">View details →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $totalAbsent }}</strong>
        <span class="stat-label">Absent</span>
        <a onclick="showToast('Viewing absent records','📋','#ff8298')">View details →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">✉</div>
      <div class="stat-body">
        <strong>{{ $totalExcused ?? 0 }}</strong>
        <span class="stat-label">Excused</span>
        <a onclick="showToast('Viewing excused records','📋','#d8cdff')">View details →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $totalRecords }}</strong>
        <span class="stat-label">Total Records</span>
        <a onclick="showToast('Viewing all records','📋','#80cbff')">View all →</a>
      </div>
    </div>
  </div>

  <div class="card glass" style="margin-top:4px">
    <div class="section-head">
      <h3>📋 Attendance History</h3>
    </div>
    <div class="att-table-wrap">
      <table>
        <thead>
          <tr>
            <th>Class</th>
            <th>Recorded</th>
            <th>Status</th>
            <th>Minutes Late</th>
          </tr>
        </thead>
        <tbody>
          @forelse($attendanceRecords as $record)
          <tr>
            <td><div style="font-weight:700;font-size:14px">{{ $record->classe->code }} — {{ $record->classe->name }}</div></td>
            <td><div style="font-family:var(--mono);font-size:13px">{{ $record->recorded_at->format('M d, Y') }}</div><div class="muted" style="font-size:12px">{{ $record->recorded_at->format('h:i A') }}</div></td>
            <td><span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : ($record->status === 'absent' ? 'red' : 'purple')) }}">{{ ucfirst($record->status) }}</span></td>
            <td class="muted">{{ $record->minutes_late ?? 0 }} min</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center;padding:40px;color:var(--faint);">No attendance records yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
