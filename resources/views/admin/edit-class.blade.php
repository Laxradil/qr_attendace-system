@extends('layouts.admin')

@section('title', 'Edit Class')
@section('header', 'Edit Class')
@section('subheader', 'Update class details and assigned professor.')

@section('content')
<div class="card" style="max-width:760px;">
    <form action="{{ route('admin.classes.update', $classe) }}" method="POST">
        @csrf
        @method('PUT')
        <label class="fl">Class Code *</label>
        <input class="fi" type="text" name="code" value="{{ $classe->code }}" maxlength="20" required>
        <div style="height:8px;"></div>

        <label class="fl">Class Name *</label>
        <input class="fi" type="text" name="name" value="{{ $classe->name }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Description</label>
        <textarea class="fi" name="description" rows="4">{{ $classe->description }}</textarea>
        <div style="height:8px;"></div>

        <label class="fl">Assigned Professor *</label>
        <select class="fi" name="professor_id" required>
            @foreach($professors as $professor)
                <option value="{{ $professor->id }}" {{ $classe->professor_id == $professor->id ? 'selected' : '' }}>{{ $professor->name }}</option>
            @endforeach
        </select>
        <div style="height:8px;"></div>

        @php
            $existingSchedule = old('schedule_days', isset($schedule) && $schedule ? explode(', ', $schedule->days) : []);
            $startTime = old('schedule_start_time', isset($schedule) && $schedule && $schedule->time ? explode(' - ', $schedule->time)[0] : '');
            $endTime = old('schedule_end_time', isset($schedule) && $schedule && $schedule->time ? (explode(' - ', $schedule->time)[1] ?? '') : '');
            $room = old('schedule_room', isset($schedule) && $schedule ? ($schedule->room ?? '') : '');
        @endphp

        <label class="fl">Class Schedule (optional)</label>
        <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;">
            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                    <input type="checkbox" name="schedule_days[]" value="{{ $day }}" {{ in_array($day, $existingSchedule) ? 'checked' : '' }}>
                    {{ $day }}
                </label>
            @endforeach
        </div>
        <div style="height:8px;"></div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
            <div>
                <label class="fl">Start Time</label>
                <input class="fi" type="time" name="schedule_start_time" value="{{ $startTime }}">
            </div>
            <div>
                <label class="fl">End Time</label>
                <input class="fi" type="time" name="schedule_end_time" value="{{ $endTime }}">
            </div>
        </div>
        <div style="height:8px;"></div>

        <label class="fl">Room</label>
        <input class="fi" type="text" name="schedule_room" value="{{ $room }}" maxlength="20" placeholder="Optional room or location">
        <div style="height:8px;"></div>

        <input type="hidden" name="is_active" value="0">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text2);margin:10px 0;">
            <input type="checkbox" name="is_active" value="1" {{ $classe->is_active ? 'checked' : '' }}> Active class
        </label>

        <div style="font-size:10px;color:var(--text3);margin-bottom:8px;">Enrolled students: {{ $classe->students->count() }}</div>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-p">Update Class</button>
            <a href="{{ route('admin.classes') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
