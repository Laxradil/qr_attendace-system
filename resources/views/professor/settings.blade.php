@extends('layouts.professor')

@section('title', 'Settings - Professor')
@section('header', 'My Settings')
@section('subheader', 'Manage your profile and account settings')

@section('content')
<div style="max-width:600px">
  <!-- Profile Settings -->
  <div class="glass" style="border-radius:var(--radius-lg);padding:24px;margin-bottom:20px">
    <h3 style="font-size:16px;font-weight:800;margin-bottom:16px">Profile Settings</h3>
    
    <form action="{{ route('professor.settings.update') ?? '#' }}" method="POST" style="display:grid;gap:14px">
      @csrf
      @method('PUT')

      <!-- Full Name -->
      <div>
        <label style="display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;letter-spacing:.05em;text-transform:uppercase">
          Full Name
        </label>
        <input type="text" name="name" value="{{ $user->name ?? '' }}" required class="settings-input">
        @error('name')
          <div style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <!-- Email -->
      <div>
        <label style="display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;letter-spacing:.05em;text-transform:uppercase">
          Email Address
        </label>
        <input type="email" name="email" value="{{ $user->email ?? '' }}" required class="settings-input">
        @error('email')
          <div style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <!-- Username (read-only) -->
      <div>
        <label style="display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;letter-spacing:.05em;text-transform:uppercase">
          Username
        </label>
        <input type="text" value="{{ $user->username ?? 'N/A' }}" disabled class="settings-input" style="opacity:0.6;cursor:not-allowed">
        <div style="font-size:11px;color:var(--muted);margin-top:4px">Cannot be changed</div>
      </div>
    </form>
  </div>

  <!-- Change Password Section -->
  <div class="glass" style="border-radius:var(--radius-lg);padding:24px;margin-bottom:20px">
    <h3 style="font-size:16px;font-weight:800;margin-bottom:16px">Change Password</h3>
    
    <form action="{{ route('professor.settings.password') ?? '#' }}" method="POST" style="display:grid;gap:14px">
      @csrf
      @method('PUT')

      <!-- New Password -->
      <div>
        <label style="display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;letter-spacing:.05em;text-transform:uppercase">
          New Password
        </label>
        <input type="password" name="password" placeholder="Leave blank to keep current" class="settings-input">
        @error('password')
          <div style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div>
        <label style="display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;letter-spacing:.05em;text-transform:uppercase">
          Confirm Password
        </label>
        <input type="password" name="password_confirmation" placeholder="Confirm new password" class="settings-input">
        @error('password_confirmation')
          <div style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
      </div>

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="settings-btn primary">Update Password</button>
        <button type="reset" class="settings-btn">Clear</button>
      </div>
    </form>
  </div>

  <!-- Account Information -->
  <div class="glass" style="border-radius:var(--radius-lg);padding:24px">
    <h3 style="font-size:16px;font-weight:800;margin-bottom:16px">Account Information</h3>
    
    <div style="display:grid;gap:12px">
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(255,255,255,.04);border-radius:10px;border:1px solid rgba(255,255,255,.07)">
        <span style="color:var(--muted);font-size:13px">Role</span>
        <strong style="font-weight:700;letter-spacing:.02em">Professor</strong>
      </div>
      
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(255,255,255,.04);border-radius:10px;border:1px solid rgba(255,255,255,.07)">
        <span style="color:var(--muted);font-size:13px">Status</span>
        <span class="pill green" style="display:inline-block">Active</span>
      </div>
      
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(255,255,255,.04);border-radius:10px;border:1px solid rgba(255,255,255,.07)">
        <span style="color:var(--muted);font-size:13px">Member Since</span>
        <strong style="font-weight:700;font-family:var(--mono);font-size:12px">{{ $user->created_at->format('M d, Y') ?? 'N/A' }}</strong>
      </div>
    </div>
  </div>
</div>

<style>
  .settings-input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 12px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13px;
    font-family: var(--font);
    outline: none;
    transition: .2s ease;
  }
  
  .settings-input:focus {
    border-color: rgba(139,92,255,.5);
    box-shadow: 0 0 0 3px rgba(139,92,255,.12);
  }
  
  .settings-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  
  .settings-btn {
    padding: 10px 16px;
    border-radius: 12px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13px;
    font-family: var(--font);
    font-weight: 600;
    outline: none;
    cursor: pointer;
    transition: .2s ease;
  }
  
  .settings-btn:hover {
    transform: translateY(-2px);
    background: rgba(255,255,255,.13);
    border-color: rgba(255,255,255,.24);
  }
  
  .settings-btn.primary {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));
    border-color: rgba(139,92,255,.5);
    color: #fff;
  }
  
  .settings-btn.primary:hover {
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25), 0 10px 28px rgba(80,94,255,.38);
  }
  
  .pill {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,.14);
  }
  
  .pill.green {
    color: #4dffa0;
    background: rgba(24,240,139,.11);
    border-color: rgba(24,240,139,.2);
  }
</style>
@endsection
