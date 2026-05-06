@extends('layouts.admin')

@section('title', 'Attendance Records')
@section('header', 'Attendance Records')
@section('subheader', 'View and manage all attendance records.')

@section('content')
<div class="stats stats-4" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $records->total() }}</div><div class="stat-label">Total Records</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val" style="color:var(--green);">{{ \App\Models\AttendanceRecord::where('status', 'present')->count() }}</div><div class="stat-label">Present</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--amber-bg);"></div><div><div class="stat-val" style="color:var(--amber);">{{ \App\Models\AttendanceRecord::where('status', 'late')->count() }}</div><div class="stat-label">Late</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val" style="color:var(--red);">{{ \App\Models\AttendanceRecord::where('status', 'absent')->count() }}</div><div class="stat-label">Absent</div></div></div>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Date & Time</th><th>Student</th><th>Class</th><th>Status</th><th>Minutes Late</th></tr></thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td class="td-mono">{{ $record->recorded_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div class="log-av">{{ strtoupper(substr($record->student->name ?? 'ST', 0, 2)) }}</div>
                            <div>
                                <div style="font-size:11px;font-weight:500;">{{ $record->student->name ?? 'Unknown' }}</div>
                                <div class="td-mono" style="font-size:9px;">{{ $record->student->student_id ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:11px;font-weight:500;">{{ $record->classe->name ?? 'N/A' }}</div>
                        <div style="font-size:9px;color:var(--text2);">{{ $record->classe->code ?? '-' }}</div>
                    </td>
                    <td>
                        @if($record->status === 'present')
                            <span class="badge bg">Present</span>
                        @elseif($record->status === 'late')
                            <span class="badge ba">Late</span>
                        @else
                            <span class="badge br">Absent</span>
                        @endif
                    </td>
                    <td>{{ $record->status === 'late' ? (int) $record->minutes_late . ' min' : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text2);">No attendance records found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} records</span><div>{{ $records->links() }}</div></div>
</div>
@endsection
