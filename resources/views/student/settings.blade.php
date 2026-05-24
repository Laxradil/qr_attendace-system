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

  body.theme-light .settings-container,
  body.theme-light .settings-wrapper {
    background: #ffffff;
    border-color: #e5e7eb;
    color: #0f172a;
  }

  body.theme-light .settings-input {
    background: #ffffff;
    color: #0f172a;
    border-color: #d1d5db;
  }

  body.theme-light .settings-input:focus {
    background: #ffffff;
    border-color: #3b82f6;
  }

  body.theme-light .settings-divider { background: #e5e7eb; }

  body.theme-light .settings-btn { background: #f9fafb; border: 1px solid #d1d5db; color: #000000; }
  body.theme-light .settings-btn:hover { background: #f3f4f6; border-color: #9ca3af; }
  body.theme-light .settings-btn.primary { background: linear-gradient(135deg,#3b82f6,#8b5cff); border-color:#3b82f6; color:#fff; }

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

<div class="settings-container">
  <!-- Profile Settings -->
  <div class="settings-section">
    <h3>Profile Settings</h3>
    <form id="profile-settings-form" action="{{ route('student.settings.update') ?? '#' }}" method="POST" class="form-group">
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

      @php
        $theme = old('theme', $user->theme ?? 'light');
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
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const themeKey = 'qr_attendance_theme';
      const themeInput = document.getElementById('theme-input');
      const themeOnlyInput = document.getElementById('theme-only-input');
      const themeButtons = document.querySelectorAll('.theme-option');
      const profileSettingsForm = document.getElementById('profile-settings-form');
      const passwordSettingsForm = document.getElementById('password-settings-form');
      const warningBox = document.getElementById('settings-warning');
      let unsavedSettings = false;
      let warningTimeout = null;
      let activeForm = null;
      let profileFormModified = false;
      let passwordFormModified = false;

      if (!themeInput || !themeButtons.length) return;

      const themeSwitchCheckbox = document.getElementById('theme-switch-checkbox');
      const themeSwitchWrapper = themeSwitchCheckbox ? themeSwitchCheckbox.closest('.theme-switch') : null;
      const syncThemeSwitch = function (theme) {
        if (!themeSwitchCheckbox) return;
        themeSwitchCheckbox.checked = (theme === 'onyx');
        if (!themeSwitchWrapper) return;
        themeSwitchWrapper.classList.toggle('light-mode', theme === 'light');
        themeSwitchWrapper.classList.toggle('onyx-mode', theme === 'onyx');
      };

      const passwordField = passwordSettingsForm ? passwordSettingsForm.querySelector('input[name="password"]') : null;
      const passwordConfirmField = passwordSettingsForm ? passwordSettingsForm.querySelector('input[name="password_confirmation"]') : null;
      const clearPasswordMismatchError = function () {
        if (!passwordConfirmField) return;
        passwordConfirmField.setCustomValidity('');
        const existing = passwordConfirmField.parentNode.querySelector('.password-mismatch-error');
        if (existing) existing.remove();
      };
      const showPasswordMismatchError = function (message) {
        if (!passwordConfirmField) return;
        passwordConfirmField.setCustomValidity(message);
        let existing = passwordConfirmField.parentNode.querySelector('.password-mismatch-error');
        if (!existing) {
          existing = document.createElement('div');
          existing.className = 'error-text password-mismatch-error';
          passwordConfirmField.parentNode.appendChild(existing);
        }
        existing.textContent = message;
      };
      const validatePasswordMatch = function () {
        if (!passwordSettingsForm || !passwordField || !passwordConfirmField) return true;
        const passwordValue = passwordField.value;
        const confirmValue = passwordConfirmField.value;
        clearPasswordMismatchError();
        if (!passwordValue && !confirmValue) {
          return true;
        }
        if (passwordValue !== confirmValue) {
          showPasswordMismatchError('Passwords do not match.');
          return false;
        }
        return true;
      };

      const applyTheme = function (theme) {
        const validThemes = ['light','onyx'];
        const activeTheme = validThemes.includes(theme) ? theme : 'light';
        document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
        document.body.classList.add('theme-' + activeTheme);
        themeInput.value = activeTheme;
        if (themeOnlyInput) themeOnlyInput.value = activeTheme;
        themeButtons.forEach(function (option) {
          option.classList.toggle('selected', option.dataset.theme === activeTheme);
        });
        syncThemeSwitch(activeTheme);
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
            
            // Create promises for both form submissions
            let promises = [];
            
            // Submit profile form if modified
            if (profileFormModified && profileSettingsForm) {
              const profilePromise = new Promise(function (resolve, reject) {
                const formData = new FormData(profileSettingsForm);
                fetch(profileSettingsForm.action, {
                  method: 'PUT',
                  headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                  },
                  body: formData
                })
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
              });
              promises.push(profilePromise);
            }
            
            // Submit password form if modified
            if (passwordFormModified && passwordSettingsForm) {
              const passwordPromise = new Promise(function (resolve, reject) {
                const formData = new FormData(passwordSettingsForm);
                fetch(passwordSettingsForm.action, {
                  method: 'PUT',
                  headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                  },
                  body: formData
                })
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
              });
              promises.push(passwordPromise);
            }
            
            // If only one form was modified, submit that one
            if (!profileFormModified && !passwordFormModified && activeForm) {
              const singlePromise = new Promise(function (resolve, reject) {
                const formData = new FormData(activeForm);
                fetch(activeForm.action, {
                  method: 'PUT',
                  headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                  },
                  body: formData
                })
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
              });
              promises.push(singlePromise);
            }
            
            // Wait for all promises to complete
            Promise.all(promises).then(function (results) {
              // After both submissions complete, reload the page
              setTimeout(function () {
                location.reload();
              }, 500);
            }).catch(function (error) {
              console.error('Error saving changes:', error);
              warningBox.classList.remove('success');
              warningBox.querySelector('.message').textContent = 'Error saving changes. Please try again.';
            });
          });
        }

        const resetButton = document.getElementById('unsaved-reset-action');
        if (resetButton && !resetButton.dataset.bound) {
          resetButton.dataset.bound = 'true';
          resetButton.addEventListener('click', function () {
            if (profileFormModified && profileSettingsForm) {
              profileSettingsForm.reset();
              // Reset theme to saved value
              applyTheme(savedTheme);
              localStorage.setItem(themeKey, savedTheme);
            }
            if (passwordFormModified && passwordSettingsForm) {
              passwordSettingsForm.reset();
            }
            unsavedSettings = false;
            activeForm = null;
            profileFormModified = false;
            passwordFormModified = false;
            hideUnsavedWarning();
          });
        }
      };

      if (localStorage.getItem('settings_saved') === 'true') {
        console.log('[Settings] Found settings_saved flag on page load, showing success state');
        localStorage.removeItem('settings_saved');
        if (warningBox) {
          // Show success message and hide actions
          const actionsDiv = warningBox.querySelector('.actions');
          if (actionsDiv) {
            actionsDiv.style.display = 'none';
          }
          warningBox.classList.add('success', 'show');
          warningBox.querySelector('.message').textContent = 'Changes saved successfully! ✓';
          console.log('[Settings] Added success class to warning box');
          setTimeout(function () {
            warningBox.classList.remove('show');
          }, 3000);
        } else {
          console.log('[Settings] Warning box not found!');
        }
      } else {
        console.log('[Settings] settings_saved flag not found on page load');
      }

      const markUnsaved = function (form, themeOnly = false) {
        unsavedSettings = true;
        activeForm = form;
        if (form === profileSettingsForm) {
          profileFormModified = true;
        } else if (form === passwordSettingsForm) {
          passwordFormModified = true;
        }
        showUnsavedWarning('Careful — you have unsaved changes!');
      };

      themeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
          const theme = button.dataset.theme;
          const selectedTheme = applyTheme(theme);
          localStorage.setItem(themeKey, selectedTheme);
          markUnsaved(profileSettingsForm, false);
        });
      });

      if (passwordSettingsForm) {
        passwordSettingsForm.addEventListener('submit', function (event) {
          if (!validatePasswordMatch()) {
            event.preventDefault();
            event.stopPropagation();
            if (passwordConfirmField) {
              passwordConfirmField.focus();
            }
          }
        });
        [passwordField, passwordConfirmField].filter(Boolean).forEach(function (input) {
          input.addEventListener('input', function () {
            if (passwordConfirmField && passwordConfirmField.value.length > 0) {
              validatePasswordMatch();
            }
          });
        });
      }

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
          if (form === profileSettingsForm) {
            profileFormModified = false;
          } else if (form === passwordSettingsForm) {
            passwordFormModified = false;
          }
          activeForm = null;
        });
      });
    });
  </script>

  <div class="settings-divider"></div>
  <!-- Change Password Section -->
  <div class="settings-section">
    <h3>Change Password</h3>
    <form id="password-settings-form" action="{{ route('student.settings.password') ?? '#' }}" method="POST" class="form-group">
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

