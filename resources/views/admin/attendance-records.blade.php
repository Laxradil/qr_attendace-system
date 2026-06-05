@extends('layouts.admin')

@section('title', 'Attendance Records')
@section('header', 'Attendance Records')
@section('subheader', 'View and manage all attendance records.')

@section('content')

<div class="stats stats-4">
    <div class="stat">
        <div class="stat-icon blue">▤</div>
        <div class="stat-body">
            <strong>{{ $records->total() }}</strong>
            <span>Total Records</span>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon green">✓</div>
        <div class="stat-body">
            <strong>{{ \App\Models\AttendanceRecord::where('status', 'present')->count() }}</strong>
            <span>Present</span>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon yellow">◷</div>
        <div class="stat-body">
            <strong>{{ \App\Models\AttendanceRecord::where('status', 'late')->count() }}</strong>
            <span>Late</span>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon red">✕</div>
        <div class="stat-body">
            <strong>{{ \App\Models\AttendanceRecord::where('status', 'absent')->count() }}</strong>
            <span>Absent</span>
        </div>
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
            @forelse($records as $record)
                <tr>
                    <td>
                        <div style="font-family:var(--mono);font-size:12.5px;">{{ $record->recorded_at?->format('M d, Y') }}</div>
                        <div class="muted" style="font-size:12px;">{{ $record->recorded_at?->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="user-cell">
                            <span class="small-avatar">{{ strtoupper(substr($record->student->name ?? 'ST', 0, 2)) }}</span>
                            <div>
                                <div>{{ $record->student->name ?? 'Unknown' }}</div>
                                <div class="muted" style="font-size:12px;">{{ $record->student->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $record->classe->name ?? 'N/A' }}</div>
                        <div class="muted" style="font-size:12px;">{{ $record->classe->code ?? '-' }}</div>
                    </td>
                    <td>
                        <span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : 'red') }}">
                            {{ ucfirst($record->status ?? 'absent') }}
                        </span>
                    </td>
                    <td class="muted">{{ $record->status === 'late' ? (int) $record->minutes_late . ' min' : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);">No attendance records found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer-bar">
        <span>Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }} records</span>
        <div class="pager">{{ $records->links() }}</div>
    </div>
</div>
@endsection
