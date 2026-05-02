@extends('layouts.app')

@section('title', 'Attendance Records')
@section('header', 'Attendance Review')
@section('subheader', 'Review your attendance history across enrolled classes.')

@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin-bottom:24px;">
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--green);font-weight:700;">{{ $totalPresent }}</div>
        <div class="stat-label" style="font-size:13px;">Present</div>
    </div>
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--amber);font-weight:700;">{{ $totalLate }}</div>
        <div class="stat-label" style="font-size:13px;">Late</div>
    </div>
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--red);font-weight:700;">{{ $totalAbsent }}</div>
        <div class="stat-label" style="font-size:13px;">Absent</div>
    </div>
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--blue);font-weight:700;">{{ $totalRecords }}</div>
        <div class="stat-label" style="font-size:13px;">Total Records</div>
    </div>
</div>

<div class="card" style="padding:0 0 12px 0;">
    <div class="sh" style="font-size:18px;font-weight:600;margin-bottom:10px;">Attendance History</div>
    <div class="tbl-wrap" style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0 4px;">
            <thead>
                <tr style="background:var(--bg2);">
                    <th style="padding:10px 8px;text-align:left;min-width:160px;">Class</th>
                    <th style="padding:10px 8px;text-align:left;min-width:120px;">Recorded</th>
                    <th style="padding:10px 8px;text-align:left;min-width:120px;">Status</th>
                    <th style="padding:10px 8px;text-align:left;min-width:140px;">Minutes Late</th>
                    <th style="padding:10px 8px;text-align:left;min-width:180px;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendanceRecords as $record)
                    <tr style="background:var(--bg1);box-shadow:0 1px 4px rgba(0,0,0,0.03);">
                        <td style="padding:10px 8px;">
                            <div style="font-size:13px;font-weight:600;">{{ $record->classe->name ?? 'Unknown Class' }}</div>
                            <div style="font-size:11px;color:var(--text2);">{{ $record->classe->code ?? 'N/A' }}</div>
                        </td>
                        <td style="padding:10px 8px;">
                            <div style="font-size:12px;color:var(--text2);">{{ $record->recorded_at?->format('M d, Y') }}</div>
                            <div style="font-size:11px;color:var(--text3);">{{ $record->recorded_at?->format('h:i A') }}</div>
                        </td>
                        <td style="padding:10px 8px;">
                            @if($record->status === 'present')
                                <span class="badge bg" style="font-size:11px;">Present</span>
                            @elseif($record->status === 'late')
                                <span class="badge" style="background:var(--amber);color:white;font-size:11px;">Late</span>
                            @else
                                <span class="badge br" style="font-size:11px;">Absent</span>
                            @endif
                        </td>
                        <td style="padding:10px 8px;">{{ $record->minutes_late !== null ? $record->minutes_late . ' min' : '-' }}</td>
                        <td style="padding:10px 8px;color:var(--text2);font-size:11px;">{{ $record->qrCode?->code ? 'QR: ' . $record->qrCode->code : 'Recorded by professor' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--text2);padding:20px;font-size:12px;">No attendance records found yet. Scan your student QR with the professor to get started.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex;justify-content:flex-end;margin-top:16px;">
    {{ $attendanceRecords->links() }}
</div>
@endsection
