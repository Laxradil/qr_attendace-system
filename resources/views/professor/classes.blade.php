{{-- @var \Illuminate\Support\Collection $classes --}}
{{-- @var int $totalStudents --}}
{{-- @var \Illuminate\Support\Collection $availableStudents --}}
@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<style>
  .search-bar {
    display: none !important;
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

<!-- Semester filter chips -->
<div class="toolbar" style="margin-bottom:20px">
  <div class="tools" style="gap:10px">
    <div class="chip active">All Semesters</div>
    <div class="chip">1st Sem</div>
    <div class="chip">2nd Sem</div>
  </div>
</div>

<!-- Class cards grid -->
<div class="class-grid">
  @forelse($classes as $class)
    <div class="class-card">
      <div class="class-head">
        <div>
          <h3>{{ $class->display_name ?? 'Class' }}</h3>
          <div class="class-code">{{ $class->code ?? 'N/A' }}</div>
        </div>
        <div class="class-room">{{ $class->schedules->first()?->room ?? 'TBA' }}</div>
      </div>
      <div class="class-meta">
        <div class="class-meta-row">
          <div class="meta-icon">📅</div>
          Days: <strong>{{ $class->schedules->first()?->days ?? 'N/A' }}</strong>
        </div>
        <div class="class-meta-row">
          <div class="meta-icon">🕓</div>
<<<<<<< HEAD
          Time: <strong style="font-family:var(--mono)">
            @if($class->schedules->first()?->start_time)
              {{ \Carbon\Carbon::createFromFormat('H:i:s', $class->schedules->first()->start_time)->format('g:i A') }}
              @if($class->schedules->first()?->end_time)
                – {{ \Carbon\Carbon::createFromFormat('H:i:s', $class->schedules->first()->end_time)->format('g:i A') }}
              @endif
            @else
              N/A
            @endif
          </strong>
        </div>
        <div class="class-meta-row">
          <div class="meta-icon">🎓</div>
          Professor: <strong>{{ auth()->user()->name }}</strong>
        </div>
      </div>
      <div class="class-actions">
        <a href="#" class="btn slim btn-p" onclick="event.preventDefault(); openAddStudentModal(this)"
           data-class-id="{{ $class->id }}"
           data-class-name="{{ $class->display_name }}"
           data-enrolled-ids="{{ json_encode($class->students->pluck('id')->toArray()) }}"
        >Add Student</a>
        <a href="#" class="btn slim" onclick="event.preventDefault(); openEditClassModal(this)"
           data-class-id="{{ $class->id }}"
           data-class-name="{{ $class->display_name }}"
           data-class-code="{{ $class->code }}"
           data-class-description="{{ $class->description }}"
           data-schedule-id="{{ $class->schedules->first()?->id }}"
           data-schedule-days="{{ $class->schedules->first()?->days }}"
           data-schedule-start="{{ $class->schedules->first()?->start_time }}"
           data-schedule-end="{{ $class->schedules->first()?->end_time }}"
           data-schedule-room="{{ $class->schedules->first()?->room }}"
        >Edit Class</a>
        <a href="#" class="btn slim" onclick="event.preventDefault(); openScanQRModal(this)"
           data-class-id="{{ $class->id }}"
           data-class-name="{{ $class->display_name }}"
           data-schedule-days="{{ $class->schedules->first()?->days }}"
           data-schedule-start="{{ $class->schedules->first()?->start_time }}"
           data-schedule-end="{{ $class->schedules->first()?->end_time }}"
        >Scan QR</a>
=======
          Time: <strong style="font-family:var(--mono)">{{ $class->schedules->first()?->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $class->schedules->first()->start_time)->format('H:i') : 'N/A' }}</strong>
        </div>
        <div class="class-meta-row">
          <div class="meta-icon">🎓</div>
          Professor: <strong>{{ auth()->user()->name }}</strong>
        </div>
      </div>
      <div class="class-actions">
        <a href="{{ route('professor.class-detail', $class->id) }}" class="view-link">View class →</a>
        <div style="flex:1"></div>
        <button class="btn slim" onclick="window.location.href='{{ route('professor.class-detail', $class->id) }}'">Details</button>
