@extends('layouts.admin-new')

@section('title', 'Settings - Admin')
@section('pageTitle', 'Settings')
@section('pageSubtitle', 'Manage your profile and account settings.')

@section('content')
<style>
  .settings-wrapper {
    max-width:1040px;
    margin:0 auto;
  }
  
  .settings-card {
    padding:32px;
    margin-bottom:20px;
  }
  
  .settings-section {
    margin-bottom:28px;
  }
  
  .settings-section:last-child {
    margin-bottom:0;
  }
  
  .settings-section h3 {
    font-size:16px;
    font-weight:800;
    margin-bottom:20px;
    letter-spacing:-.03em;
    color:#fff;
  }
  
  .settings-divider {
    height:1px;
    background:rgba(255,255,255,.08);
    margin:28px 0;
  }
  
  .settings-input {
    width:100%;
    padding:12px 14px;
    border-radius:var(--radius-md);
    background:rgba(8,12,30,.58);
    border:1px solid rgba(255,255,255,.12);
    color:#fff;
    font-size:13px;
    font-family:var(--font);
    outline:none;
    transition:border-color .2s ease, box-shadow .2s ease;
    margin-bottom:14px;
  }
  
  .settings-input:focus {
    border-color:rgba(143,91,255,.5);
    box-shadow:inset 0 0 0 2px rgba(143,91,255,.1),0 0 16px rgba(143,91,255,.2);
  }
  
  .settings-input:disabled {
    opacity:0.6;
    cursor:not-allowed;
  }
  
  .settings-input::placeholder {
    color:rgba(255,255,255,.3);
  }
  
  .form-group {
    display:grid;
    gap:14px;
  }
  
  .label {
    display:block;
    font-size:12px;
    font-weight:700;
    color:#fff;
    margin-bottom:6px;
    letter-spacing:.05em;
    text-transform:uppercase;
  }
  
  .error-text {
    font-size:11px;
    color:#ff8298;
    margin-top:4px;
  }
  
  .form-note {
    font-size:11px;
    color:rgba(255,255,255,.5);
    margin-top:4px;
  }
  
  .button-group {
    display:flex;
    gap:12px;
    margin-top:20px;
  }
  
  .settings-btn {
    padding:11px 22px;
    border-radius:10px;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    color:#fff;
    font-size:13px;
    font-family:var(--font);
    font-weight:600;
    outline:none;
    cursor:pointer;
    transition:.2s ease;
    flex:1;
  }
  
  .settings-btn:hover {
    transform:translateY(-2px);
    background:rgba(255,255,255,.13);
    border-color:rgba(255,255,255,.24);
  }
  
  .settings-btn.primary {
    background:linear-gradient(90deg,#8f5bff 0%,#5d63ff 45%,#2d68b8 100%);
    border-color:rgba(143,91,255,.5);
    color:#fff;
  }
  
  .settings-btn.primary:hover {
    box-shadow:inset 0 1px 0 rgba(255,255,255,.22),0 10px 26px rgba(78,88,255,.28);
  }
  
  .info-item {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px;
    background:rgba(255,255,255,.04);
    border-radius:12px;
    border:1px solid rgba(255,255,255,.07);
    margin-bottom:10px;
  }
  
  .info-item:last-child {
    margin-bottom:0;
  }
  
  .info-label {
    color:rgba(255,255,255,.5);
    font-size:13px;
  }
  
  .info-value {
    font-weight:700;
    letter-spacing:.02em;
  }
  
  .pill {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    border:1px solid rgba(255,255,255,.14);
  }
  
  .pill.green {
    color:#4dffa0;
    background:rgba(24,240,139,.11);
    border-color:rgba(24,240,139,.2);
  }
  
  .pill.red {
    color:#ff7f96;
    background:rgba(255,127,150,.11);
    border-color:rgba(255,127,150,.2);
  }
</style>

<div class="settings-wrapper">
  <div class="settings-card glass-table glass">
    <!-- Profile Settings -->
    <div class="settings-section">
      <h3>Profile Settings</h3>
      <form action="{{ route('admin.settings.update') ?? '#' }}" method="POST" class="form-group">
        @csrf
        @method('PUT')
        
        <!-- Full Name -->
        <div>
          <label class="label">Full Name</label>
          <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" required class="settings-input">
          @error('name')
            <div class="error-text">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Email -->
        <div>
          <label class="label">Email Address</label>
          <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" required class="settings-input">
          @error('email')
            <div class="error-text">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Username (read-only) -->
        <div>
          <label class="label">Username</label>
          <input type="text" value="{{ auth()->user()->username ?? 'N/A' }}" disabled class="settings-input">
          <div class="form-note">Cannot be changed</div>
        </div>
        
        <div class="button-group" style="margin-top:24px">
          <button type="submit" class="settings-btn primary">✓ Save Changes</button>
          <button type="reset" class="settings-btn">Reset</button>
        </div>
      </form>
    </div>
    
    <div class="settings-divider"></div>
    
    <!-- Change Password Section -->
    <div class="settings-section">
      <h3>Change Password</h3>
      <form action="{{ route('admin.settings.password') ?? '#' }}" method="POST" class="form-group">
        @csrf
        @method('PUT')
        
        <!-- New Password -->
        <div>
          <label class="label">New Password</label>
          <input type="password" name="password" placeholder="Leave blank to keep current" class="settings-input">
          @error('password')
            <div class="error-text">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Confirm Password -->
        <div>
          <label class="label">Confirm Password</label>
          <input type="password" name="password_confirmation" placeholder="Confirm new password" class="settings-input">
          @error('password_confirmation')
            <div class="error-text">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="button-group">
          <button type="submit" class="settings-btn primary">Update Password</button>
          <button type="reset" class="settings-btn">Clear</button>
        </div>
      </form>
    </div>
    
    <div class="settings-divider"></div>
    
    <!-- Account Information -->
    <div class="settings-section">
      <h3>Account Information</h3>
      <div>
        <div class="info-item">
          <span class="info-label">Account Type</span>
          <span class="pill red">{{ ucfirst(auth()->user()->role ?? 'user') }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">Account Status</span>
          <span class="pill {{ auth()->user()->is_active ? 'green' : 'red' }}">{{ auth()->user()->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">Account Created</span>
          <span class="info-value">{{ auth()->user()->created_at?->format('M d, Y') ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">Last Updated</span>
          <span class="info-value">{{ auth()->user()->updated_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
