@extends('layouts.professor')

@section('title', 'Attendance Records - Student')
@section('header', 'Attendance Review')
@section('subheader', 'Track your attendance history across all enrolled classes.')

@section('content')
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalPresent }}</div>
        <div class="stat-label">Present</div>
        <div style="font-size:10px;color:var(--green);margin-top:2px;">{{ round(($totalPresent / ($totalPresent + $totalLate + $totalAbsent)) * 100, 1) }}% rate</div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalLate }}</div>
        <div class="stat-label">Late</div>
        <div style="font-size:10px;color:var(--amber);margin-top:2px;\">View details -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalAbsent }}</div>
        <div class="stat-label">Absent</div>
        <div style="font-size:10px;color:var(--red);margin-top:2px;\">View details -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalRecords }}</div>
        <div class="stat-label">Total Records</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;\">View all -></div>
    </a>
</div>

<div class="card">
    <div class="sh">Attendance History</div>
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th style="min-width:160px;">Class</th>
                    <th style="min-width:120px;">Recorded</th>
                    <th style="min-width:100px;\">Status</th>
                    <th style="min-width:100px;\">Minutes Late</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendanceRecords as $record)
                    <tr>
                        <td>
                            <div style="font-size:12px;font-weight:600;">{{ $record->classe->display_name ?? 'Unknown Class' }}</div>
                        </td>
                        <td>
                            <div style="font-size:11px;">{{ $record->recorded_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</div>
                            <div style="font-size:10px;color:var(--text2);">{{ $record->recorded_at?->tz('UTC')->setTimezone('Asia/Manila')->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($record->status === 'present')
                                <span class="badge bg" style="font-size:10px;\">Present</span>
                            @elseif($record->status === 'late')
                                <span class="badge ba" style="font-size:10px;\">Late</span>
                            @else
                                <span class="badge br" style="font-size:10px;\">Absent</span>
                            @endif
                        </td>
                        <td style="color:var(--text2);\">{{ $record->minutes_late !== null ? $record->minutes_late . ' min' : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--text2);\">No attendance records found yet. Scan your student QR with the professor to get started.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendanceRecords->count())
        <div style="display:flex;justify-content:flex-end;margin-top:10px;\">
            {{ $attendanceRecords->links() }}
        </div>
    @endif
</div>
@endsection