>>>>>>> origin/branch-ni-kirb
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
    transition: .25s ease;
    border: 1px solid var(--stroke);
    background: linear-gradient(135deg,rgba(255,255,255,.12),rgba(255,255,255,.04) 40%,rgba(255,255,255,.08));
    backdrop-filter: var(--blur);
    -webkit-backdrop-filter: var(--blur);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25), var(--shadow);
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
    background: linear-gradient(90deg,transparent,rgba(255,255,255,.55) 50%,transparent);
    pointer-events: none;
  }
  
  .class-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255,255,255,.3);
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
  
  .class-actions {
    display: flex;
<<<<<<< HEAD
    flex-wrap: wrap;
=======
>>>>>>> origin/branch-ni-kirb
    gap: 8px;
    align-items: center;
  }
  
<<<<<<< HEAD
  .class-actions a {
    text-decoration: none;
  }
  
  .class-actions a.btn {
    text-decoration: none;
  }
  
=======
>>>>>>> origin/branch-ni-kirb
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
  }
  
  @media(max-width:1200px) {
    .class-grid { grid-template-columns: 1fr; }
  }
<<<<<<< HEAD
  
  .edit-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1200;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.55);
    padding: 20px;
  }
  
  .edit-modal.active {
    display: flex;
  }
  
  .edit-modal-content {
    width: min(100%, 560px);
    background: rgba(18,20,34,.95);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 20px;
    box-shadow: 0 30px 80px rgba(0,0,0,.35);
    padding: 24px;
  }
  
  .edit-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
  }
  
  .edit-modal-header h3 {
    margin: 0;
    font-size: 18px;
  }
  
  .edit-modal-close {
    border: none;
    background: transparent;
    color: var(--text);
    font-size: 20px;
    cursor: pointer;
  }
  
  .edit-modal-content label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
  }
  
  .edit-modal-content input,
  .edit-modal-content textarea {
    width: 100%;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.04);
    color: var(--text);
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 14px;
    font-family: inherit;
    font-size: 13px;
  }
  
  .edit-modal-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
  }

  .add-modal,
  .scan-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1200;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.55);
    padding: 20px;
  }

  .add-modal.active,
  .scan-modal.active {
    display: flex;
  }

  .modal-panel {
    width: min(100%, 520px);
    background: rgba(18,20,34,.95);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 20px;
    box-shadow: 0 30px 80px rgba(0,0,0,.35);
    padding: 24px;
  }

  .modal-panel h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
  }

  .modal-panel p {
    margin: 0 0 16px 0;
    color: var(--muted);
    font-size: 13px;
  }

  .modal-field {
    margin-bottom: 14px;
  }

  .modal-field label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
  }

  .modal-field input,
  .modal-field textarea,
  .modal-field select,
  .custom-select {
    width: 100%;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: var(--text);
    border-radius: 14px;
    padding: 12px 14px;
    font-family: inherit;
    font-size: 13px;
    transition: border-color .2s ease, background .2s ease, color .2s ease;
  }
  
  .modal-field select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' fill='none' stroke='rgba(255,255,255,0.7)' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 10px 6px;
    padding-right: 40px;
  }

  .modal-field select:focus,
  .modal-field input:focus,
  .modal-field textarea:focus,
  .custom-select:focus {
    outline: none;
    border-color: rgba(139,92,255,.9);
    background: rgba(255,255,255,.08);
  }

  .custom-select {
    position: relative;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    min-height: 52px;
    padding-right: 40px;
  }

  .custom-select::after {
    content: '';
    position: absolute;
    right: 16px;
    width: 10px;
    height: 6px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' fill='none' stroke='rgba(255,255,255,0.8)' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-size: 10px 6px;
    pointer-events: none;
  }

  .custom-select-trigger {
    width: 100%;
    color: var(--text);
  }

  .custom-options {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 8px;
    border-radius: 18px;
    background: rgba(14,16,30,.96);
    border: 1px solid rgba(255,255,255,.12);
    box-shadow: 0 20px 60px rgba(0,0,0,.35);
    max-height: 260px;
    overflow-y: auto;
    z-index: 10;
  }

  .custom-select.open .custom-options {
    display: block;
  }

  .custom-option {
    padding: 12px 14px;
    cursor: pointer;
    color: rgba(255,255,255,.85);
    transition: background .2s ease, color .2s ease;
  }

  .custom-option:hover,
  .custom-option.active {
    background: rgba(139,92,255,.18);
    color: #fff;
  }

  .custom-options::-webkit-scrollbar {
    width: 8px;
  }

  .custom-options::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,.12);
    border-radius: 999px;
  }

  .custom-options::-webkit-scrollbar-track {
    background: transparent;
  }

  .camera-preview {
    position: relative;
    width: 100%;
    min-height: 180px;
    border-radius: 14px;
    overflow: hidden;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.12);
  }

  .camera-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    min-height: 180px;
    background: #0f111f;
  }

  .camera-fallback {
    position: absolute;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 16px;
    color: var(--muted);
    font-size: 13px;
    background: rgba(0,0,0,.32);
    pointer-events: none;
  }

  .modal-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
  }
