@extends('layouts.professor')

@section('title', 'Attendance Records - Professor')
@section('header', 'Attendance Records')
@section('subheader', 'View and update attendance records for your classes.')

@section('content')
<div class="stats stats-4" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $totalRecords }}</div><div class="stat-label">Total Records</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val" style="color:var(--green);">{{ $presentCount }}</div><div class="stat-label">Present</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--amber-bg);"></div><div><div class="stat-val" style="color:var(--amber);">{{ $lateCount }}</div><div class="stat-label">Late</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val" style="color:var(--red);">{{ $absentCount }}</div><div class="stat-label">Absent</div></div></div>
</div>

<div class="card">
    <form method="GET" class="g2" style="gap:10px;align-items:end;">
        <div>
            <label class="fl" for="class_id">Class</label>
            <select id="class_id" name="class_id" class="fi">
                <option value="">All Classes</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}" {{ request('class_id') == $classe->id ? 'selected' : '' }}>{{ $classe->code }} - {{ $classe->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="fl" for="date">Date</label>
            <input id="date" type="date" name="date" value="{{ request('date') }}" class="fi">
        </div>
        <div style="grid-column:1 / -1;display:flex;gap:8px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-p">Filter</button>
            <a href="{{ route('professor.attendance-records') }}" class="btn">Reset</a>
        </div>
    </form>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Date</th><th>Scan Time</th><th>Student</th><th>Subject</th><th>Status</th><th>Minutes Late</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td class="td-mono">{{ $record->recorded_at?->format('M d, Y') }}</td>
                    <td class="td-mono" style="font-weight:600;color:var(--text);">{{ $record->recorded_at?->format('h:i A') }}</td>
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
                        <div class="td-mono" style="font-size:10px;color:var(--text3);margin-top:4px;">{{ $record->recorded_at?->format('M d, Y') }}</div>
                    </td>
                    <td>{{ $record->status === 'late' ? (int) $record->minutes_late . ' min' : '-' }}</td>
                    <td>
                        <a href="{{ route('professor.attendance-records.edit', $record) }}" class="btn btn-sm">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text2);">No attendance records found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} records</span><div>{{ $records->links() }}</div></div>
</div>
@endsection
