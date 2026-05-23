@extends('layouts.admin-new')

@section('title', 'Settings - Admin')
@section('pageTitle', 'Settings')
@section('pageSubtitle', 'Manage your profile and account settings.')

@section('content')
<style>
  .settings-wrapper {
    max-width:1040px;
    margin:0 auto;
    border-radius:var(--radius-lg);
    overflow:hidden;
  }
  
  .settings-card {
    padding:32px;
    margin-bottom:20px;
    background: rgba(255,255,255,.055);
    border: 1px solid rgba(255,255,255,.10);
    border-radius: var(--radius-lg);
    box-shadow: none;
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
    background:#ffffff;
    border:1px solid #d1d5db;
    color:#0f172a;
    font-size:13px;
    font-family:var(--font);
    outline:none;
    transition:border-color .2s ease, box-shadow .2s ease;
    margin-bottom:14px;
  }
  
  .settings-input:focus {
    border-color:#3b82f6;
    box-shadow:0 0 0 2px rgba(59,130,246,.12);
  }
  
  .settings-input:disabled {
    opacity:0.6;
    cursor:not-allowed;
  }
  
  .settings-input::placeholder {
    color:#6b7280;
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
  
  .theme-option:hover {
    transform:translateY(-1px);
    border-color:rgba(255,255,255,.22);
  }
  
  .theme-option.selected {
    border-color:#6b73ff;
    box-shadow:0 0 0 2px rgba(107,115,255,.12);
    background:rgba(107,115,255,.12);
  }
  .theme-option.selected .theme-swatch {
    border-color:rgba(255,255,255,.95);
    box-shadow:0 0 0 1px rgba(255,255,255,.5);
  }
  
  .theme-swatch {
    width:34px;
    height:34px;
    border-radius:14px;
    flex-shrink:0;
    border:1px solid rgba(148,163,184,.45);
    box-shadow:inset 0 0 0 1px rgba(255,255,255,.05);
  }
  
  .theme-label {
    font-size:13px;
    font-weight:700;
  }
  
  .theme-light { background:#ffffff; }
  .theme-ash { background:#9ca3af; }
  .theme-dark { background:#1f2937; }
  .theme-onyx { background:#0f172a; }

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
    margin-bottom: 0;
    border: none;
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

  .unsaved-notice,
  body.theme-light .unsaved-notice,
  body.theme-ash .unsaved-notice {
    position: fixed;
    left: 50%;
    bottom: 24px;
    z-index: 2200;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    max-width: 520px;
    min-width: 360px;
    width: auto;
    transform: translate(-50%, 20px) scale(0.985);
    padding: 14px 18px;
    border-radius: 22px;
    background: rgba(18, 20, 42, 0.95) !important;
    border: 1px solid rgba(124,107,255,0.18) !important;
    color: #e6eef8 !important;
    font-size: 13px;
    font-weight: 800;
    box-shadow: 0 24px 60px rgba(6, 8, 22, 0.45) !important;
    opacity: 0;
    pointer-events: none;
    transition: opacity .22s cubic-bezier(.2,.9,.2,1), transform .22s cubic-bezier(.2,.9,.2,1);
    backdrop-filter: blur(20px) saturate(180%);
  }

  .unsaved-notice.show,
  body.theme-light .unsaved-notice.show,
  body.theme-ash .unsaved-notice.show {
    opacity: 1;
    transform: translate(-50%, 0) scale(1);
    pointer-events: auto;
  }

  .unsaved-notice.success,
  body.theme-light .unsaved-notice.success,
  body.theme-ash .unsaved-notice.success {
    background: rgba(24, 240, 139, 0.1) !important;
    border: 1px solid rgba(77, 255, 160, 0.3) !important;
  }

  .unsaved-notice.success .message {
    color: #4dffa0 !important;
  }

  .unsaved-notice.success .icon {
    background: rgba(77, 255, 160, 0.15) !important;
    color: #4dffa0 !important;
  }

  .unsaved-notice .notice-copy,
  body.theme-light .unsaved-notice .notice-copy,
  body.theme-ash .unsaved-notice .notice-copy {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
    flex: 1;
  }

  .unsaved-notice .message,
  body.theme-light .unsaved-notice .message,
  body.theme-ash .unsaved-notice .message {
    color: #e6eef8 !important;
    font-weight: 800;
    letter-spacing: -0.01em;
    display: block;
  }

  .unsaved-notice .icon,
  body.theme-light .unsaved-notice .icon,
  body.theme-ash .unsaved-notice .icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 5px;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 12px;
    flex-shrink: 0;
  }

  .unsaved-notice button,
  body.theme-light .unsaved-notice button,
  body.theme-ash .unsaved-notice button {
    all: unset;
    appearance: none;
    box-sizing: border-box;
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 100px;
    padding: 10px 18px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,0.12) !important;
    background: linear-gradient(180deg, #7c6bff, #5d4bf2) !important;
    color: #ffffff !important;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 14px 34px rgba(92,76,255,0.22);
    transition: transform .12s ease, box-shadow .12s ease, background .12s ease;
    text-align: center;
  }

  .unsaved-notice button:hover,
  body.theme-light .unsaved-notice button:hover,
  body.theme-ash .unsaved-notice button:hover {
    transform: translateY(-1px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 16px 38px rgba(92,76,255,0.26);
  }

  .unsaved-notice .btn-reset,
  body.theme-light .unsaved-notice .btn-reset,
  body.theme-ash .unsaved-notice .btn-reset {
    all: unset;
    appearance: none;
    box-sizing: border-box;
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 100px;
    padding: 10px 18px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,0.18) !important;
    background: rgba(255,255,255,0.08) !important;
    color: #e6eef8 !important;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: none !important;
    transition: transform .12s ease, background .12s ease;
    text-align: center;
  }

  .unsaved-notice .btn-reset:hover,
  body.theme-light .unsaved-notice .btn-reset:hover,
  body.theme-ash .unsaved-notice .btn-reset:hover {
    background: rgba(255,255,255,0.14) !important;
  }

  .unsaved-notice .actions,
  body.theme-light .unsaved-notice .actions,
  body.theme-ash .unsaved-notice .actions {
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }

  .unsaved-notice.success .actions {
    display: none;
  }

  .unsaved-notice button::after,
  body.theme-light .unsaved-notice button::after,
  body.theme-ash .unsaved-notice button::after {
    content: '';
    position: absolute;
    top: -6px;
    left: -6px;
    right: -6px;
    bottom: -6px;
    border-radius: 999px;
    border: 2px solid rgba(124,107,255,0.14);
    pointer-events: none;
    opacity: 0.92;
  }

  .unsaved-notice .actions { display: inline-flex; align-items: center; }
  .unsaved-notice .message { display: inline-block; }
</style>

<div class="unsaved-notice" id="settings-warning" role="alert" aria-live="assertive" hidden>
  <div class="notice-copy">
    <span class="message">Careful — you have unsaved changes!</span>
  </div>
  <div class="actions">
    <button type="button" id="unsaved-save-action">Save changes</button>
    <button type="button" id="unsaved-reset-action" class="btn-reset">Discard</button>
  </div>
</div>

<style>
  /* Ash theme overrides (admin) */
  body.theme-ash .settings-card,
  body.theme-ash .settings-wrapper {
    background: #f3f4f6;
    border: 1px solid #e6e9ee;
  }

  body.theme-ash .settings-input {
    background: #ffffff;
    border: 1px solid #d1d5db;
    color: #0b1220;
  }

  body.theme-ash .settings-input:focus {
    border-color: #6b7280;
    box-shadow: 0 0 0 3px rgba(107,115,255,.06);
  }

  body.theme-ash .settings-divider { background: #e6e9ee; }

  body.theme-ash .settings-btn { background: #ffffff; border: 1px solid #d1d5db; color: #0b1220; }
  body.theme-ash .settings-btn:hover { background: #f3f4f6; border-color: #c7ccd3; }
  body.theme-ash .settings-btn.primary { background: linear-gradient(90deg,#8f5bff,#5d63ff); border-color:#6b73ff; color:#fff; }

  body.theme-ash .settings-card,
  body.theme-ash .settings-wrapper,
  body.theme-ash .settings-section h3,
  body.theme-ash .settings-input,
  body.theme-ash .theme-label,
  body.theme-ash .info-value {
    color: #0f172a !important;
  }

  body.theme-ash .label,
  body.theme-ash .info-label,
  body.theme-ash .form-note,
  body.theme-ash .error-text {
    color: #475569 !important;
  }

  body.theme-ash .info-item { background: #ffffff; border: 1px solid #e6e9ee; }
  body.theme-ash .pill.green { color: #166534; background: #dcfce7; border-color: #bbf7d0; }

  /* In Ash theme make theme options neutral like Light: only selected option highlighted */
  body.theme-ash .theme-option {
    background: #ffffff !important;
    border: 1px solid rgba(15,23,42,.08) !important;
    box-shadow: none !important;
    color: #0b1220 !important;
  }

  body.theme-ash .theme-option:hover {
    border-color: rgba(15,23,42,.12) !important;
  }

  body.theme-ash .theme-option.selected {
    border-color: #6b73ff !important;
    box-shadow: 0 0 0 2px rgba(107,115,255,.12) !important;
    background: rgba(107,115,255,.12) !important;
    color: #0b1220 !important;
  }

  body.theme-ash .theme-swatch {
    border: 1px solid rgba(148,163,184,.45) !important;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.05) !important;
  }

  body.theme-ash .theme-option.selected .theme-swatch {
    border-color: rgba(255,255,255,.95) !important;
    box-shadow: 0 0 0 1px rgba(255,255,255,.5) !important;
  }
</style>

<style>
  /* Light theme solid overrides */
  body.theme-light .settings-card,
  body.theme-light .settings-wrapper {
    background: rgba(255,255,255,.055);
    border: 1px solid rgba(255,255,255,.10);
  }

  body.theme-light .settings-input {
    background: #f9fafb;
    border: 1px solid #d1d5db;
    color: #000000;
  }

  body.theme-light .settings-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
  }

  body.theme-light .settings-divider { background: #e5e7eb; }

  body.theme-light .settings-btn { background: #f9fafb; border: 1px solid #d1d5db; color: #000000; }
  body.theme-light .settings-btn:hover { background: #f3f4f6; border-color: #9ca3af; }
  body.theme-light .settings-btn.primary { background: linear-gradient(90deg,#8f5bff 0%,#5d63ff 45%,#2d68b8 100%); border-color:#3b82f6; color:#fff; }

  body.theme-light .info-item { background: #f9fafb; border: 1px solid #e5e7eb; }
  body.theme-light .info-label { color: #6b7280; }
  body.theme-light .pill.green { color: #166534; background: #dcfce7; border-color: #bbf7d0; }

  body.theme-light .settings-section h3,
  body.theme-light .label,
  body.theme-light .form-note,
  body.theme-light .error-text,
  body.theme-light .theme-label,
  body.theme-light .info-value,
  body.theme-light .theme-option {
    color: #0f172a !important;
  }
  body.theme-light .theme-option:hover { color: #0f172a !important; }
</style>
 
<div class="settings-wrapper">
  <div class="settings-card">
    <!-- Profile Settings -->
    <div class="settings-section">
      <h3>Profile Settings</h3>
      <form id="profile-settings-form" action="{{ route('admin.settings.update') ?? '#' }}" method="POST" class="form-group">
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

        @php
          $theme = old('theme', auth()->user()->theme ?? 'light');
          if (!in_array($theme, ['light','onyx'])) {
            $theme = 'light';
          }
        @endphp
        <div>
          <label class="label">Theme</label>
          <input type="hidden" name="theme" id="theme-input" value="{{ $theme }}">
          <div class="theme-grid">
            <button type="button" class="theme-option {{ $theme === 'light' ? 'selected' : '' }}" data-theme="light">
              <span class="theme-swatch theme-light"></span>
              <span class="theme-label">Light</span>
            </button>
            <button type="button" class="theme-option {{ $theme === 'onyx' ? 'selected' : '' }}" data-theme="onyx">
              <span class="theme-swatch theme-onyx"></span>
              <span class="theme-label">Onyx</span>
            </button>
          </div>
          <div class="form-note">Choose between Light and Onyx.</div>
        </div>

      </form>

      <form id="theme-save-form" action="{{ route('admin.settings.update') }}" method="POST" style="display:none;margin-top:12px">
        @csrf
        @method('PUT')
        <input type="hidden" name="theme" id="theme-only-input" value="{{ $theme }}">
        <button type="submit" id="theme-save-btn" class="settings-btn primary">Save Theme</button>
      </form>
    </div>
    
    <div class="settings-divider"></div>
    
    <!-- Change Password Section -->
    <div class="settings-section">
      <h3>Change Password</h3>
      <form id="password-settings-form" action="{{ route('admin.settings.password') ?? '#' }}" method="POST" class="form-group">
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const themeKey = 'qr_attendance_theme';
    const themeInput = document.getElementById('theme-input');
    const themeButtons = document.querySelectorAll('.theme-option');
    const themeSaveForm = document.getElementById('theme-save-form');
    const profileSettingsForm = document.getElementById('profile-settings-form');
    const passwordSettingsForm = document.getElementById('password-settings-form');
    const warningBox = document.getElementById('settings-warning');
    let unsavedSettings = false;
    let warningTimeout = null;
    let activeForm = null;
    let themeChanged = false;

    if (!themeInput || !themeButtons.length) return;

    const applyTheme = function (theme) {
      const validThemes = ['light','onyx'];
      const activeTheme = validThemes.includes(theme) ? theme : 'onyx';
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + activeTheme);
      themeInput.value = activeTheme;
      const themeOnlyInput = document.getElementById('theme-only-input');
      if (themeOnlyInput) themeOnlyInput.value = activeTheme;
      themeButtons.forEach(function (option) {
        option.classList.toggle('selected', option.dataset.theme === activeTheme);
      });
      return activeTheme;
    };

    const savedTheme = localStorage.getItem(themeKey) || themeInput.value;
    const currentTheme = applyTheme(savedTheme);
    localStorage.setItem(themeKey, currentTheme);

    const showUnsavedWarning = function (message) {
      if (!warningBox) return;
      warningBox.hidden = false;
      warningBox.classList.add('show');
      warningBox.querySelector('.message').textContent = message;
      clearTimeout(warningTimeout);
      warningTimeout = setTimeout(function () {
        if (!unsavedSettings) {
          warningBox.classList.remove('show');
        }
      }, 4200);
      bindWarningActions();
    };

    const hideUnsavedWarning = function () {
      if (!warningBox) return;
      warningBox.classList.remove('show');
    };

    const bindWarningActions = function () {
      const saveButton = document.getElementById('unsaved-save-action');
      if (saveButton && !saveButton.dataset.bound) {
        saveButton.dataset.bound = 'true';
        saveButton.addEventListener('click', function () {
          if (!warningBox) return;
          warningBox.classList.remove('show');
          warningBox.classList.add('success');
          warningBox.querySelector('.message').textContent = 'Saving changes...';
          warningBox.classList.add('show');
          unsavedSettings = false;
          localStorage.setItem('settings_saved', 'true');
          if (activeForm) {
            activeForm.submit();
          } else if (profileSettingsForm) {
            profileSettingsForm.submit();
          }
        });
      }

      const resetButton = document.getElementById('unsaved-reset-action');
      if (resetButton && !resetButton.dataset.bound) {
        resetButton.dataset.bound = 'true';
        resetButton.addEventListener('click', function () {
          if (activeForm && activeForm !== themeSaveForm) {
            activeForm.reset();
          }
          if (themeSaveForm) {
            themeSaveForm.style.display = 'none';
          }
          if (themeChanged) {
            const themeInputValue = localStorage.getItem(themeKey) || themeInput.value;
            applyTheme(themeInputValue);
          }
          unsavedSettings = false;
          activeForm = null;
          themeChanged = false;
          hideUnsavedWarning();
        });
      }
    };

    if (localStorage.getItem('settings_saved') === 'true') {
      localStorage.removeItem('settings_saved');
      if (warningBox) {
        warningBox.classList.add('success', 'show');
        warningBox.querySelector('.message').textContent = 'Changes saved successfully! ✓';
        setTimeout(function () {
          warningBox.classList.remove('show', 'success');
        }, 2500);
      }
    }

    const markUnsaved = function (form, themeOnly = false) {
      unsavedSettings = true;
      activeForm = form;
      themeChanged = themeOnly;
      if (themeSaveForm) {
        themeSaveForm.style.display = themeOnly ? '' : 'none';
      }
      showUnsavedWarning('Careful — you have unsaved changes!');
    };

    themeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const theme = button.dataset.theme;
        const selectedTheme = applyTheme(theme);
        localStorage.setItem(themeKey, selectedTheme);
        if (themeSaveForm) {
          themeSaveForm.style.display = '';
        }
        markUnsaved(themeSaveForm, true);
      });
    });

    [profileSettingsForm, passwordSettingsForm].filter(Boolean).forEach(function (form) {
      form.addEventListener('input', function () {
        markUnsaved(form);
      });
      form.addEventListener('change', function () {
        markUnsaved(form);
      });
      form.addEventListener('submit', function () {
        unsavedSettings = false;
        hideUnsavedWarning();
        if (themeSaveForm) {
          themeSaveForm.style.display = 'none';
        }
        activeForm = null;
        themeChanged = false;
      });
    });
  });
</script>

@endsection