</style>

<div id="editClassModal" class="edit-modal" onclick="closeEditClassModal(event)">
  <div class="edit-modal-content" onclick="event.stopPropagation();">
    <div class="edit-modal-header">
      <div>
        <h3>Edit Class</h3>
        <div style="font-size:12px;color:var(--muted);margin-top:4px;">Update class details without leaving the page.</div>
      </div>
      <button type="button" class="edit-modal-close" onclick="closeEditClassModal(event)">✕</button>
    </div>
    <form id="editClassForm" method="POST" action="">
      @csrf
      @method('PUT')
      <input type="hidden" name="schedule_id" id="editClassScheduleId" value="">
      <label for="editClassName">Class Name</label>
      <input id="editClassName" type="text" name="name" required>
      <label for="editClassCode">Class Code</label>
      <input id="editClassCode" type="text" name="code" required>
      <label for="editClassDescription">Description</label>
      <textarea id="editClassDescription" name="description" rows="3"></textarea>
      <label for="editClassDaysDropdown">Days</label>
      <div class="custom-select" id="editClassDaysDropdown" tabindex="0" onclick="toggleDaysDropdown(event)">
        <span class="custom-select-trigger" id="editClassDaysTrigger">Select days</span>
        <div class="custom-options" id="editClassDaysOptions">
          @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
            <div class="custom-option" data-value="{{ $day }}" onclick="toggleDayOption(event, this)">{{ $day }}</div>
          @endforeach
        </div>
      </div>
      <div id="editClassDaysHiddenInputs"></div>
      <label for="editClassStart">Start Time</label>
      <input id="editClassStart" type="time" name="start_time" required>
      <label for="editClassEnd">End Time</label>
      <input id="editClassEnd" type="time" name="end_time" required>
      <label for="editClassRoom">Room</label>
      <input id="editClassRoom" type="text" name="room" required>
      <div class="edit-modal-actions">
        <button type="button" class="btn" onclick="closeEditClassModal(event)">Cancel</button>
        <button type="submit" class="btn btn-p">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<div id="addStudentModal" class="add-modal" onclick="closeAddStudentModal(event)">
  <div class="modal-panel" onclick="event.stopPropagation();">
    <div class="edit-modal-header">
      <div>
        <h3>Add Student</h3>
        <p>Add a student to this class without leaving the page.</p>
      </div>
      <button type="button" class="edit-modal-close" onclick="closeAddStudentModal(event)">✕</button>
    </div>
    <form id="addStudentForm" method="POST" action="{{ route('professor.add-student') }}">
      @csrf
      <input type="hidden" name="class_id" id="addStudentClassId" value="">
      <div class="modal-field">
        <label for="addStudentClassName">Class</label>
        <input id="addStudentClassName" type="text" readonly>
      </div>
      <div class="modal-field">
        <label for="addStudentDropdown">Student</label>
        <div class="custom-select" id="addStudentDropdown" tabindex="0" onclick="toggleAddStudentDropdown(event)">
          <span class="custom-select-trigger" id="addStudentDropdownTrigger">Select a student</span>
          <div class="custom-options" id="addStudentDropdownOptions">
            @foreach($availableStudents as $student)
              <div class="custom-option" data-id="{{ $student->id }}" onclick="selectAddStudentOption(event, this)">{{ $student->name }} — {{ $student->email }}</div>
            @endforeach
          </div>
        </div>
        <input type="hidden" name="student_id" id="addStudentStudentId" required>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" onclick="closeAddStudentModal(event)">Cancel</button>
        <button type="submit" class="btn btn-p">Add Student</button>
      </div>
    </form>
  </div>
</div>

