@extends('layouts.professor')

@section('title', 'Settings - Professor')
@section('header', 'My Settings')
@section('subheader', 'Manage your profile and account settings')

@section('content')
<div class="content" style="max-width:600px;">
    <div class="card">
        <div class="sh">Profile Settings</div>
        
        <form action="{{ route('professor.settings.update') }}" method="POST" style="display:grid;gap:12px;">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="fl">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="fi">
                @error('name')
                    <div class="alert alert-error" style="margin-top:4px;padding:6px 10px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="fl">Email Address</label>
                <input type="email" name="email" value="{{ $user->email }}" required class="fi">
                @error('email')
                    <div class="alert alert-error" style="margin-top:4px;padding:6px 10px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username (read-only) -->
            <div>
                <label class="fl">Username</label>
                <input type="text" value="{{ $user->username }}" disabled class="fi" style="opacity:0.6;cursor:not-allowed;">
                <div style="font-size:9px;color:var(--text3);margin-top:3px;">Cannot be changed</div>
            </div>

            <!-- Change Password Section -->
            <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:8px;">
                <div class="sh" style="margin-top:0;">Change Password</div>

                <!-- New Password -->
                <div>
                    <label class="fl">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" class="fi">
                    @error('password')
                        <div class="alert alert-error" style="margin-top:4px;padding:6px 10px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="fl">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm new password" class="fi">
                </div>
            </div>

            <!-- Account Info (read-only) -->
            <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:8px;">
                <div class="sh" style="margin-top:0;">Account Information</div>
                <div style="font-size:11px;space-y:1px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid var(--border2);">
                        <span style="color:var(--text2);">Role:</span>
                        <span style="font-weight:600;">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid var(--border2);">
                        <span style="color:var(--text2);">Status:</span>
                        <span class="badge {{ $user->is_active ? 'bg' : 'br' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;">
                        <span style="color:var(--text2);">Member Since:</span>
                        <span style="font-weight:600;">{{ $user->created_at->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:12px;display:flex;gap:8px;">
                <button type="submit" class="btn btn-p" style="flex:1;justify-content:center;">Save Changes</button>
                <a href="{{ route('professor.dashboard') }}" class="btn" style="flex:1;justify-content:center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
