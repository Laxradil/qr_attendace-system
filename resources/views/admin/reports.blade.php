@extends('layouts.admin')

@section('title', 'Reports')
@section('header', 'Reports')
@section('subheader', 'Generate and view attendance reports and statistics.')

@section('content')
<div class="stats stats-5" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $totalStudents }}</div><div class="stat-label">Total Students</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val" style="color:var(--green);">{{ $presentCount }}</div><div class="stat-label">Present</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--amber-bg);"></div><div><div class="stat-val" style="color:var(--amber);">{{ $lateCount }}</div><div class="stat-label">Late</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val" style="color:var(--red);">{{ $absentCount }}</div><div class="stat-label">Absent</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val">{{ $totalRecords }}</div><div class="stat-label">Attendance Records</div></div></div>
</div>

<div class="sh">Top Classes by Attendance</div>
<div class="tbl-wrap">
    <table>
        <thead><tr><th>Rank</th><th>Class</th><th>Professor</th><th>Present</th><th>Total</th><th>Attendance Rate</th></tr></thead>
        <tbody>
            @forelse($topClasses as $index => $classe)
                @php
                    $rate = $classe->total_records > 0 ? round(($classe->present_records / $classe->total_records) * 100, 2) : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="font-size:11px;font-weight:500;">{{ $classe->name }}</div>
                        <div class="td-mono" style="font-size:9px;">{{ $classe->code }}</div>
                    </td>
                    <td style="font-size:10px;">{{ $classe->professor->name ?? 'N/A' }}</td>
                    <td>{{ $classe->present_records }}</td>
                    <td>{{ $classe->total_records }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="flex:1;height:4px;background:var(--navy3);border-radius:2px;"><div style="width:{{ $rate }}%;height:100%;background:{{ $rate >= 85 ? 'var(--green)' : 'var(--amber)' }};border-radius:2px;"></div></div>
                            <span style="font-size:10px;font-weight:600;">{{ $rate }}%</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No report data available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
