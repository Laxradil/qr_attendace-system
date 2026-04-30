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

        <div style="display:flex;gap:8px;margin-top:14px;">
            <button type="submit" class="btn btn-p">Create Class</button>
            <a href="{{ route('admin.classes') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
