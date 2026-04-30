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

        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text2);margin:10px 0;">
            <input type="checkbox" name="is_active" {{ $classe->is_active ? 'checked' : '' }}> Active class
        </label>

        <div style="font-size:10px;color:var(--text3);margin-bottom:8px;">Enrolled students: {{ $classe->students->count() }}</div>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-p">Update Class</button>
            <a href="{{ route('admin.classes') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
