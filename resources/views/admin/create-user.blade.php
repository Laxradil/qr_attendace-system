@extends('layouts.admin')

@section('title', 'Create User')
@section('header', 'Create User')
@section('subheader', 'Add a new user account to the system.')

@section('content')
<div class="card" style="max-width:760px;">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <label class="fl">Full Name *</label>
        <input class="fi" type="text" name="name" value="{{ old('name') }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Email *</label>
        <input class="fi" type="email" name="email" value="{{ old('email') }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Username *</label>
        <input class="fi" type="text" name="username" value="{{ old('username') }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Role *</label>
        <select class="fi" name="role" required>
            <option value="">Select a role...</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="professor" {{ old('role') == 'professor' ? 'selected' : '' }}>Professor</option>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
        </select>
        <div style="height:8px;"></div>

        <label class="fl">Student ID</label>
        <input class="fi" type="text" name="student_id" value="{{ old('student_id') }}">
        <div style="height:8px;"></div>

        <label class="fl">Password *</label>
        <input class="fi" type="password" name="password" required>
        <div style="height:8px;"></div>

        <label class="fl">Confirm Password *</label>
        <input class="fi" type="password" name="password_confirmation" required>

        <div style="display:flex;gap:8px;margin-top:14px;">
            <button type="submit" class="btn btn-p">Create User</button>
            <a href="{{ route('admin.users') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
