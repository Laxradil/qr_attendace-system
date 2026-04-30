@extends('layouts.admin')

@section('title', 'Edit User')
@section('header', 'Edit User')
@section('subheader', 'Update user profile, role, and account status.')

@section('content')
<div class="card" style="max-width:760px;">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <label class="fl">Full Name *</label>
        <input class="fi" type="text" name="name" value="{{ $user->name }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Email *</label>
        <input class="fi" type="email" name="email" value="{{ $user->email }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Username *</label>
        <input class="fi" type="text" name="username" value="{{ $user->username }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Role *</label>
        <select class="fi" name="role" required>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="professor" {{ $user->role == 'professor' ? 'selected' : '' }}>Professor</option>
            <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
        </select>
        <div style="height:8px;"></div>

        <label class="fl">Student ID</label>
        <input class="fi" type="text" name="student_id" value="{{ $user->student_id }}">
        <div style="height:8px;"></div>

        <label class="fl">New Password (optional)</label>
        <input class="fi" type="password" name="password">
        <div style="height:8px;"></div>

        <label class="fl">Confirm New Password</label>
        <input class="fi" type="password" name="password_confirmation">
        <div style="height:8px;"></div>

        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text2);margin-bottom:12px;">
            <input type="checkbox" name="is_active" {{ $user->is_active ? 'checked' : '' }}> Active account
        </label>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-p">Update User</button>
            <a href="{{ route('admin.users') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
