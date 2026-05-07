@extends('layouts.admin-new')

@section('title', 'Attendance Records - QR Attendance Admin')
@section('pageTitle', 'Attendance Records')
@section('pageSubtitle', 'View and manage all attendance records.')

@section('content')
<div class="stats">
  <div class="stat glass"><div class="stat-icon blue">▤</div><div class="stat-body"><strong>{{ $attendanceRecords->count() }}</strong><span>Total Records</span></div></div>
  <div class="stat glass"><div class="stat-icon green">✓</div><div class="stat-body"><strong>{{ $attendanceRecords->where('status', 'present')->count() }}</strong><span>Present</span></div></div>
  <div class="stat glass"><div class="stat-icon yellow">◷</div><div class="stat-body"><strong>{{ $attendanceRecords->where('status', 'late')->count() }}</strong><span>Late</span></div></div>
  <div class="stat glass"><div class="stat-icon red">✕</div><div class="stat-body"><strong>{{ $attendanceRecords->where('status', 'absent')->count() }}</strong><span>Absent</span></div></div>
</div>

<div class="glass-table glass" style="margin-top:16px">
  <div class="toolbar">
    <h3 style="font-size:16px;font-weight:800">📋 Attendance Records</h3>
    <div class="tools">
      <button class="btn">☰ Filter</button>
      <button class="btn" onclick="alert('Exporting CSV...')">📤 Export</button>
      <div class="search-bar" style="height:42px;width:240px">🔍 <span style="font-size:13px">Search records...</span></div>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date & Time</th>
          <th>Student</th>
          <th>Class</th>
          <th>Status</th>
          <th>Minutes Late</th>
        </tr>
      </thead>
      <tbody>
        @forelse($attendanceRecords as $record)
        <tr>
          <td>
            <div style="font-family:var(--mono);font-size:12.5px">{{ $record->recorded_at->format('M d, Y') }}</div>
            <div class="muted" style="font-size:12px">{{ $record->recorded_at->format('h:i A') }}</div>
          </td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($record->student->name, 0, 2)) }}</span>
              <div>
                <div>{{ $record->student->name }}</div>
                <div class="muted" style="font-size:12px">{{ $record->student->email }}</div>
              </div>
            </div>
          </td>
          <td>
            <div>{{ $record->classe->code }} — {{ $record->classe->name }}</div>
            <div class="muted" style="font-size:12px">{{ $record->classe->code }}</div>
          </td>
          <td><span class="pill @if($record->status == 'present') green @elseif($record->status == 'late') yellow @else red @endif">{{ ucfirst($record->status) }}</span></td>
          <td class="muted">{{ $record->minutes_late ?? '—' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No attendance records found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
