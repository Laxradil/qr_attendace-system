@extends('layouts.student')

@section('title', 'Settings & Profile')
@section('header', 'My Settings')
@section('subheader', 'Manage your profile and account settings')

@section('content')
<style>
  .search-bar { display: none !important; }
  .settings-container { max-width: 1040px; margin: 0 auto; background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.10); border-radius: 28px; padding: 32px; }
  .settings-section { margin-bottom: 28px; }
  .settings-section:last-child { margin-bottom: 0; }
  .settings-section h3 { font-size: 15px; font-weight: 800; margin-bottom: 18px; letter-spacing: -.03em; }
  .settings-divider { height: 1px; background: rgba(255,255,255,.08); margin: 28px 0; }
  .settings-input { width: 100%; padding: 11px 14px; border-radius: 13px; background: #ffffff; border: 1px solid #d1d5db; color: #0f172a; font-size: 13px; font-family: var(--font); outline: none; transition: .2s ease; margin-bottom: 14px; }
  .settings-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
  .settings-input:disabled { opacity: 0.6; cursor: not-allowed; }
  .settings-input::placeholder { color: #6b7280; }
  .form-group { display: grid; gap: 14px; }
  .label { display: block; font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 6px; letter-spacing: .05em; text-transform: uppercase; }
  .error-text { font-size: 11px; color: #ff8298; margin-top: 4px; }
  .form-note { font-size: 11px; color: var(--muted); margin-top: 4px; }
  .theme-grid {
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:12px;
  }
  .theme-option {
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px;
    border-radius:16px;
    border:1px solid rgba(255,255,255,.12);
    background:rgba(255,255,255,.05);
    color:inherit;
    cursor:pointer;
    transition:transform .2s ease,border-color .2s ease,background .2s ease;
    width:100%;
    text-align:left;
  }
  .theme-option:hover { transform:translateY(-1px); border-color:rgba(255,255,255,.22); }
  .theme-option.selected { border-color:#6b73ff; box-shadow:0 0 0 2px rgba(107,115,255,.12); background:rgba(107,115,255,.12); }
  .theme-option.selected .theme-swatch { border-color:rgba(255,255,255,.95); box-shadow:0 0 0 1px rgba(255,255,255,.5); }
  .theme-swatch { width:34px; height:34px; border-radius:14px; flex-shrink:0; border:1px solid rgba(148,163,184,.45); box-shadow:inset 0 0 0 1px rgba(255,255,255,.05); }
  .theme-label { font-size:13px; font-weight:700; }
  .theme-light { background:#ffffff; }
  .theme-ash { background:#9ca3af; }
  .theme-dark { background:#1f2937; }
  .theme-onyx { background:#0f172a; }
  .button-group { display: flex; gap: 10px; margin-top: 16px; }
  .settings-btn { padding: 11px 20px; border-radius: 999px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); color: var(--text); font-size: 13px; font-family: var(--font); font-weight: 600; outline: none; cursor: pointer; transition: .2s ease; flex: 1; }
  .settings-btn:hover { transform: translateY(-2px); background: rgba(255,255,255,.13); border-color: rgba(255,255,255,.24); }
  .settings-btn.primary { background: linear-gradient(135deg,rgba(67,166,255,.88),rgba(139,92,255,.5)); border-color: rgba(67,166,255,.5); color: #fff; }
  .settings-btn.primary:hover { box-shadow: inset 0 1px 0 rgba(255,255,255,.25), 0 10px 28px rgba(80,94,255,.18); }
  .info-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(255,255,255,.04); border-radius: 12px; border: 1px solid rgba(255,255,255,.07); margin-bottom: 10px; }
  .info-item:last-child { margin-bottom: 0; }
  .info-label { color: var(--muted); font-size: 13px; }
  .info-value { font-weight: 700; letter-spacing: .02em; }
  .pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; border: 1px solid rgba(255,255,255,.14); }
  .pill.green { color: #4dffa0; background: rgba(24,240,139,.11); border-color: rgba(24,240,139,.2); }
</style>

<div class="settings-container">
  <!-- Profile Settings -->
  <div class="settings-section">
    <h3>Profile Settings</h3>
    <form action="{{ route('student.settings.update') ?? '#' }}" method="POST" class="form-group">
      @csrf
      @method('PUT')
      <!-- Full Name -->
      <div>
        <label class="label">Full Name</label>
        <input type="text" name="name" value="{{ $user->name ?? '' }}" required class="settings-input">
        @error('name')
          <div class="error-text">{{ $message }}</div>
        @enderror
      </div>
      <!-- Email -->
      <div>
        <label class="label">Email Address</label>
        <input type="email" name="email" value="{{ $user->email ?? '' }}" required class="settings-input">
        @error('email')
          <div class="error-text">{{ $message }}</div>
        @enderror
      </div>
      <!-- Username (read-only) -->
      <div>
        <label class="label">Username</label>
        <input type="text" value="{{ $user->username ?? 'N/A' }}" disabled class="settings-input" style="opacity:0.6;cursor:not-allowed;margin-bottom:0">
        <div class="form-note">Cannot be changed</div>
      </div>

      @php $theme = old('theme', $user->theme ?? 'light'); @endphp
      <div>
        <label class="label">Theme</label>
        <input type="hidden" name="theme" id="theme-input" value="{{ $theme }}">
        <div class="theme-grid">
          <button type="button" class="theme-option {{ $theme === 'light' ? 'selected' : '' }}" data-theme="light">
            <span class="theme-swatch theme-light"></span>
            <span class="theme-label">Light</span>
          </button>
          <button type="button" class="theme-option {{ $theme === 'ash' ? 'selected' : '' }}" data-theme="ash">
            <span class="theme-swatch theme-ash"></span>
            <span class="theme-label">Ash</span>
          </button>
          <button type="button" class="theme-option {{ $theme === 'dark' ? 'selected' : '' }}" data-theme="dark">
            <span class="theme-swatch theme-dark"></span>
            <span class="theme-label">Dark</span>
          </button>
          <button type="button" class="theme-option {{ $theme === 'onyx' ? 'selected' : '' }}" data-theme="onyx">
            <span class="theme-swatch theme-onyx"></span>
            <span class="theme-label">Onyx</span>
          </button>
        </div>
        <div class="form-note">Choose between Light, Ash, Dark and Onyx.</div>
      </div>
    </form>
  </div>
  <div class="settings-divider"></div>
  <!-- Change Password Section -->
  <div class="settings-section">
    <h3>Change Password</h3>
    <form action="{{ route('student.settings.password') ?? '#' }}" method="POST" class="form-group">
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
        <span class="info-label">Role</span>
        <strong class="info-value">Student</strong>
      </div>
      <div class="info-item">
        <span class="info-label">Status</span>
        <span class="pill green">Active</span>
      </div>
      <div class="info-item">
        <span class="info-label">Member Since</span>
        <strong class="info-value" style="font-family:var(--mono);font-size:12px">{{ $user->created_at->format('M d, Y') ?? 'N/A' }}</strong>
      </div>
    </div>
  </div>
</div>

<style>
  /* Light theme solid overrides */
  body.theme-light .settings-container {
    background: #ffffff;
    border: 1px solid #e5e7eb;
  }
  
  body.theme-light .settings-input {
    background: #f9fafb;
    border: 1px solid #d1d5db;
    color: #000000;
  }
  
  body.theme-light .settings-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }
  
  body.theme-light .theme-option {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
  }
  
  body.theme-light .theme-option:hover {
    border-color: #d1d5db;
  }
  
  body.theme-light .theme-option.selected {
    border-color: #6b73ff;
    box-shadow: 0 0 0 2px rgba(107, 115, 255, 0.1);
    background: #eef2ff;
  }
  
  body.theme-light .settings-divider {
    background: #e5e7eb;
  }
  
  body.theme-light .settings-btn {
    background: #f9fafb;
    border: 1px solid #d1d5db;
    color: #000000;
  }
  
  body.theme-light .settings-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
  }
  
  body.theme-light .settings-btn.primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cff);
    border-color: #3b82f6;
    color: #ffffff;
  }
  
  body.theme-light .settings-btn.primary:hover {
    box-shadow: 0 10px 28px rgba(80, 94, 255, 0.2);
  }
  
  body.theme-light .info-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
  }
  
  body.theme-light .pill.green {
    color: #166534;
    background: #dcfce7;
    border-color: #bbf7d0;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const themeKey = 'qr_attendance_theme';
    const themeInput = document.getElementById('theme-input');
    const themeButtons = document.querySelectorAll('.theme-option');
    if (!themeInput || !themeButtons.length) return;

    const applyTheme = function (theme) {
      const validThemes = ['light','ash','dark','onyx'];
      const activeTheme = validThemes.includes(theme) ? theme : 'dark';
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + activeTheme);
      themeInput.value = activeTheme;
      themeButtons.forEach(function (option) {
        option.classList.toggle('selected', option.dataset.theme === activeTheme);
      });
      return activeTheme;
    };

    const savedTheme = localStorage.getItem(themeKey);
    const currentTheme = applyTheme(savedTheme);
    localStorage.setItem(themeKey, currentTheme);

    themeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const theme = button.dataset.theme;
        const selectedTheme = applyTheme(theme);
        localStorage.setItem(themeKey, selectedTheme);
      });
    });
  });
</script>

@endsection
