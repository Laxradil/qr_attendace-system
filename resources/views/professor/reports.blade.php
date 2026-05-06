@extends('layouts.professor')

@section('title', 'Reports - Professor')
@section('header', 'Attendance Reports')
@section('subheader', 'View detailed attendance statistics for your classes')

@section('content')
<div class="content">
    <!-- Class Selection -->
    <div class="card">
        <form method="GET" style="display:flex;gap:10px;align-items:flex-end;">
            <div style="flex:1;">
                <label class="fl">Select Class</label>
                <select name="class_id" class="fi">
                    <option value="">Choose a class to view reports...</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ request('class_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-p">View Report</button>
        </form>
    </div>

    @if($attendanceData)
        <!-- Report Stats -->
        <div class="stats stats-3" style="margin-bottom:12px;">
            <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ count($attendanceData) }}</div><div class="stat-label">Total Students</div></div></div>
            <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val">{{ collect($attendanceData)->sum('total') }}</div><div class="stat-label">Total Records</div></div></div>
            <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val">{{ round(collect($attendanceData)->avg('percentage')) }}%</div><div class="stat-label">Avg Rate</div></div></div>
        </div>

        <!-- Attendance Table -->
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th style="text-align:center;">Student ID</th>
                        <th style="text-align:center;">Total</th>
                        <th style="text-align:center;">Present</th>
                        <th style="text-align:center;">Late</th>
                        <th style="text-align:center;">Absent</th>
                        <th style="text-align:center;">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceData as $data)
                        <tr>
                            <td style="font-weight:500;">{{ $data['student']->name }}</td>
                            <td style="text-align:center;color:var(--text2);" class="td-mono">{{ $data['student']->student_id ?? 'N/A' }}</td>
                            <td style="text-align:center;color:var(--text2);">{{ $data['total'] }}</td>
                            <td style="text-align:center;color:var(--green);">{{ $data['present'] }}</td>
                            <td style="text-align:center;color:var(--amber);">{{ $data['late'] ?? 0 }}</td>
                            <td style="text-align:center;color:var(--red);">{{ $data['absent'] ?? 0 }}</td>
                            <td style="text-align:center;">
                                <span class="badge {{ $data['percentage'] >= 80 ? 'bg' : ($data['percentage'] >= 60 ? 'ba' : 'br') }}" style="font-size:10px;">
                                    {{ $data['percentage'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align:center;padding:40px;color:var(--text2);">
            <div style="font-size:24px;margin-bottom:8px;">📊</div>
            <div style="font-size:12px;">Select a class to view attendance reports</div>
        </div>
    @endif
</div>
@endsection
