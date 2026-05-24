{{-- @var \Illuminate\Support\Collection $classes --}}
{{-- @var int $totalStudents --}}
{{-- @var \Illuminate\Support\Collection $availableStudents --}}
@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@php
    $studentPayload = $availableStudents->map(function ($student) {
        return [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
        ];
    })->values();

    $scanClassesPayload = $classes->map(function ($class) {
        return [
            'id' => $class->id,
            'code' => $class->code,
            'name' => $class->name,
            'students' => $class->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                ];
            })->values(),
            'schedules' => $class->schedules->map(function ($schedule) {
                return [
                    'days' => $schedule->days,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                ];
            })->values(),
        ];
    })->values();
@endphp

@section('content')
<style>
  .search-bar {
    display: none !important;
  }

  /* Overview/stat card color to match darker bluish-gray palette */
  .stats .stat.glass {
    background: linear-gradient(180deg,#2f3746,#262b36);
    border: 1px solid rgba(255,255,255,0.06);
    color: #e6eef8;
    box-shadow: 0 8px 26px rgba(6,8,18,0.6);
  }
</style>
<!-- Overview stats -->
<div class="stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px;margin-top:6px">
  <div class="stat glass">
    <div class="stat-icon blue">▤</div>
    <div class="stat-body">
      <strong>{{ $classes->count() }}</strong>
      <span>Classes Overview</span>
      <div style="font-size:12px;color:var(--muted);margin-top:2px">Total classes currently assigned.</div>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon green">🧑‍🎓</div>
    <div class="stat-body">
      <strong>{{ $totalStudents }}</strong>
      <span>Total Students</span>
      <div style="font-size:12px;color:var(--muted);margin-top:2px">Students across all your classes.</div>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon yellow">✓</div>
    <div class="stat-body">
      <strong>{{ $classes->count() }}</strong>
      <span>Active Classes</span>
      <div style="font-size:12px;color:var(--muted);margin-top:2px">Classes marked active right now.</div>
    </div>
  </div>
</div>

<!-- Class cards grid -->
<div class="class-grid">
  @forelse($classes as $class)
    @php
      $schedule = $class->schedules->first();
      $formattedStart = $schedule?->start_time ? date('g:i A', strtotime($schedule->start_time)) : null;
      $formattedEnd = $schedule?->end_time ? date('g:i A', strtotime($schedule->end_time)) : null;
      $timeLabel = $formattedStart && $formattedEnd ? "{$formattedStart} - {$formattedEnd}" : ($formattedStart ?? $formattedEnd ?? 'N/A');
    @endphp
    <div class="class-card">
      <div class="class-head">
        <div>
          <h3>{{ $class->display_name ?? 'Class' }}</h3>
          <div class="class-code">{{ $class->code ?? 'N/A' }}</div>
        </div>
        <div class="class-room">{{ $schedule?->room ?? 'TBA' }}</div>
      </div>
      <div class="class-meta">
        <div class="class-meta-row">
          Days: <strong>{{ $schedule?->days ?? 'N/A' }}</strong>
        </div>
        <div class="class-meta-row">
          Time: <strong style="font-family:var(--mono)">{{ $timeLabel }}</strong>
        </div>
        <div class="class-meta-row">
          Professor: <strong>{{ auth()->user()->name }}</strong>
        </div>
      </div>
      <div class="class-actions">
        <button type="button" class="btn slim btn-add" data-action="add-student" data-class-id="{{ $class->id }}" data-class-name="{{ $class->display_name }}" data-class-code="{{ $class->code }}" data-class-room="{{ $schedule?->room }}" data-class-schedule-id="{{ $schedule?->id }}" data-class-days="{{ $schedule?->days }}" data-class-start-time="{{ $schedule?->start_time }}" data-class-end-time="{{ $schedule?->end_time }}" data-current-students='@json($class->students->pluck("id"))'>Add Student</button>
        <button type="button" class="btn slim btn-edit" data-action="edit-class" data-class-id="{{ $class->id }}" data-class-name="{{ $class->display_name }}" data-class-code="{{ $class->code }}" data-class-room="{{ $schedule?->room }}" data-class-schedule-id="{{ $schedule?->id }}" data-class-days="{{ $schedule?->days }}" data-class-start-time="{{ $schedule?->start_time }}" data-class-end-time="{{ $schedule?->end_time }}">Edit Class</button>
        <button type="button" class="btn slim btn-scan" data-action="scan-qr" data-class-id="{{ $class->id }}" data-class-name="{{ $class->display_name }}">Scan QR</button>
      </div>
    </div>
  @empty
    <div style="grid-column:1/-1;padding:40px;text-align:center;color:var(--muted)">
      <div style="font-size:48px;margin-bottom:12px">📚</div>
      <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:4px">No Classes Yet</div>
      <div style="font-size:13px">You haven't been assigned any classes yet.</div>
    </div>
  @endforelse
</div>

<!-- Modals -->
<div id="modalBackdrop" class="modal-backdrop" style="display:none;">
  <div class="modal-card">
    <button type="button" class="modal-close" aria-label="Close">×</button>
    <div id="modalContent"></div>
  </div>
</div>

<style>
  .toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  
  .tools {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
  }
  
  .chip {
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.065);
    color: #dde5ff;
    border-radius: 12px;
    padding: 9px 12px;
    font-weight: 700;
    font-size: 12.5px;
    cursor: pointer;
    transition: .2s ease;
    font-family: var(--font);
  }
  
  .chip:hover {
    background: rgba(255,255,255,.1);
  }
  
  .chip.active {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.44));
    border-color: transparent;
  }
  
  .class-grid {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 14px;
    margin-top: 4px;
  }
  
  .class-card {
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: .18s ease, transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    border: 1px solid rgba(255,255,255,0.18);
    background: rgba(20,28,40,0.95);
    backdrop-filter: blur(6px) saturate(115%);
    -webkit-backdrop-filter: blur(6px) saturate(115%);
    box-shadow: 0 0 0 1px rgba(255,255,255,0.08), 0 16px 36px rgba(10,14,26,0.65), inset 0 1px 0 rgba(255,255,255,0.05);
    position: relative;
    overflow: hidden;
  }
  
  .class-card::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg,transparent,rgba(255,255,255,.18) 50%,transparent);
    pointer-events: none;
  }
  
  .class-card:hover {
    transform: translateY(-6px);
    border-color: rgba(139,92,255,0.35);
    box-shadow: 0 20px 56px rgba(10,14,26,0.82), inset 0 1px 0 rgba(255,255,255,0.06);
  }
  
  .class-actions .btn {
    border: 1px solid rgba(255,255,255,0.24);
    background: rgba(255,255,255,0.08);
    color: #f8fafc;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.12);
  }
  
  .class-actions .btn:hover {
    border-color: rgba(139,92,255,0.35);
    background: rgba(255,255,255,0.14);
    box-shadow: 0 0 0 1px rgba(139,92,255,0.05), inset 0 1px 0 rgba(255,255,255,0.15);
  }
  
  .class-actions .btn:focus-visible {
    outline: 2px solid rgba(139,92,255,0.7);
    outline-offset: 2px;
  }
  
  .class-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
  }
  
  .class-head h3 {
    font-size: 16px;
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.2;
  }
  
  .class-code {
    font-size: 11px;
    color: var(--muted);
    margin-top: 3px;
    font-family: var(--mono);
  }
  
  .class-room {
    font-size: 22px;
    font-weight: 900;
    font-family: var(--mono);
    color: rgba(139,92,255,.9);
    letter-spacing: -.03em;
  }
  
  .class-meta {
    display: grid;
    gap: 7px;
    margin-bottom: 14px;
  }
  
  .class-meta-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--muted);
  }
  
  .class-meta-row strong {
    color: var(--text);
    font-weight: 600;
  }
  
  .meta-icon {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    display: grid;
    place-items: center;
    font-size: 11px;
    background: rgba(255,255,255,.08);
    flex-shrink: 0;
  }

  .scanner-layout {
    display: grid;
    grid-template-columns: 1.45fr 1fr;
    gap: 24px;
    min-height: 0;
    align-items: start;
  }
  
  .scanner-box,
  .att-recording,
  .recent-scans-box {
    background: linear-gradient(180deg,#f8fafc,#e2e8f0);
    border-radius: 24px;
    padding: 24px;
    border: 1px solid rgba(148,163,184,.6);
    box-shadow: 0 8px 28px rgba(15,23,42,.08), inset 0 1px 0 rgba(255,255,255,.8);
    color: #0f172a;
  }

  .scanner-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 18px;
  }

  .scanner-tab {
    border: 1px solid rgba(148,163,184,.7);
    background: rgba(255,255,255,.92);
    color: #0f172a;
    border-radius: 14px;
    padding: 11px 12px;
    font-weight: 700;
    cursor: pointer;
    font-size: 13px;
    transition: .2s ease;
  }

  .scanner-tab.active {
    background: linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.85));
    color: #fff;
    border-color: transparent;
  }

  .cam-viewport {
    width: 100%;
    border-radius: 22px;
    background: #e2e8f0;
    border: 1px dashed rgba(148,163,184,.65);
    display: grid;
    place-items: center;
    position: relative;
    overflow: hidden;
    min-height: 320px;
  }

  .cam-inner {
    width: 100%;
    height: 100%;
    position: relative;
  }

  .cam-btns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 18px;
  }

  .cam-btn {
    border: 0;
    border-radius: 14px;
    padding: 12px;
    font-weight: 800;
    cursor: pointer;
    font-size: 13px;
    transition: .2s ease;
  }

  .scan-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    background: rgba(15,23,42,.08);
    border: 1px solid rgba(148,163,184,.6);
    color: #0f172a;
    margin-bottom: 12px;
  }

  .att-field {
    margin-bottom: 18px;
  }

  .att-field label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 8px;
    color: #475569;
  }

  .att-select,
  .att-input {
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid rgba(148,163,184,.65);
    color: #0f172a;
    font-size: 14px;
    outline: none;
    transition: .2s ease;
  }

  .att-display {
    min-height: 52px;
    display: flex;
    align-items: center;
    padding: 14px 16px;
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid rgba(148,163,184,.65);
    color: #0f172a;
    font-size: 14px;
  }

  .att-select:focus,
  .att-input:focus {
    border-color: rgba(139,92,255,.5);
    background: rgba(255,255,255,.12);
  }

  .att-recording + .recent-scans-box {
    margin-top: 24px;
  }

  .recent-scans-box {
    padding: 20px;
  }

  .no-scans {
    text-align: center;
    padding: 30px 0;
    color: #64748b;
    font-size: 13px;
  }

  @media(max-width:1200px) {
    .scanner-layout { grid-template-columns: 1fr; }
  }
  
  .class-actions {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  #modalBackdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 24px;
  }

  .modal-card {
    width: min(960px, 96vw);
    max-height: min(92vh, 1080px);
    overflow-y: auto;
    background: rgba(243, 244, 246, 0.98);
    border-radius: 24px;
    border: 1px solid rgba(148, 163, 184, 0.5);
    box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
    padding: 32px;
    position: relative;
    color: #0f172a;
  }

  .modal-close {
    position: absolute;
    top: 14px;
    right: 14px;
    border: none;
    background: rgba(15, 23, 42, 0.08);
    color: #0f172a;
    width: 34px;
    height: 34px;
    border-radius: 14px;
    cursor: pointer;
    font-size: 20px;
    line-height: 1;
  }

  .modal-card h2 {
    margin: 0 0 14px;
    font-size: 22px;
    letter-spacing: -0.03em;
    color: #0f172a;
  }

  .modal-card .section-head h3 {
    color: #0f172a;
  }

  .modal-card p,
  .modal-card label,
  .modal-card small {
    color: #475569;
  }

  .modal-card .modal-meta {
    display: grid;
    gap: 10px;
    margin-bottom: 20px;
  }

  .modal-card .modal-meta div {
    font-size: 13px;
  }

  .modal-card .modal-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 22px;
  }

  .modal-card .modal-actions .btn {
    min-width: 120px;
  }

  .modal-card .modal-body {
    display: grid;
    gap: 14px;
  }

  .modal-card .modal-field {
    display: grid;
    gap: 6px;
  }

  .modal-card .modal-field input,
  .modal-card .modal-field textarea,
  .modal-card .modal-field select {
    width: 100%;
    border: 1px solid rgba(148,163,184,.6);
    border-radius: 12px;
    background: #ffffff;
    color: #0f172a;
    padding: 11px 12px;
    font-size: 14px;
  }

  .modal-card .modal-field input::placeholder,
  .modal-card .modal-field textarea::placeholder {
    color: rgba(255,255,255,.55);
  }

  .modal-card .modal-note {
    color: #475569;
    font-size: 13px;
  }

  .modal-card .modal-panel {
    background: #f8fafc;
    border: 1px solid rgba(148,163,184,.6);
    border-radius: 16px;
    padding: 16px;
  }

  .modal-card .modal-panel strong {
    display: block;
    margin-bottom: 6px;
    color: #0f172a;
  }

  .modal-card .modal-panel span {
    color: #475569;
    font-size: 13px;
  }

  .modal-card .student-search-results {
    display: grid;
    gap: 8px;
    max-height: 240px;
    overflow-y: auto;
    padding: 8px;
    border: 1px solid rgba(148,163,184,.5);
    border-radius: 12px;
    background: #f8fafc;
  }

  .modal-card .student-option {
    width: 100%;
    text-align: left;
    border: 1px solid rgba(148,163,184,.6);
    border-radius: 14px;
    padding: 12px 14px;
    background: #ffffff;
    color: #0f172a;
    cursor: pointer;
    transition: background .2s, border-color .2s;
    display: grid;
    gap: 4px;
  }

  .modal-card .student-option:hover,
  .modal-card .student-option.selected {
    background: rgba(59,130,246,.14);
    border-color: rgba(59,130,246,.35);
  }

  .modal-card .student-option small {
    color: #475569;
    font-size: 12px;
  }

  @media (max-width: 640px) {
    .modal-card {
      padding: 20px;
    }

    .modal-card h2 {
      font-size: 20px;
    }
  }

  .view-link {
    color: rgba(139,92,255,.9);
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
  }
  
  .view-link:hover {
    color: #b9c4ff;
  }
  
  .btn.slim {
    padding: 7px 10px;
    font-size: 12px;
    border-radius: 10px;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.24);
    background: rgba(255,255,255,0.08);
    color: #f8fafc;
  }
  
  .btn.slim:hover {
    background: rgba(255,255,255,0.14);
    border-color: rgba(139,92,255,0.35);
  }
  
  a.btn,
  a.btn:hover {
    text-decoration: none;
  }
  
  @media(max-width:1200px) {
    .class-grid { grid-template-columns: 1fr; }
  }
