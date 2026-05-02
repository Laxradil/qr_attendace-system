@extends('layouts.admin')

@section('title', 'Create Class')
@section('header', 'Create Class')
@section('subheader', 'Create a class and assign a professor.')

@section('content')
<div class="card" style="max-width:760px;">
    <form action="{{ route('admin.classes.store') }}" method="POST">
        @csrf
        <label class="fl">Class Code *</label>
        <input class="fi" type="text" name="code" value="{{ old('code') }}" maxlength="20" required>
        <div style="height:8px;"></div>

        <label class="fl">Class Name *</label>
        <input class="fi" type="text" name="name" value="{{ old('name') }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Description</label>
        <textarea class="fi" name="description" rows="4">{{ old('description') }}</textarea>
        <div style="height:8px;"></div>

        <label class="fl">Assigned Professor *</label>
        <select class="fi" name="professor_id" required>
            <option value="">Select a professor...</option>
            @foreach($professors as $professor)
                <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>{{ $professor->name }}</option>
            @endforeach
        </select>
        <div style="height:8px;"></div>

        <label class="fl">Class Schedule (optional)</label>
        <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;">
            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                    <input type="checkbox" name="schedule_days[]" value="{{ $day }}" {{ is_array(old('schedule_days')) && in_array($day, old('schedule_days')) ? 'checked' : '' }}>
                    {{ $day }}
                </label>
            @endforeach
        </div>
        <div style="height:8px;"></div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
            <div>
                <label class="fl">Start Time</label>
                <input class="fi" type="time" name="schedule_start_time" value="{{ old('schedule_start_time') }}">
            </div>
            <div>
                <label class="fl">End Time</label>
                <input class="fi" type="time" name="schedule_end_time" value="{{ old('schedule_end_time') }}">
            </div>
        </div>
        <div style="height:8px;"></div>

        <label class="fl">Room</label>
        <input class="fi" type="text" name="schedule_room" value="{{ old('schedule_room') }}" maxlength="20" placeholder="Optional room or location">
        <div style="height:8px;"></div>

        <div style="display:flex;gap:8px;margin-top:14px;">
            <button type="submit" class="btn btn-p">Create Class</button>
            <a href="{{ route('admin.classes') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
