@extends('layouts.admin')

@section('title', 'Attendance Records')
@section('header', 'Attendance Records')
@section('subheader', 'View and manage all attendance records.')

@section('content')
<div class="stats stats-4">
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $totalRecords ?? 0 }}</strong>
        <span>Total Records</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon green">✓</div>
      <div class="stat-body">
        <strong>{{ $presentCount ?? 0 }}</strong>
        <span>Present</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">◷</div>
      <div class="stat-body">
        <strong>{{ $lateCount ?? 0 }}</strong>
        <span>Late</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $absentCount ?? 0 }}</strong>
        <span>Absent</span>
      </div>
    </div>
</div>

<div class="toolbar">
    <h3 style="font-size:16px;font-weight:800">📋 Attendance Records</h3>
    <div class="tools">
        <button class="btn">☰ Filter</button>
        <button class="btn" onclick="showToast('Exporting CSV...','📤','#4dffa0')">📤 Export</button>
        <div class="search-bar" style="height:42px;width:240px">🔍 <span style="font-size:13px">Search records...</span></div>
    </div>
</div>

<div class="table-wrap glass">
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
            @forelse($records as $record)
                <tr>
                    <td>
                        <div style="font-family:var(--mono);font-size:12.5px">{{ $record->created_at?->format('M d, Y') }}</div>
                        <div class="muted" style="font-size:12px">{{ $record->created_at?->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="user-cell">
                            <span class="small-avatar">{{ strtoupper(substr($record->student?->name ?? 'N/A', 0, 2)) }}</span>
                            <div>
                                <div>{{ $record->student?->name ?? 'Unknown' }}</div>
                                <div class="muted" style="font-size:12px">{{ $record->student?->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>{{ $record->class?->class_name ?? 'N/A' }}</div>
                        <div class="muted" style="font-size:12px">{{ $record->class?->class_code ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : 'red') }}">
                            {{ ucfirst($record->status ?? 'absent') }}
                        </span>
                    </td>
                    <td class="muted">{{ $record->minutes_late ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);">No attendance records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
