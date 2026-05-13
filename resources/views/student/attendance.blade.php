@extends('layouts.student')

@section('title', 'Attendance Review')
@section('subtitle', 'Track your attendance history across all enrolled classes.')

@section('content')
<!-- ══ ATTENDANCE ══ -->
<section class="page" id="attendance">
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
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $totalAbsent }}</strong>
        <span class="stat-label">Absent</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">✉</div>
      <div class="stat-body">
        <strong>{{ $totalExcused ?? 0 }}</strong>
        <span class="stat-label">Excused</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $totalRecords }}</strong>
        <span class="stat-label">Total Records</span>
      </div>
    </div>
  </div>

  <div class="card glass" style="margin-top:4px">
    <div class="section-head">
      <h3>📋 Attendance History</h3>
    </div>
    <div class="att-table-wrap">
      <table style="color: white;">
        <thead>
          <tr>
            <th style="color: white;">Class</th>
            <th style="color: white;">Recorded</th>
            <th style="color: white;">Status</th>
            <th style="color: white;">Minutes Late</th>
          </tr>
        </thead>
        <tbody>
          @forelse($attendanceRecords as $record)
          <tr>
            <td style="color: white;"><div style="font-weight:700;font-size:14px">{{ $record->classe->code }} — {{ $record->classe->name }}</div></td>
            <td style="color: white;"><div style="font-family:var(--mono);font-size:13px">{{ $record->recorded_at->format('M d, Y') }}</div><div class="muted" style="font-size:12px">{{ $record->recorded_at->format('h:i A') }}</div></td>
            <td style="color: white;"><span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : ($record->status === 'absent' ? 'red' : 'purple')) }}">{{ ucfirst($record->status) }}</span></td>
            <td class="muted" style="color: white;">{{ $record->minutes_late ?? 0 }} min</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center;padding:40px;color:white;">No attendance records yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($attendanceRecords->hasPages())
    <div style="margin-top:14px;padding:0 16px 16px">
      {{ $attendanceRecords->links() }}
    </div>
    @endif
  </div>
</section>
@endsection