</style>

<style>
  body.theme-light .glass {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .chip {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .chip:hover {
    background: #f9fafb !important;
  }
  
  body.theme-light .chip.active {
    background: #3b82f6 !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
  }
  
  body.theme-light .class-card {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
  }
  
  body.theme-light .class-card::after {
    background: linear-gradient(90deg,transparent,#e5e7eb 50%,transparent) !important;
  }
  
  body.theme-light .class-card:hover {
    border-color: #d1d5db !important;
  }
  
  body.theme-light .class-code {
    color: #d1d5ff !important;
  }
  
  body.theme-light .class-room,
  body.theme-light .class-meta-row,
  body.theme-light .class-meta-row strong {
    color: var(--text) !important;
  }

  body.theme-light .class-head h3 {
    color: var(--text) !important;
  }
  
  body.theme-light .meta-icon {
    background: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .view-link {
    color: #3b82f6 !important;
  }
  
  body.theme-light .view-link:hover {
    color: #2563eb !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalContent = document.getElementById('modalContent');
    const modalClose = modalBackdrop.querySelector('.modal-close');

    const modalActions = {
      'add-student': function (data) {
        return `
          <h2>Add Students to ${data.className || 'Class'}</h2>
          <div class="modal-meta">
            <div><strong>Class Code:</strong> ${data.classCode || 'N/A'}</div>
            <div><strong>Room:</strong> ${data.classRoom || 'TBA'}</div>
            <div><strong>Schedule:</strong> ${data.classDays || 'N/A'} ${data.classStartTime || ''} ${data.classEndTime || ''}</div>
          </div>
          <div class="modal-body">
            <div class="modal-field">
              <label>Search existing students</label>
              <input type="search" id="studentSearchInput" placeholder="Search by name or email" autocomplete="off" />
            </div>
            <div class="student-search-results" id="studentSearchResults"></div>
            <div class="modal-field">
              <label>Selected students</label>
              <input type="text" id="selectedStudentDisplay" value="" readonly placeholder="Pick students from the list" />
            </div>
            <div class="modal-note">Click students to toggle them on or off. Add all selected students at once.</div>
            <div class="modal-actions">
              <button type="button" id="studentAddSubmit" class="btn btn-add" disabled>Add Students</button>
              <button type="button" class="btn" onclick="document.getElementById('modalBackdrop').style.display='none';document.body.style.overflow=''">Cancel</button>
            </div>
          </div>
        `;
      },
      'edit-class': function (data) {
        // days: data.classDays expected as comma-separated string
        const days = (data.classDays || '').split(',').map(d => d.trim()).filter(Boolean);
        const startValue = (data.classStartTime || '').split(':').slice(0, 2).join(':');
        const endValue = (data.classEndTime || '').split(':').slice(0, 2).join(':');
        return `
          <h2>Edit ${data.className || 'Class'}</h2>
          <div class="modal-meta">
            <div><strong>Class Code:</strong> ${data.classCode || 'N/A'}</div>
            <div><strong>Schedule ID:</strong> ${data.classScheduleId || 'N/A'}</div>
          </div>
          <div class="modal-body">
            <div class="modal-field">
              <label>Class Name</label>
              <input type="text" id="editNameInput" value="${data.className || ''}" />
            </div>
            <div class="modal-field">
              <label>Class Code</label>
              <input type="text" id="editCodeInput" value="${data.classCode || ''}" />
            </div>
            <div class="modal-field">
              <label>Room</label>
              <input type="text" id="editRoomInput" value="${data.classRoom || ''}" />
            </div>
            <div class="modal-field">
              <label>Days</label>
              <div id="editDaysGroup" style="display:flex;gap:8px;flex-wrap:wrap;">
                ${['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'].map(d => `<label style=\"display:inline-flex;align-items:center;gap:6px;\"><input type=\"checkbox\" class=\"edit-day-checkbox\" value=\"${d}\" ${days.includes(d) ? 'checked' : ''}/> ${d}</label>`).join('')}
              </div>
            </div>
            <div class="modal-field">
              <label>Start Time</label>
              <input type="time" id="editStartTime" value="${startValue}" />
            </div>
            <div class="modal-field">
              <label>End Time</label>
              <input type="time" id="editEndTime" value="${endValue}" />
            </div>
            <div class="modal-note">Edit schedule days and times for this class.</div>
            <div id="editClassErrors" class="modal-errors" style="color:#ffdddd;margin-bottom:8px;"></div>
            <div id="editClassSuccess" class="modal-success" style="color:#b9ffb7;margin-bottom:8px;"></div>
            <div class="modal-actions">
              <button type="button" id="editClassSave" class="btn btn-edit">Save Changes</button>
              <button type="button" class="btn" onclick="document.getElementById('modalBackdrop').style.display='none';document.body.style.overflow=''">Cancel</button>
            </div>
          </div>
        `;
      },
      'scan-qr': function (data) {
        const selectedClassId = data.classId || '';
        return `
          <h2>Scan QR for ${data.className || 'Class'}</h2>
          <div class="scanner-layout">
            <div class="scanner-box glass" style="flex:1">
              <div class="section-head" style="margin-bottom:12px">
                <h3>QR Scanner</h3>
                <div class="scan-status" id="scanStatus">Ready to scan</div>
              </div>
              <div class="scanner-tabs">
                <button type="button" class="scanner-tab active" data-mode="camera">Camera</button>
                <button type="button" class="scanner-tab" data-mode="hardware">Hardware</button>
              </div>
              <div class="cam-viewport">
                <div class="cam-inner"></div>
              </div>
            </div>
            <div>
              <form id="scanAttendanceForm" method="POST" action="/professor/attendance" class="att-recording">
                <input type="hidden" name="_token" value="${csrfToken}" />
                <input type="hidden" id="scanClassInput" name="class_id" value="${selectedClassId}" />
                <input type="hidden" id="scanStudentInput" name="student_id" value="" />
                <input type="hidden" id="scanQrInput" name="qr_code" />
                <div class="section-head" style="margin-bottom:12px"><h3>Attendance Recording</h3></div>
                <div class="att-field">
                  <label>Class</label>
                  <div class="att-display" id="scanClassDisplay">${data.className || 'Selected Class'}</div>
                </div>
                <div class="att-field">
                  <label>Student Scanned</label>
                  <div class="att-display" id="scanStudentDisplay">Waiting for scan...</div>
                </div>
                <div class="hardware-input-group" style="margin-top:16px; display:none;">
                  <label for="scanQrTextInput" style="display:none;">Student QR code input</label>
                  <input type="text" id="scanQrTextInput" autocomplete="off" spellcheck="false" placeholder="Scan or paste student QR code..." style="width:100%;padding:14px 16px;border-radius:14px;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.05);color:#fff;font-size:14px;" />
                </div>
                <div class="att-note" style="margin-top:8px;padding:12px;border-radius:12px;background:rgba(255,255,255,0.08);font-size:14px;color:var(--text2);">
                  Attendance will be recorded automatically for this class when a valid student QR is scanned.
                </div>
              </form>
              <div class="recent-scans-box">
                <div class="section-head" style="margin-bottom:12px"><h3>Recent Scans</h3></div>
                <div class="no-scans">No scans yet</div>
              </div>
            </div>
          </div>
        `;
      }
    };

    const availableStudents = @json($studentPayload);
    const scanClasses = @json($scanClassesPayload);
    const csrfToken = '{{ csrf_token() }}';

    function renderStudentOption(student) {
      return `
        <button type="button" class="student-option" data-student-id="${student.id}" data-student-name="${student.name}" data-student-email="${student.email}">
          <span>${student.name}</span>
          <small>${student.email}</small>
        </button>
      `;
    }

    function attachStudentSearchHandlers(currentStudentIds, classId) {
      currentStudentIds = (currentStudentIds || []).map(String);
      const searchInput = modalContent.querySelector('#studentSearchInput');
      const resultsContainer = modalContent.querySelector('#studentSearchResults');
      const selectedDisplay = modalContent.querySelector('#selectedStudentDisplay');
      const addButton = modalContent.querySelector('#studentAddSubmit');
      if (!searchInput || !resultsContainer || !selectedDisplay || !addButton) {
        return;
      }

      const selectedStudents = new Map();

      function updateSelectedDisplay() {
        if (selectedStudents.size === 0) {
          selectedDisplay.value = '';
          addButton.disabled = true;
        } else {
          const names = Array.from(selectedStudents.values()).map((student) => student.name);
          selectedDisplay.value = `${names.join(', ')}`;
          addButton.disabled = false;
        }
      }

      function renderResults(query) {
        const normalized = query.trim().toLowerCase();
        const filtered = availableStudents.filter(function (student) {
          const isEnrolled = currentStudentIds.includes(String(student.id));
          return !isEnrolled && (student.name.toLowerCase().includes(normalized) || student.email.toLowerCase().includes(normalized));
        });

        resultsContainer.innerHTML = filtered.map(renderStudentOption).join('') || '<div style="color:rgba(255,255,255,.65);font-size:13px;padding:10px;">No students matched.</div>';

        resultsContainer.querySelectorAll('.student-option').forEach(function (button) {
          const studentId = button.dataset.studentId;
          if (selectedStudents.has(studentId)) {
            button.classList.add('selected');
          }

          button.addEventListener('click', function () {
            const student = {
              id: this.dataset.studentId,
              name: this.dataset.studentName,
              email: this.dataset.studentEmail,
            };
            if (selectedStudents.has(student.id)) {
              selectedStudents.delete(student.id);
              this.classList.remove('selected');
            } else {
              selectedStudents.set(student.id, student);
              this.classList.add('selected');
            }
            updateSelectedDisplay();
          });
        });
      }

      searchInput.addEventListener('input', function () {
        renderResults(this.value);
      });

      addButton.addEventListener('click', function () {
        if (selectedStudents.size === 0) {
          alert('Please choose at least one student from the list.');
          return;
        }

        const selected = Array.from(selectedStudents.values());
        const payload = {
          class_id: classId,
          student_ids: selected.map(s => s.id)
        };

        addButton.disabled = true;
        addButton.textContent = 'Adding...';

        fetch('/professor/add-student', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(payload)
        }).then(async (res) => {
          const text = await res.text();
          if (res.ok) {
            // reload to reflect changes
            window.location.reload();
          } else {
            alert('Failed to add students.');
            addButton.disabled = false;
            addButton.textContent = 'Add Students';
          }
        }).catch((err) => {
          console.error(err);
          alert('An error occurred while adding students.');
          addButton.disabled = false;
          addButton.textContent = 'Add Students';
        });
      });

      updateSelectedDisplay();
      renderResults('');
    }

    let scanStream = null;
    let scanFrameId = null;

    function attachScanQrHandlers(classData) {
      const classInput = modalContent.querySelector('#scanClassInput');
      const classDisplay = modalContent.querySelector('#scanClassDisplay');
      const studentInput = modalContent.querySelector('#scanStudentInput');
      const studentDisplay = modalContent.querySelector('#scanStudentDisplay');
      const qrInput = modalContent.querySelector('#scanQrInput');
      const qrTextInput = modalContent.querySelector('#scanQrTextInput');
      const hardwareInputGroup = modalContent.querySelector('.hardware-input-group');
      const scannerTabs = modalContent.querySelectorAll('.scanner-tab');
      const camViewport = modalContent.querySelector('.cam-inner');
      const scanStatus = modalContent.querySelector('#scanStatus');
      if (!classInput || !classDisplay || !studentInput || !studentDisplay || !qrInput || !qrTextInput || !camViewport || !scanStatus) {
        return;
      }

      const attendanceForm = modalContent.querySelector('#scanAttendanceForm');
      const recentScansBox = modalContent.querySelector('.recent-scans-box');
      const noScansNotice = modalContent.querySelector('.no-scans');
      let pendingSubmit = false;
      let inputTimer = null;

      const selectedClassId = String(classData.classId || '');
      classInput.value = selectedClassId;
      classDisplay.textContent = classData.className || 'Selected Class';
      studentDisplay.textContent = 'Waiting for scan...';
      studentInput.value = '';

      function setScanStatus(text) {
        scanStatus.textContent = text;
      }

      function addRecentScanEntry(message, type = 'success') {
        if (!recentScansBox) return;
        if (noScansNotice) {
          noScansNotice.style.display = 'none';
        }
        const entry = document.createElement('div');
        entry.className = 'recent-scan-entry';
        entry.style.padding = '10px 12px';
        entry.style.margin = '8px 0';
        entry.style.borderRadius = '12px';
        entry.style.background = type === 'success' ? 'rgba(88, 214, 141, 0.12)' : 'rgba(255, 133, 133, 0.12)';
        entry.style.color = type === 'success' ? 'var(--green)' : 'var(--red)';
        entry.textContent = `${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' })} — ${message}`;
        recentScansBox.appendChild(entry);
      }

      function lookupStudentName(studentId, decodedName) {
        const classe = scanClasses.find(cls => String(cls.id) === selectedClassId);
        const student = classe?.students?.find(st => String(st.id) === String(studentId));
        return student?.name || decodedName || `Student #${studentId}`;
      }

      function parseQrPayload(qrCode) {
        let studentId = '';
        let studentName = '';
        try {
          const decoded = JSON.parse(qrCode);
          if (decoded && decoded.type === 'student_attendance' && decoded.student_id) {
            studentId = String(decoded.student_id);
            studentName = lookupStudentName(decoded.student_id, decoded.student_name || '');
          }
        } catch (e) {
          // ignore invalid JSON
        }
        return { studentId, studentName };
      }

      function submitAttendance() {
        const classId = classInput.value;
        const studentId = studentInput.value;
        const qrCode = qrInput.value.trim();

        if (!qrCode) {
          return;
        }
        if (pendingSubmit) {
          return;
        }
        if (!studentId) {
          setScanStatus('Waiting for a valid student QR scan...');
          return;
        }
        if (!classId) {
          setScanStatus('Unable to determine the selected class.');
          return;
        }

        pendingSubmit = true;
        setScanStatus('Recording attendance...');

        fetch('/professor/attendance', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            class_id: classId,
            student_id: studentId,
            qr_code: qrCode
          })
        }).then(async (res) => {
          const data = await res.json().catch(() => ({}));
          pendingSubmit = false;
          if (res.ok) {
            setScanStatus('Attendance recorded successfully.');
            addRecentScanEntry(data.message || `Recorded ${studentDisplay.textContent} for ${classDisplay.textContent}`);
            return;
          }
          const errorMessage = data.error || data.message || 'Failed to record attendance.';
          setScanStatus(errorMessage);
          addRecentScanEntry(errorMessage, 'error');
        }).catch((err) => {
          console.error(err);
          pendingSubmit = false;
          const errorMessage = 'Network error while recording attendance.';
          setScanStatus(errorMessage);
          addRecentScanEntry(errorMessage, 'error');
        });
      }

      function handleQrInput(value) {
        const { studentId, studentName } = parseQrPayload(value);
        if (studentId) {
          studentInput.value = studentId;
          studentDisplay.textContent = studentName || `Student #${studentId}`;
        } else {
          studentInput.value = '';
          studentDisplay.textContent = 'Scanning student QR...';
        }
      }

      if (attendanceForm) {
        attendanceForm.addEventListener('submit', function (event) {
          event.preventDefault();
          submitAttendance();
        });
      }

      qrTextInput.addEventListener('input', function () {
        clearTimeout(inputTimer);
        const value = this.value.trim();
        qrInput.value = value;
        handleQrInput(value);
        inputTimer = setTimeout(() => submitAttendance(), 250);
      });

      qrTextInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
          event.preventDefault();
          qrInput.value = this.value.trim();
          handleQrInput(qrInput.value);
          submitAttendance();
        }
      });

      function stopCamera() {
        if (scanStream) {
          scanStream.getTracks().forEach(track => track.stop());
          scanStream = null;
        }
        if (scanFrameId) {
          cancelAnimationFrame(scanFrameId);
          scanFrameId = null;
        }
        camViewport.innerHTML = '';
        setScanStatus('Ready to scan');
      }

      function loadJsQr() {
        return new Promise((resolve, reject) => {
          if (window.jsQR) {
            return resolve(window.jsQR);
          }
          const existing = document.querySelector('script[data-jsqr]');
          if (existing) {
            existing.addEventListener('load', () => resolve(window.jsQR));
            existing.addEventListener('error', reject);
            return;
          }
          const script = document.createElement('script');
          script.src = 'https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js';
          script.dataset.jsqr = 'true';
          script.onload = () => resolve(window.jsQR);
          script.onerror = reject;
          document.head.appendChild(script);
        });
      }

      function parseScheduleDays(days) {
        if (!days) return [];
        return days.split(/[,;\/\s]+/).map(d => d.trim()).filter(Boolean).map(d => {
          const normalized = d.toLowerCase();
          return {
            mon: 'Monday', monday: 'Monday', tue: 'Tuesday', tues: 'Tuesday', tuesday: 'Tuesday',
            wed: 'Wednesday', wednesday: 'Wednesday', thu: 'Thursday', thur: 'Thursday', thurs: 'Thursday', thursday: 'Thursday',
            fri: 'Friday', friday: 'Friday', sat: 'Saturday', saturday: 'Saturday', sun: 'Sunday', sunday: 'Sunday'
          }[normalized];
        }).filter(Boolean);
      }

      function scheduleMatchesDay(schedule, day) {
        return parseScheduleDays(schedule.days).some(d => d.toLowerCase() === day.toLowerCase());
      }

      function scheduleMatchesNow(schedule) {
        if (!schedule.start_time || !schedule.end_time) {
          return false;
        }
        const now = new Date();
        const today = now.toISOString().slice(0, 10);
        const start = new Date(`${today}T${schedule.start_time}`);
        const end = new Date(`${today}T${schedule.end_time}`);
        if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
          return false;
        }
        return now >= start && now <= end;
      }

      function findBestClassForStudent(studentId) {
        const today = new Date().toLocaleDateString('en-US', { weekday: 'long' });
        const candidates = scanClasses.filter(cls => cls.students.some(student => String(student.id) === String(studentId)));
        if (!candidates.length) return null;
        const currentMatches = candidates.filter(cls => cls.schedules.some(schedule => scheduleMatchesDay(schedule, today) && scheduleMatchesNow(schedule)));
        if (currentMatches.length === 1) return currentMatches[0].id;
        if (currentMatches.length > 1) return currentMatches[0].id;
        const todayMatches = candidates.filter(cls => cls.schedules.some(schedule => scheduleMatchesDay(schedule, today)));
        if (todayMatches.length === 1) return todayMatches[0].id;
        return candidates[0].id;
      }

      function initQrDetection(video) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        let lastDetectedValue = null;

        function detect() {
          if (!scanStream) return;
          if (video.videoWidth === 0 || video.videoHeight === 0) {
            scanFrameId = requestAnimationFrame(detect);
            return;
          }
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
          try {
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = window.jsQR(imageData.data, canvas.width, canvas.height);
            if (code && code.data && code.data !== lastDetectedValue) {
              lastDetectedValue = code.data;
              qrInput.value = code.data;
              setScanStatus('QR code detected');
              handleQrInput(code.data);
              setTimeout(submitAttendance, 150);
            }
          } catch (e) {
            console.error('QR detection error', e);
          }
          scanFrameId = requestAnimationFrame(detect);
        }

        detect();
      }

      function startCamera() {
        loadJsQr().then(() => navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false }))
          .then(stream => {
            scanStream = stream;
            const video = document.createElement('video');
            video.srcObject = stream;
            video.autoplay = true;
            video.playsInline = true;
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';
            video.style.borderRadius = '14px';
            camViewport.innerHTML = '';
            camViewport.appendChild(video);
            setScanStatus('Scanning...');
            initQrDetection(video);
          })
          .catch(err => {
            setScanStatus('Camera access denied');
            console.error(err);
          });
      }

      scannerTabs.forEach(tab => {
        tab.addEventListener('click', () => {
          scannerTabs.forEach(t => t.classList.remove('active'));
          tab.classList.add('active');
          if (tab.dataset.mode === 'hardware') {
            stopCamera();
            if (hardwareInputGroup) {
              hardwareInputGroup.style.display = 'block';
            }
            qrTextInput.value = '';
            qrInput.value = '';
            qrTextInput.focus();
            setScanStatus('Hardware scanner mode');
          } else {
            if (hardwareInputGroup) {
              hardwareInputGroup.style.display = 'none';
            }
            qrTextInput.value = '';
            qrInput.value = '';
            qrInput.placeholder = 'Scan or paste student QR code...';
            setScanStatus('Ready to scan');
            startCamera();
          }
        });
      });

      // Start camera automatically when scan modal opens
      startCamera();
    }

    function attachEditClassHandlers(classData) {
      const saveBtn = modalContent.querySelector('#editClassSave');
      const errorContainer = modalContent.querySelector('#editClassErrors');
      if (!saveBtn) return;

      function clearErrors() {
        if (errorContainer) errorContainer.innerHTML = '';
        modalContent.querySelectorAll('.input-error').forEach(n => n.remove());
        const successContainer = modalContent.querySelector('#editClassSuccess');
        if (successContainer) successContainer.textContent = '';
      }

      function showErrors(errors) {
        if (!errors) return;
        if (errorContainer) {
          const list = Object.values(errors).flat().map(m => `<div>${m}</div>`).join('');
          errorContainer.innerHTML = list;
        }
        Object.keys(errors).forEach(key => {
          const input = modalContent.querySelector('#edit' + key.charAt(0).toUpperCase() + key.slice(1) + 'Input') || modalContent.querySelector(`#edit${key.charAt(0).toUpperCase() + key.slice(1)}Input`);
          if (input) {
            const el = document.createElement('div');
            el.className = 'input-error';
            el.style.color = '#ffdddd';
            el.style.fontSize = '12px';
            el.textContent = errors[key][0];
            input.parentNode.insertBefore(el, input.nextSibling);
          }
        });
      }

      function setSuccess(message) {
        const successContainer = modalContent.querySelector('#editClassSuccess');
        if (successContainer) {
          successContainer.textContent = message;
        }
      }

      saveBtn.addEventListener('click', function () {
        clearErrors();
        setSuccess('');
        const name = modalContent.querySelector('#editNameInput')?.value?.trim();
        const code = modalContent.querySelector('#editCodeInput')?.value?.trim();
        const room = modalContent.querySelector('#editRoomInput')?.value?.trim();
        const startTime = modalContent.querySelector('#editStartTime')?.value;
        const endTime = modalContent.querySelector('#editEndTime')?.value;
        const days = Array.from(modalContent.querySelectorAll('.edit-day-checkbox:checked')).map(n => n.value);

        if (!name || !code) {
          showErrors({ name: ['Please provide class name.'], code: ['Please provide class code.'] });
          return;
        }

        const classId = classData.classId || classData.class_id;
        if (!classId) {
          showErrors({ general: ['Invalid class identifier. Please refresh the page and try again.'] });
          return;
        }

        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        const payload = {
          name: name,
          code: code,
          room: room,
          description: '',
          start_time: startTime,
          end_time: endTime,
          days: days,
          _method: 'PUT'
        };

        fetch(`/professor/classes/${classId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-HTTP-Method-Override': 'PUT'
          },
          body: JSON.stringify(payload)
        }).then(async (res) => {
          if (res.ok) {
            setSuccess('Saved successfully. Refreshing...');
            setTimeout(() => window.location.reload(), 900);
            return;
          }

          if (res.status === 422) {
            const data = await res.json();
            showErrors(data.errors || data);
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Changes';
            return;
          }

          const txt = await res.text();
          showErrors({ general: [txt] });
          saveBtn.disabled = false;
          saveBtn.textContent = 'Save Changes';
        }).catch((err) => {
          console.error(err);
          showErrors({ general: ['Error saving class.'] });
          saveBtn.disabled = false;
          saveBtn.textContent = 'Save Changes';
        });
      }, { once: true });
    }

    function openModal(html) {
      modalContent.innerHTML = html;
      modalBackdrop.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      stopScanStream();
      modalBackdrop.style.display = 'none';
      modalContent.innerHTML = '';
      document.body.style.overflow = '';
    }

    function stopScanStream() {
      if (scanStream) {
        scanStream.getTracks().forEach(track => track.stop());
        scanStream = null;
      }
      if (scanFrameId) {
        cancelAnimationFrame(scanFrameId);
        scanFrameId = null;
      }
      const camInner = modalContent.querySelector('.cam-inner');
      if (camInner) camInner.innerHTML = '';
    }

    modalClose.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', function (event) {
      if (event.target === modalBackdrop) {
        closeModal();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && modalBackdrop.style.display === 'flex') {
        closeModal();
      }
    });

    document.querySelectorAll('button[data-action]').forEach(function (button) {
      button.addEventListener('click', function () {
        const action = this.dataset.action;
        const data = {
          className: this.dataset.className,
          classCode: this.dataset.classCode,
          classRoom: this.dataset.classRoom,
          classDays: this.dataset.classDays,
          classStartTime: this.dataset.classStartTime,
          classEndTime: this.dataset.classEndTime,
          classScheduleId: this.dataset.classScheduleId,
          currentStudentIds: this.dataset.currentStudents ? JSON.parse(this.dataset.currentStudents) : [],
          classId: this.dataset.classId
        };
        const renderer = modalActions[action];
        if (renderer) {
          openModal(renderer(data));
          if (action === 'add-student') {
            attachStudentSearchHandlers(data.currentStudentIds, data.classId);
          }
          if (action === 'edit-class') {
            attachEditClassHandlers(data);
          }
          if (action === 'scan-qr') {
            attachScanQrHandlers(data);
          }
        }
      });
    });
  });
</script>
@endsection