<style>
  /* Ash theme overrides (student) */
  body.theme-ash .settings-container {
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
  body.theme-ash .settings-btn.primary { background: linear-gradient(135deg,#3b82f6,#8b5cff); border-color:#3b82f6; color:#fff; }

  body.theme-ash .info-item { background: #ffffff; border: 1px solid #e6e9ee; }
  body.theme-ash .info-label { color: #6b7280; }
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const themeKey = 'qr_attendance_theme';
    const themeInput = document.getElementById('theme-input');
    const themeOnlyInput = document.getElementById('theme-only-input');
    const themeButtons = document.querySelectorAll('.theme-option');
    const themeSaveForm = document.getElementById('theme-save-form');
    if (!themeInput || !themeButtons.length) return;

    const applyTheme = function (theme) {
      const validThemes = ['light','onyx'];
      const activeTheme = validThemes.includes(theme) ? theme : 'onyx';
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + activeTheme);
      themeInput.value = activeTheme;
      if (themeOnlyInput) themeOnlyInput.value = activeTheme;
      themeButtons.forEach(function (option) {
        option.classList.toggle('selected', option.dataset.theme === activeTheme);
      });
      return activeTheme;
    };

    const savedTheme = localStorage.getItem(themeKey) || themeInput.value;
    const currentTheme = applyTheme(savedTheme);
    localStorage.setItem(themeKey, currentTheme);

    themeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const theme = button.dataset.theme;
        const selectedTheme = applyTheme(theme);
        localStorage.setItem(themeKey, selectedTheme);
        // show save button/form
        if (themeSaveForm) themeSaveForm.style.display = '';
      });
    });
  });
</script>

@endsection
