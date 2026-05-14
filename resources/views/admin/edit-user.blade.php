@extends('layouts.admin-new')

@section('title', 'Edit User - QR Attendance Admin')
@section('pageTitle', 'Edit User')
@section('pageSubtitle', 'Update user profile, role, and account status.')

@section('content')
<style>
  .form-grid{
    display:grid;
    gap:16px;
  }
  .form-group{
    display:flex;
    flex-direction:column;
    gap:6px;
  }
  .form-group label{
    font-size:13px;
    font-weight:700;
    letter-spacing:.01em;
    color:#fff;
    text-transform:uppercase;
  }
  .form-group input,
  .form-group select{
    padding:12px 14px;
    border-radius:var(--radius-md);
    border:1px solid rgba(255,255,255,.12);
    background:rgba(8,12,30,.58);
    color:#fff;
    font-size:13px;
    transition:border-color .2s ease, box-shadow .2s ease;
  }
  .form-group input:focus,
  .form-group select:focus{
    outline:none;
    border-color:rgba(143,91,255,.5);
    box-shadow:inset 0 0 0 2px rgba(143,91,255,.1),0 0 16px rgba(143,91,255,.2);
  }
  .form-group input::placeholder{
    color:rgba(255,255,255,.3);
  }
  .form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
  }
  @media (max-width: 760px){
    .form-row{
      grid-template-columns:1fr;
    }
  }
  .form-actions{
    display:flex;
    gap:12px;
    margin-top:24px;
  }
  .checkbox-group{
    display:flex;
    align-items:center;
    gap:8px;
    padding:12px 14px;
    border-radius:var(--radius-md);
    border:1px solid rgba(255,255,255,.12);
    background:rgba(8,12,30,.58);
    width:fit-content;
  }
  .checkbox-group input[type="checkbox"]{
    width:16px;
    height:16px;
    cursor:pointer;
    accent-color:#8f5bff;
  }
  .checkbox-group label{
    margin:0;
    font-size:13px;
    cursor:pointer;
    text-transform:none;
  }
</style>

<div class="card glass" style="margin-bottom:16px">
  <div class="mini-grid">
    <div class="mini">
      <div class="mini-icon stat-icon blue" style="width:38px;height:38px;border-radius:12px;font-size:16px">👥</div>
      <div><b>{{ App\Models\User::count() }}</b><small>Total Users</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon red" style="width:38px;height:38px;border-radius:12px;font-size:16px">🛡</div>
      <div><b>{{ App\Models\User::where('role', 'admin')->count() }}</b><small>Administrators</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon yellow" style="width:38px;height:38px;border-radius:12px;font-size:16px">🎓</div>
      <div><b>{{ App\Models\User::where('role', 'professor')->count() }}</b><small>Professors</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon purple" style="width:38px;height:38px;border-radius:12px;font-size:16px">🧑‍🎓</div>
      <div><b>{{ App\Models\User::where('role', 'student')->count() }}</b><small>Students</small></div>
    </div>
  </div>
</div>

<div class="glass-table glass" style="padding:32px">
  <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-grid">
    @csrf
    @method('PUT')

    <div class="form-row">
      <div class="form-group">
        <label for="name">Full Name *</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}" placeholder="Enter full name" required>
      </div>
      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}" placeholder="Enter email address" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="username">Username *</label>
        <input type="text" id="username" name="username" value="{{ $user->username }}" placeholder="Enter username" required>
      </div>
      <div class="form-group">
        <label for="role">Role *</label>
        <select id="role" name="role" required>
          <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="professor" {{ $user->role == 'professor' ? 'selected' : '' }}>Professor</option>
          <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="student_id">Student ID</label>
        <input type="text" id="student_id" name="student_id" value="{{ $user->student_id }}" placeholder="Optional - for students only">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="password">New Password (optional)</label>
        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
      </div>
    </div>

    <div class="form-group">
      <input type="hidden" name="is_active" value="0">
      <div class="checkbox-group">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
        <label for="is_active">Active account</label>
      </div>
    </div>

    <div class="form-actions" style="margin-top:32px">
      <button type="submit" class="btn primary">✓ Update User</button>
      <a href="{{ route('admin.users') }}" class="btn">Cancel</a>
    </div>
  </form>
</div>

@endsection
