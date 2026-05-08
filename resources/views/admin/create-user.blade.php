@extends('layouts.admin-new')

@section('title', 'Create User - QR Attendance Admin')
@section('pageTitle', 'Create User')
@section('pageSubtitle', 'Add a new user account to the system.')

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
  <form action="{{ route('admin.users.store') }}" method="POST" class="form-grid">
    @csrf

    <div class="form-row">
      <div class="form-group">
        <label for="name">Full Name *</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
      </div>
      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="username">Username *</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Enter username" required>
      </div>
      <div class="form-group">
        <label for="role">Role *</label>
        <select id="role" name="role" required>
          <option value="">Select a role...</option>
          <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="professor" {{ old('role') == 'professor' ? 'selected' : '' }}>Professor</option>
          <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="student_id">Student ID</label>
        <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}" placeholder="Optional - for students only">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm Password *</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
      </div>
    </div>

    <div class="form-actions" style="margin-top:32px">
      <button type="submit" class="btn primary">✓ Create User</button>
      <a href="{{ route('admin.users') }}" class="btn">Cancel</a>
    </div>
  </form>
</div>

@endsection
