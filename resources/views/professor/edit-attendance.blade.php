@extends('layouts.professor')

@section('title', 'Edit Attendance - Professor')
@section('header', 'Edit Attendance')
@section('subheader', 'Manually update an attendance record for your class.')

@section('content')
<div class="g-6-4">
    <div class="card">
        <div class="sh">Record Details</div>
        <div style="display:grid;gap:10px;">
            <div>
                <div class="fl">Student</div>
                <div class="fi" style="display:flex;align-items:center;gap:8px;">{{ $record->student->name ?? 'Unknown' }}</div>
            </div>
            <div>
                <div class="fl">Student ID</div>
                <div class="fi">{{ $record->student->student_id ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="fl">Class</div>
                <div class="fi">{{ $record->classe->display_name ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="fl">Current QR Scan</div>
                <div class="fi">{{ optional($record->qrCode)->uuid ?? 'N/A' }}</div>
            </div>
            <div class="info">Changes are recorded in the activity logs.</div>
        </div>
    </div>

    <div class="card">
        <div class="sh">Update Attendance</div>
        <form method="POST" action="{{ route('professor.attendance-records.update', $record) }}" style="display:grid;gap:10px;">
            @csrf
            @method('PUT')

            <div>
                <label class="fl" for="status">Status</label>
                <select id="status" name="status" class="fi">
                    <option value="present" {{ old('status', $record->status) === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="late" {{ old('status', $record->status) === 'late' ? 'selected' : '' }}>Late</option>
                    <option value="absent" {{ old('status', $record->status) === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="excused" {{ old('status', $record->status) === 'excused' ? 'selected' : '' }}>Excused</option>
                </select>
            </div>

            <div>
                <label class="fl" for="recorded_at">Time Stamp</label>
                <input id="recorded_at" type="datetime-local" name="recorded_at" value="{{ old('recorded_at', optional($record->recorded_at)->tz('UTC')->setTimezone('Asia/Manila')->format('Y-m-d\\TH:i')) }}" class="fi">
            </div>

            <div>
                <label class="fl" for="minutes_late">Minutes Late</label>
                <input id="minutes_late" type="number" min="0" name="minutes_late" value="{{ old('minutes_late', $record->minutes_late) }}" class="fi">
            </div>

            <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;">
                <a href="{{ route('professor.attendance-records', ['class_id' => $record->class_id]) }}" class="btn">Cancel</a>
                <button type="submit" class="btn btn-p">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection