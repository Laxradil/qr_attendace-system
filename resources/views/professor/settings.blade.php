@extends('layouts.professor')

@section('title', 'Settings - Professor')
@section('header', 'My Settings')
@section('subheader', 'Manage your profile and account settings')

@section('content')
<div id="settings-warning" class="unsaved-notice" role="alert" aria-live="assertive">
  <span class="icon" aria-hidden="true">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M12 2L2 20h20L12 2z" fill="rgba(255,255,255,0.95)"/>
      <path d="M11 10h2v5h-2zM11 16h2v2h-2z" fill="#0f172a"/>
    </svg>
  </span>
  <div class="notice-copy">
    <span class="message">Careful — you have unsaved changes!</span>
  </div>
  <div class="actions">
    <button type="button" id="unsaved-reset-action" class="btn-reset">Reset</button>
    <button type="button" id="unsaved-save-action" class="btn-save">Save changes</button>
  </div>
</div>
<style>
  .search-bar {
    display: none !important;
  }
  
  .settings-container {
    max-width: 1040px;
    margin: 0 auto;
    background: #4a4a4a;
    border: 1px solid rgba(255,255,255,.45);
    border-radius: 28px;
    padding: 32px;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.12), 0 0 0 1px rgba(0,0,0,.12);
    color: #ffffff;
  }
  
  .settings-section {
    margin-bottom: 28px;
  }
  
  .settings-section:last-child {
    margin-bottom: 0;
  }
  
  .settings-section h3 {
    font-size: 15px;
    font-weight: 800;
    margin-bottom: 18px;
    letter-spacing: -.03em;
    color: #ffffff;
  }
  
  .settings-divider {
    height: 1px;
    background: rgba(255,255,255,.08);
    margin: 28px 0;
  }
  
  .settings-input {
    width: 100%;
    padding: 11px 14px;
    border-radius: 13px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.18);
    color: #ffffff;
    font-size: 13px;
    font-family: var(--font);
    outline: none;
    transition: .2s ease;
    margin-bottom: 14px;
  }
  
  .settings-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
  }
  
  .settings-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  
  .settings-input::placeholder {
    color: rgba(255,255,255,.75);
  }
  
  .form-group {
    display: grid;
    gap: 14px;
  }
  
  .label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 6px;
    letter-spacing: .05em;
    text-transform: uppercase;
  }
  
  .error-text {
    font-size: 11px;
    color: #ff8298;
    margin-top: 4px;
  }
  
  .form-note {
    font-size: 11px;
    color: rgba(255,255,255,.85);
    margin-top: 4px;
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
    border:1px solid rgba(255,255,255,.35);
    background:rgba(255,255,255,.14);
    color:#ffffff;
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
    border-color:rgba(255,255,255,.24);
    box-shadow:0 0 0 2px rgba(255,255,255,.08);
    background:rgba(255,255,255,.14);
    color:#ffffff;
  }
  .theme-option.selected .theme-swatch {
    border-color:rgba(255,255,255,.85);
    box-shadow:0 0 0 1px rgba(255,255,255,.16);
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
    color: #ffffff;
  }
  
  .theme-light { background:#ffffff; }
  .theme-ash { background:#9ca3af; }
  .theme-dark { background:#1f2937; }
  .theme-onyx { background:#0f172a; }

  /* Ash-specific visual tweaks: make theme options and buttons lighter */
  body.theme-ash .theme-option {
    background: rgba(255,255,255,.12) !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    color: #ffffff !important;
  }
  body.theme-ash .theme-option.selected {
    background: rgba(255,255,255,.16) !important;
    border-color: rgba(255,255,255,.18) !important;
    box-shadow: none !important;
    color: #ffffff !important;
  }
  body.theme-ash .theme-swatch.theme-ash { background: #d1d5db !important; }

  body.theme-ash .settings-container,
  body.theme-ash .settings-container * {
    color: #ffffff !important;
  }
  body.theme-ash .info-label { color: #ffffff !important; }

  body.theme-ash .settings-btn {
    background: rgba(255,255,255,.08) !important;
    border: 1px solid rgba(255,255,255,.14) !important;
    color: #ffffff !important;
  }
  body.theme-ash .settings-btn.primary {
    background: linear-gradient(135deg,#5f6770,#4b5563) !important;
    border-color: transparent !important;
    color: #ffffff !important;
  }

  .button-group {
    display: flex;
    gap: 10px;
    margin-top: 16px;
  }
  
  .settings-btn {
    padding: 11px 20px;
    border-radius: 999px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13px;
    font-family: var(--font);
    font-weight: 600;
    outline: none;
    cursor: pointer;
    transition: .2s ease;
    flex: 1;
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
  
  .info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: rgba(255,255,255,.04);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.07);
    margin-bottom: 10px;
  }
  
  .info-item:last-child {
    margin-bottom: 0;
  }
  
  .info-label {
    color: #ffffff;
    font-size: 13px;
  }
  
  .info-value {
    font-weight: 700;
    letter-spacing: .02em;
    color: #ffffff;
  }
  
  .pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid rgba(255,255,255,.14);
  }
  
  .pill.green {
    color: #4dffa0;
    background: rgba(24,240,139,.11);
    border-color: rgba(24,240,139,.2);
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

  .unsaved-notice button::after {
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

  @keyframes settings-shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
  }

  .shake {
    animation: settings-shake 320ms ease-in-out;
  }
</style>

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
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
  }

  body.theme-light .settings-divider { background: #e5e7eb; }

  body.theme-light .settings-btn {
    background: #f9fafb;
    border: 1px solid #d1d5db;
    color: #000000;
  }

  body.theme-light .settings-btn:hover { background: #f3f4f6; border-color: #9ca3af; }

  body.theme-light .settings-btn.primary { background: linear-gradient(135deg,#3b82f6,#8b5cff); border-color:#3b82f6; color:#fff; }

  body.theme-light .info-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
  }

  body.theme-light .info-label { color: #6b7280; }

  body.theme-light .pill.green { color: #166534; background: #dcfce7; border-color: #bbf7d0; }
</style>

<style>
  /* Ash theme overrides (similar to light but slightly muted) */
  body.theme-ash .settings-container {
    background: #4a4a4a;
    border: 1px solid rgba(255,255,255,.45);
  }

  body.theme-ash .settings-input {
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.18);
    color: #ffffff;
  }

  body.theme-ash .settings-input:focus {
    border-color: rgba(255,255,255,.12);
    box-shadow: 0 0 0 3px rgba(255,255,255,.03);
  }

  body.theme-ash .settings-divider { background: rgba(255,255,255,.04); }

  body.theme-ash .settings-btn { background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.04); color: var(--text); }
  body.theme-ash .settings-btn:hover { background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.06); }
  body.theme-ash .settings-btn.primary { background: linear-gradient(135deg,#374151,#4b5563); border-color:transparent; color:#fff; }

  body.theme-ash .info-item { background: rgba(255,255,255,.02); border: 1px solid rgba(255,255,255,.04); }
  body.theme-ash .info-label { color: var(--muted); }
  body.theme-ash .pill.green { color: #16a34a; background: rgba(22,163,74,.08); border-color: rgba(22,163,74,.12); }

  /* Theme options: dark neutral look in Ash mode */
  body.theme-ash .theme-option {
    background: rgba(255,255,255,.02) !important;
    border: 1px solid rgba(255,255,255,.04) !important;
    box-shadow: none !important;
    color: var(--text) !important;
  }

  body.theme-ash .theme-option:hover {
    border-color: rgba(255,255,255,.06) !important;
  }

  /* Selected option highlighted with subtle light-gray outline */
  body.theme-ash .theme-option.selected {
    border-color: rgba(255,255,255,.12) !important;
    box-shadow: 0 0 0 2px rgba(255,255,255,.02) !important;
    background: rgba(255,255,255,.04) !important;
    color: var(--text) !important;
  }

  /* Swatch appearance in ash: neutral gray circle */
  body.theme-ash .theme-swatch {
    border: 1px solid rgba(255,255,255,.06) !important;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,.25) !important;
    background: rgba(255,255,255,.06) !important;
  }

  body.theme-ash .theme-option.selected .theme-swatch {
    border-color: rgba(255,255,255,.18) !important;
    box-shadow: 0 0 0 1px rgba(255,255,255,.06) !important;
    background: rgba(255,255,255,.12) !important;
  }
</style>

<div class="settings-container">
  <!-- Profile Settings -->
  <div class="settings-section">
    <h3>Profile Settings</h3>
    
    <form id="profile-settings-form" action="{{ route('professor.settings.update') ?? '#' }}" method="POST" class="form-group">
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
    
    <form action="{{ route('professor.settings.password') ?? '#' }}" method="POST" class="form-group">
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

      
    </form>
  </div>

  <div class="settings-divider"></div>

  <!-- Account Information -->
  <div class="settings-section">
    <h3>Account Information</h3>
    
    <div>
      <div class="info-item">
        <span class="info-label">Role</span>
        <strong class="info-value">Professor</strong>
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const themeKey = 'qr_attendance_theme';
    const themeInput = document.getElementById('theme-input');
    const themeOnlyInput = document.getElementById('theme-only-input');
    const themeButtons = document.querySelectorAll('.theme-option');
    const themeSaveForm = document.getElementById('theme-save-form');
    if (!themeInput || !themeButtons.length) return;

    const applyTheme = function (theme) {
      const validThemes = ['light','ash','dark','onyx'];
      const activeTheme = validThemes.includes(theme) ? theme : 'dark';
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + activeTheme);
      themeInput.value = activeTheme;
      if (themeOnlyInput) themeOnlyInput.value = activeTheme;
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
        // show save button/form
        if (themeSaveForm) themeSaveForm.style.display = '';
        markUnsaved();
      });
    });

    const warningBox = document.getElementById('settings-warning');
    const profileSettingsForm = document.getElementById('profile-settings-form');
    let unsavedSettings = false;
    let warningTimeout = null;

      const showUnsavedWarning = function (message) {
        if (!warningBox) return;
        // populate structured content to exactly match the visual layout
        warningBox.classList.add('show');
        warningBox.querySelector('.message').textContent = message;
        // ensure actions area contains the save button
        const actions = warningBox.querySelector('.actions');
        if (actions && !document.getElementById('unsaved-save-action')) {
          actions.innerHTML = '<button type="button" id="unsaved-save-action">Save changes</button>';
        }
        clearTimeout(warningTimeout);
        warningTimeout = setTimeout(function () {
          if (!unsavedSettings) {
            warningBox.classList.remove('show');
          }
        }, 4200);
        const saveButton = document.getElementById('unsaved-save-action');
        if (saveButton) {
          saveButton.replaceWith(saveButton.cloneNode(true));
          const freshSave = document.getElementById('unsaved-save-action');
          freshSave.addEventListener('click', function () {
            if (themeSaveForm && themeSaveForm.style.display !== 'none') {
              themeSaveForm.submit();
            } else if (profileSettingsForm) {
              profileSettingsForm.submit();
            }
          });
        }

        const resetButton = document.getElementById('unsaved-reset-action');
        if (resetButton) {
          resetButton.replaceWith(resetButton.cloneNode(true));
          const freshReset = document.getElementById('unsaved-reset-action');
          freshReset.addEventListener('click', function () {
            if (profileSettingsForm) {
              profileSettingsForm.reset();
            }
            if (themeSaveForm) {
              themeSaveForm.style.display = 'none';
              applyTheme(savedTheme);
            }
            unsavedSettings = false;
            hideUnsavedWarning();
          });
        }
      };

    const hideUnsavedWarning = function () {
      if (!warningBox) return;
      warningBox.classList.remove('show');
    };

    const markUnsaved = function () {
      unsavedSettings = true;
      if (themeSaveForm) themeSaveForm.style.display = '';
      showUnsavedWarning('Careful — you have unsaved changes!');
    };

    document.querySelectorAll('.settings-container form').forEach(function (form) {
      form.addEventListener('input', markUnsaved);
      form.addEventListener('change', markUnsaved);
      form.addEventListener('submit', function () {
        unsavedSettings = false;
        hideUnsavedWarning();
      });
    });

    document.querySelectorAll('.nav a, .nav button').forEach(function (navItem) {
      navItem.addEventListener('click', function (event) {
        if (!unsavedSettings) return;
        const href = navItem.getAttribute('href');
        if (!href || href === '#' || href === window.location.pathname || href === window.location.href) return;
        event.preventDefault();
        navItem.classList.add('shake');
        window.setTimeout(function () {
          navItem.classList.remove('shake');
        }, 360);
        showUnsavedWarning('Please save your settings before switching tabs.');
      });
    });

    window.addEventListener('beforeunload', function (event) {
      if (!unsavedSettings) return;
      event.preventDefault();
      event.returnValue = '';
    });
  });
</script>

@endsection