<div id="scanQRModal" class="scan-modal" onclick="closeScanQRModal(event)">
  <div class="modal-panel" onclick="event.stopPropagation();">
    <div class="edit-modal-header">
      <div>
        <h3>Scan QR</h3>
        <p>Record attendance for this class inside the popup.</p>
      </div>
      <button type="button" class="edit-modal-close" onclick="closeScanQRModal(event)">✕</button>
    </div>
    <form id="scanQRForm" method="POST" action="{{ route('professor.attendance.store') }}">
      @csrf
      <input type="hidden" name="class_id" id="scanQRClassId" value="">
      <div class="modal-field">
        <label for="scanQRClassName">Class</label>
        <input id="scanQRClassName" type="text" readonly>
      </div>
      <div class="modal-field">
        <label for="scanQRStudentName">Student</label>
        <input id="scanQRStudentName" type="text" readonly placeholder="Scan a student QR code to record attendance">
        <input type="hidden" name="student_id" id="scanQRStudentId" value="">
        <input type="hidden" name="qr_code" id="scanQRCode" value="">
      </div>
      <div class="modal-field">
        <label>Camera</label>
        <div class="camera-preview">
          <video id="scanQRVideo" autoplay playsinline muted></video>
          <div id="scanQRCameraFallback" class="camera-fallback">Point your device camera at a QR code to preview it here.</div>
        </div>
        <div id="scanQRStatus" class="scan-status" style="margin-top:10px;color:var(--muted);font-size:12px;">
          Scanner is ready.
        </div>
      </div>
      <div class="modal-field">
        <label for="scanQRRawData">QR Data</label>
        <textarea id="scanQRRawData" readonly rows="3" style="width:100%;border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:12px;background:rgba(255,255,255,.04);color:var(--text);font-family:inherit;font-size:13px;resize:none;">No QR data detected yet.</textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" onclick="closeScanQRModal(event)">Cancel</button>
        <button type="submit" class="btn btn-p">Record Attendance</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
  var professorClasses = @json($classes->load('students'));

  function openEditClassModal(element) {
    var modal = document.getElementById('editClassModal');
    var form = document.getElementById('editClassForm');
    var classId = element.dataset.classId;
    var name = element.dataset.className || '';
    var code = element.dataset.classCode || '';
    var description = element.dataset.classDescription || '';
    var scheduleId = element.dataset.scheduleId || '';
    var days = element.dataset.scheduleDays || '';
    var startTime = element.dataset.scheduleStart || '';
    var endTime = element.dataset.scheduleEnd || '';
    var room = element.dataset.scheduleRoom || '';

    if (startTime.length === 8) {
      startTime = startTime.slice(0, 5);
    }
    if (endTime.length === 8) {
      endTime = endTime.slice(0, 5);
    }

    form.action = "{{ url('/') }}" + "/professor/classes/" + classId;
    document.getElementById('editClassScheduleId').value = scheduleId;
    document.getElementById('editClassName').value = name;
    document.getElementById('editClassCode').value = code;
    document.getElementById('editClassDescription').value = description;
    populateDaysSelection(days);
    document.getElementById('editClassStart').value = startTime;
    document.getElementById('editClassEnd').value = endTime;
    document.getElementById('editClassRoom').value = room;

    modal.classList.add('active');
  }

  function closeEditClassModal(event) {
    if (event) event.preventDefault();
    var modal = document.getElementById('editClassModal');
    modal.classList.remove('active');
  }

  function openAddStudentModal(element) {
    var modal = document.getElementById('addStudentModal');
    document.getElementById('addStudentClassId').value = element.dataset.classId || '';
    document.getElementById('addStudentClassName').value = element.dataset.className || '';
    var enrolledIds = element.dataset.enrolledIds ? JSON.parse(element.dataset.enrolledIds) : [];
    
    var allOptions = document.querySelectorAll('#addStudentDropdownOptions .custom-option');
    allOptions.forEach(function(option) {
      if (enrolledIds.includes(parseInt(option.dataset.id))) {
        option.style.display = 'none';
      } else {
        option.style.display = 'block';
      }
    });
    
    resetAddStudentDropdown();
    modal.classList.add('active');
  }

  function toggleAddStudentDropdown(event) {
    event.stopPropagation();
    var dropdown = document.getElementById('addStudentDropdown');
    dropdown.classList.toggle('open');
  }

  function selectAddStudentOption(event, option) {
    event.stopPropagation();
    var trigger = document.getElementById('addStudentDropdownTrigger');
    var studentIdField = document.getElementById('addStudentStudentId');
    var dropdown = document.getElementById('addStudentDropdown');
    trigger.textContent = option.textContent;
    studentIdField.value = option.dataset.id || '';
    dropdown.classList.remove('open');
  }

  function resetAddStudentDropdown() {
    var dropdown = document.getElementById('addStudentDropdown');
    var trigger = document.getElementById('addStudentDropdownTrigger');
    var studentIdField = document.getElementById('addStudentStudentId');
    trigger.textContent = 'Select a student';
    studentIdField.value = '';
    dropdown.classList.remove('open');
  }

  function toggleDaysDropdown(event) {
    event.stopPropagation();
    var dropdown = document.getElementById('editClassDaysDropdown');
    dropdown.classList.toggle('open');
  }

  function toggleDayOption(event, option) {
    event.stopPropagation();
    option.classList.toggle('active');
    updateDaysSelection();
  }

  function updateDaysSelection() {
    var options = document.querySelectorAll('#editClassDaysOptions .custom-option');
    var selected = [];
    options.forEach(function(option) {
      if (option.classList.contains('active')) {
        selected.push(option.dataset.value);
      }
    });

    var trigger = document.getElementById('editClassDaysTrigger');
    var container = document.getElementById('editClassDaysHiddenInputs');
    container.innerHTML = '';

    if (selected.length === 0) {
      trigger.textContent = 'Select days';
    } else {
      trigger.textContent = selected.join(', ');
      selected.forEach(function(day) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'days[]';
        input.value = day;
        container.appendChild(input);
      });
    }
  }

  function populateDaysSelection(daysString) {
    var values = (daysString || '').split(',').map(function(day) { return day.trim(); }).filter(Boolean);
    var options = document.querySelectorAll('#editClassDaysOptions .custom-option');
    options.forEach(function(option) {
      if (values.includes(option.dataset.value)) {
        option.classList.add('active');
      } else {
        option.classList.remove('active');
      }
    });
    updateDaysSelection();
  }

  function resetDaysSelection() {
    var options = document.querySelectorAll('#editClassDaysOptions .custom-option');
    options.forEach(function(option) {
      option.classList.remove('active');
    });
    updateDaysSelection();
  }

  function closeAddStudentModal(event) {
    if (event) event.preventDefault();
    var modal = document.getElementById('addStudentModal');
    modal.classList.remove('active');
  }

  var scanQRStream = null;
  var scanQRFrame = null;
  var lastQRCodeData = '';

  async function startScanCamera() {
    var video = document.getElementById('scanQRVideo');
    var fallback = document.getElementById('scanQRCameraFallback');
    var status = document.getElementById('scanQRStatus');

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      fallback.textContent = 'Camera access is not supported by this browser.';
      fallback.style.display = 'flex';
      if (status) status.textContent = 'No camera support detected.';
      return;
    }

    try {
      if (status) status.textContent = 'Requesting camera access...';
      scanQRStream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: { ideal: 'environment' } },
        audio: false
      });
      video.srcObject = scanQRStream;
      video.muted = true;
      await video.play();
      fallback.style.display = 'none';
      if (status) status.textContent = 'Camera active. Scanning for QR code...';
      initQRDetection(video);
    } catch (error) {
      fallback.textContent = 'Unable to access the camera. Please allow camera access or try a different device.';
      fallback.style.display = 'flex';
      if (status) status.textContent = 'Camera access failed.';
      console.warn('Scan QR camera failed', error);
    }
  }

  function stopScanCamera() {
    var video = document.getElementById('scanQRVideo');
    var fallback = document.getElementById('scanQRCameraFallback');

    if (scanQRStream) {
      scanQRStream.getTracks().forEach(function(track) {
        track.stop();
      });
      scanQRStream = null;
    }

    if (scanQRFrame) {
      cancelAnimationFrame(scanQRFrame);
      scanQRFrame = null;
    }

    if (video) {
      video.srcObject = null;
    }

    if (fallback) {
      fallback.textContent = 'Point your device camera at a QR code to preview it here.';
      fallback.style.display = 'flex';
    }
  }

  function initQRDetection(video) {
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');
    var fallback = document.getElementById('scanQRCameraFallback');
    var status = document.getElementById('scanQRStatus');

    function detect() {
      if (!scanQRStream) {
        if (status) status.textContent = 'Camera stopped.';
        return;
      }

      if (video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0 && video.videoHeight > 0) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        if (typeof jsQR !== 'undefined') {
          try {
            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var code = jsQR(imageData.data, canvas.width, canvas.height);
            if (code && code.data && code.data !== lastQRCodeData) {
              lastQRCodeData = code.data;
              if (status) status.textContent = 'QR code detected. Decoding...';
              onQRCodeDetected(code.data);
            }
          } catch (err) {
            console.warn('QR detection error', err);
            if (status) status.textContent = 'QR detection error. See console for details.';
          }
        } else {
          fallback.textContent = 'QR decoder is unavailable. Please reload the page.';
          fallback.style.display = 'flex';
          if (status) status.textContent = 'Decoder missing.';
        }
      }

      scanQRFrame = requestAnimationFrame(detect);
    }

    detect();
  }

  function openScanQRModal(element) {
    var classId = element.dataset.classId || '';
    var className = element.dataset.className || '';
    var scheduleDays = element.dataset.scheduleDays || '';
    var scheduleStart = element.dataset.scheduleStart || '';
    var scheduleEnd = element.dataset.scheduleEnd || '';
    var modal = document.getElementById('scanQRModal');
    var classField = document.getElementById('scanQRClassName');
    var classIdField = document.getElementById('scanQRClassId');
    var studentNameField = document.getElementById('scanQRStudentName');
    var studentIdField = document.getElementById('scanQRStudentId');
    var qrInput = document.getElementById('scanQRCode');
    var fallback = document.getElementById('scanQRCameraFallback');

    modal.dataset.scheduleDays = scheduleDays;
    modal.dataset.scheduleStart = scheduleStart;
    modal.dataset.scheduleEnd = scheduleEnd;

    classField.value = className;
    classIdField.value = classId;
    studentNameField.value = '';
    studentNameField.placeholder = 'Scan a student QR code to record attendance';
    studentIdField.value = '';
    qrInput.value = '';
    fallback.textContent = 'Point your device camera at a QR code to preview it here.';
    fallback.style.display = 'flex';

    modal.classList.add('active');
    startScanCamera();
  }

  function closeScanQRModal(event) {
    if (event) event.preventDefault();
    var modal = document.getElementById('scanQRModal');
    var rawDataField = document.getElementById('scanQRRawData');
    var status = document.getElementById('scanQRStatus');
    var fallback = document.getElementById('scanQRCameraFallback');

    modal.classList.remove('active');
    stopScanCamera();
    lastQRCodeData = '';
    if (rawDataField) rawDataField.value = 'No QR data detected yet.';
    if (status) status.textContent = 'Scanner is ready.';
    if (fallback) {
      fallback.textContent = 'Point your device camera at a QR code to preview it here.';
      fallback.style.display = 'flex';
    }
  }

  function getManilaNow() {
    var parts = new Intl.DateTimeFormat('en-US', {
      timeZone: 'Asia/Manila',
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false
    }).formatToParts(new Date());

    var values = {};
    parts.forEach(function(part) {
      if (part.type && part.value) {
        values[part.type] = part.value;
      }
    });

    return new Date(values.year + '-' + values.month + '-' + values.day + 'T' + values.hour + ':' + values.minute + ':' + values.second);
  }

  function verifyScheduleMatch(days, startTime, endTime) {
    if (!days || !startTime || !endTime) {
      return false;
    }

    var now = getManilaNow();
    var dayMap = {
      sunday: 'Sunday',
      monday: 'Monday',
      tuesday: 'Tuesday',
      wednesday: 'Wednesday',
      thursday: 'Thursday',
      friday: 'Friday',
      saturday: 'Saturday'
    };

    var today = dayMap[now.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase()];
    var scheduleDays = days.split(/[,;\s]+/).map(function(day) { return day.trim(); }).filter(Boolean);
    if (!scheduleDays.some(function(day) { return day.toLowerCase() === today.toLowerCase(); })) {
      return false;
    }

    function parseTime(value) {
      var timeString = (value || '').trim();
      if (!timeString) {
        return null;
      }

      var ampmMatch = timeString.match(/\s*(am|pm)$/i);
      var isPM = false;
      var isAM = false;

      if (ampmMatch) {
        isPM = ampmMatch[1].toLowerCase() === 'pm';
        isAM = ampmMatch[1].toLowerCase() === 'am';
        timeString = timeString.replace(/\s*(am|pm)$/i, '').trim();
      }

      var parts = timeString.split(':').map(function(part) { return parseInt(part, 10); });
      if (parts.length < 2 || isNaN(parts[0]) || isNaN(parts[1])) {
        return null;
      }

      var hour = parts[0];
      var minute = parts[1];

      if (isAM || isPM) {
        if (hour === 12) {
          hour = isAM ? 0 : 12;
        } else if (isPM) {
          hour += 12;
        }
      }

      var date = new Date(now);
      date.setHours(hour, minute, 0, 0);
      return date;
    }

    var start = parseTime(startTime);
    var end = parseTime(endTime);
    if (!start || !end) {
      return false;
    }

    return now >= start && now <= end;
  }

  function attemptAutoSubmitScanQR() {
    var qrInput = document.getElementById('scanQRCode');
    var studentIdField = document.getElementById('scanQRStudentId');
    var form = document.getElementById('scanQRForm');

    if (!qrInput || !studentIdField || !form) {
      return;
    }

    if (qrInput.value.trim() !== '' && studentIdField.value) {
      stopScanCamera();
      form.submit();
    }
  }

  function onQRCodeDetected(data) {
    var qrInput = document.getElementById('scanQRCode');
    var studentNameField = document.getElementById('scanQRStudentName');
    var studentIdField = document.getElementById('scanQRStudentId');
    var fallback = document.getElementById('scanQRCameraFallback');
    var status = document.getElementById('scanQRStatus');
    var rawDataField = document.getElementById('scanQRRawData');
    var form = document.getElementById('scanQRForm');
    var modal = document.getElementById('scanQRModal');

    if (!qrInput || !studentNameField || !studentIdField || !form || !modal || !data) {
      return;
    }

    var decoded;
    try {
      decoded = JSON.parse(data);
    } catch (err) {
      fallback.textContent = 'Scanned QR is not recognized. Please scan a student attendance QR code.';
      fallback.style.display = 'flex';
      return;
    }

    if (!decoded || decoded.type !== 'student_attendance' || !decoded.student_id) {
      fallback.textContent = 'Scanned QR is not a valid student attendance code.';
      fallback.style.display = 'flex';
      if (status) status.textContent = 'Invalid QR format.';
      return;
    }

    var scheduleDays = modal.dataset.scheduleDays || '';
    var scheduleStart = modal.dataset.scheduleStart || '';
    var scheduleEnd = modal.dataset.scheduleEnd || '';

    if (!verifyScheduleMatch(scheduleDays, scheduleStart, scheduleEnd)) {
      fallback.textContent = 'This class is not scheduled for the current day/time.';
      fallback.style.display = 'flex';
      if (status) status.textContent = 'Schedule mismatch. Scan again when the class is live.';
      return;
    }

    qrInput.value = data;
    if (rawDataField) rawDataField.value = data;
    studentNameField.value = decoded.student_name || 'Student #' + decoded.student_id;
    studentIdField.value = decoded.student_id;
    fallback.textContent = 'Detected ' + studentNameField.value + '. Recording attendance now...';
    fallback.style.display = 'flex';
    if (status) status.textContent = 'QR decoded successfully. Recording attendance...';

    stopScanCamera();
    setTimeout(function() {
      form.submit();
    }, 300);
  }

  document.addEventListener('click', function(event) {
    var studentDropdown = document.getElementById('addStudentDropdown');
    if (studentDropdown && !studentDropdown.contains(event.target)) {
      studentDropdown.classList.remove('open');
    }

    var daysDropdown = document.getElementById('editClassDaysDropdown');
    if (daysDropdown && !daysDropdown.contains(event.target)) {
      daysDropdown.classList.remove('open');
    }
  });
</script>
=======
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
    color: #6b7280 !important;
  }
  
  body.theme-light .class-room {
    color: #000000 !important;
  }
  
  body.theme-light .class-meta-row {
    color: #6b7280 !important;
  }
  
  body.theme-light .class-meta-row strong {
    color: #000000 !important;
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
>>>>>>> origin/branch-ni-kirb
@endsection
