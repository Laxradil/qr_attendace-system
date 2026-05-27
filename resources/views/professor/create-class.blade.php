@extends('layouts.professor')

@section('title', 'Create Class - QR Attendance')
@section('header', 'Create Class')
@section('subheader', 'Create a class and assign a professor.')

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
    color:var(--text);
    text-transform:uppercase;
  }
  .form-group input,
  .form-group select,
  .form-group textarea{
    padding:12px 14px;
    border-radius:var(--radius-md);
    border:1px solid rgba(255,255,255,.12);
    background:rgba(8,12,30,.58);
    color:var(--text);
    font-size:13px;
    transition:border-color .2s ease, box-shadow .2s ease;
    font-family:inherit;
  }
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus{
    outline:none;
    border-color:rgba(143,91,255,.5);
    box-shadow:inset 0 0 0 2px rgba(143,91,255,.1),0 0 16px rgba(143,91,255,.2);
  }
  .form-group input::placeholder,
  .form-group textarea::placeholder{
    color:rgba(255,255,255,.3);
  }
  .form-row{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:16px;
  }
  .schedule-panel{
    border-radius:var(--radius-lg);
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.04);
    padding:18px;
  }
  .section-title{
    font-size:12px;
    font-weight:800;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:rgba(255,255,255,.68);
    margin-bottom:14px;
  }
  .days-grid{
    display:grid;
    grid-template-columns:repeat(7,minmax(0,1fr));
    gap:8px;
  }
  .day-pill{
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:10px 8px;
    border-radius:14px;
    border:1px solid rgba(255,255,255,.12);
    background:rgba(8,12,30,.58);
    color:var(--text);
    font-size:12px;
    font-weight:700;
    letter-spacing:.02em;
    cursor:pointer;
    user-select:none;
    text-align:center;
    transition:border-color .2s ease, background .2s ease, box-shadow .2s ease;
  }
  .day-pill input{
    position:absolute;
    opacity:0;
    pointer-events:none;
  }
  .day-pill span{pointer-events:none;}
  .day-pill.selected{
    border-color:rgba(143,91,255,.7);
    background:rgba(143,91,255,.18);
    box-shadow:inset 0 0 0 1px rgba(143,91,255,.35);
  }
  @media (max-width: 980px){
    .days-grid{grid-template-columns:repeat(4,minmax(0,1fr));}
  }
  @media (max-width: 760px){
    .days-grid{grid-template-columns:repeat(2,minmax(0,1fr));}
  }
  @media (max-width: 980px){
    .form-row{
      grid-template-columns:repeat(2,minmax(0,1fr));
    }
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
  .professor-selector{
    display:flex;
    gap:12px;
    align-items:center;
  }
  .professor-list{
    flex:1;
    display:flex;
    flex-direction:column;
    gap:6px;
  }
  .professor-list label{
    font-size:12px;
    color:rgba(255,255,255,.6);
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.05em;
  }
  .professor-list select{
    width:100%;
    min-height:140px;
    padding:10px;
    border-radius:var(--radius-md);
    border:1px solid rgba(255,255,255,.12);
    background:rgba(8,12,30,.58);
    color:var(--text);
    font-size:13px;
  }
  .professor-list select:focus{
    outline:none;
    border:none;
    box-shadow:inset 0 0 0 2px rgba(143,91,255,.1),0 0 16px rgba(143,91,255,.2);
  }
  .professor-buttons{
    display:flex;
    flex-direction:column;
    gap:8px;
    justify-content:center;
    align-items:center;
  }
  .professor-buttons button{
    padding:10px 12px;
    font-size:12px;
    white-space:nowrap;
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
    color:var(--text);
    font-weight:600;
  }
  .info-text{
    font-size:12px;
    color:rgba(255,255,255,.6);
    margin-top:8px;
  }
</style>

<div class="card glass" style="margin-bottom:16px">
  <div class="mini-grid">
    <div class="mini">
      <div class="mini-icon stat-icon purple" style="width:38px;height:38px;border-radius:12px;font-size:16px">📚</div>
      <div><b>{{ App\Models\Classe::count() }}</b><small>Total Classes</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon blue" style="width:38px;height:38px;border-radius:12px;font-size:16px">🎓</div>
      <div><b>{{ App\Models\User::where('role', 'professor')->count() }}</b><small>Professors</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon green" style="width:38px;height:38px;border-radius:12px;font-size:16px">✓</div>
      <div><b>{{ App\Models\Classe::where('is_active', true)->count() }}</b><small>Active</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon yellow" style="width:38px;height:38px;border-radius:12px;font-size:16px">👥</div>
      <div><b>{{ \App\Models\User::where('role', 'student')->sum(DB::raw('1')) > 0 ? 'Ready' : '—' }}</b><small>Enrollment</small></div>
    </div>
  </div>
</div>

<div class="glass-table glass" style="padding:32px">
  <form action="{{ route('professor.classes.store') }}" method="POST" class="form-grid">
    @csrf

    <div class="form-row">
      <div class="form-group">
        <label for="code">Class Code *</label>
        <input type="text" id="code" name="code" value="{{ old('code') }}" maxlength="20" placeholder="Enter class code" required>
      </div>
      <div class="form-group">
        <label for="room_code">Room Code *</label>
        <input type="text" id="room_code" name="room_code" value="{{ old('room_code') }}" placeholder="Enter room code" required>
      </div>
      <div class="form-group">
        <label for="name">Class Name *</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter class name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" placeholder="Enter class description (optional)">{{ old('description') }}</textarea>
    </div>

    <div class="schedule-panel">
      <div class="section-title">Schedule</div>
      @php
        $selectedDays = collect(old('days', []))->map(fn ($day) => trim((string) $day))->filter()->values()->all();
      @endphp
      <div class="form-group" style="margin-bottom:14px;">
        <label>Days *</label>
        <div class="days-grid">
          @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
            <label class="day-pill{{ in_array($day, $selectedDays, true) ? ' selected' : '' }}">
              <input type="checkbox" name="days[]" value="{{ $day }}" {{ in_array($day, $selectedDays, true) ? 'checked' : '' }}>
              <span>{{ $day }}</span>
            </label>
          @endforeach
        </div>
      </div>
      <div class="form-row" style="grid-template-columns:repeat(2,minmax(0,1fr));">
        <div class="form-group">
          <label for="start_time">Start Time *</label>
          <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
        </div>
        <div class="form-group">
          <label for="end_time">End Time *</label>
          <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
        </div>
      </div>
      <div class="info-text">This schedule is saved once and shown on professor and student class views.</div>
    </div>

    <div class="form-group">
      <label>Assigned Professors *</label>
      <div class="professor-selector">
        <div class="professor-list">
          <label>Available Professors</label>
          <select id="available-professors" multiple>
            @foreach($professors as $professor)
              <option value="{{ $professor->id }}">{{ $professor->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="professor-buttons">
          <button type="button" id="add-professor" class="btn">Add →</button>
          <button type="button" id="remove-professor" class="btn">← Remove</button>
        </div>
        <div class="professor-list">
          <label>Selected Professors</label>
          <select id="selected-professors" name="professors[]" multiple required></select>
        </div>
      </div>
      <div class="info-text">Select professors from available list and move them to selected</div>
    </div>

    <div class="form-group">
      <input type="hidden" name="is_active" value="0">
      <div class="checkbox-group">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
        <label for="is_active">Active class</label>
      </div>
    </div>

    <div class="form-actions" style="margin-top:32px">
      <button type="submit" class="btn primary">✓ Create Class</button>
      <a href="{{ route('professor.classes') }}" class="btn">Cancel</a>
    </div>
  </form>
</div>

<script>
document.getElementById('add-professor').addEventListener('click', function(e) {
  e.preventDefault();
  moveOptions('available-professors', 'selected-professors');
});
document.getElementById('remove-professor').addEventListener('click', function(e) {
  e.preventDefault();
  moveOptions('selected-professors', 'available-professors');
});

function moveOptions(fromId, toId) {
  const from = document.getElementById(fromId);
  const to = document.getElementById(toId);
  Array.from(from.selectedOptions).forEach(option => {
    to.appendChild(option);
  });
}

document.querySelectorAll('.day-pill input[type="checkbox"]').forEach((checkbox) => {
  const syncState = () => checkbox.closest('.day-pill')?.classList.toggle('selected', checkbox.checked);
  checkbox.addEventListener('change', syncState);
  syncState();
});
</script>

<style>
  /* Light theme solid overrides */
  body.theme-light .form-group label {
    color: #000000;
  }
  
  body.theme-light .form-group input,
  body.theme-light .form-group select,
  body.theme-light .form-group textarea {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .form-group input:focus,
  body.theme-light .form-group select:focus,
  body.theme-light .form-group textarea:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1) !important;
  }
  
  body.theme-light .form-group input::placeholder,
  body.theme-light .form-group textarea::placeholder {
    color: #9ca3af !important;
  }
  
  body.theme-light .professor-list label {
    color: #6b7280 !important;
  }
  
  body.theme-light .professor-list select {
    background: #ffffff !important;
    color: #000000 !important;
  }
  
  body.theme-light .professor-list select:focus {
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1) !important;
  }
  
  body.theme-light .btn {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .btn.primary {
    background: #3b82f6 !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
  }
  
  body.theme-light .checkbox-group {
    background: #ffffff !important;
    border-color: #e5e7eb !important;
  }
  
  body.theme-light .checkbox-group label {
    color: #111827 !important;
  }
  
  body.theme-light .checkbox-group input[type="checkbox"] {
    accent-color: #2563eb !important;
  }
</style>

@endsection
