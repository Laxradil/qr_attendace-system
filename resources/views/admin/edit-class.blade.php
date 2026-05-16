@extends('layouts.admin-new')

@section('title', 'Edit Class - QR Attendance Admin')
@section('pageTitle', 'Edit Class')
@section('pageSubtitle', 'Update class details and assigned professor.')

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
  .form-group select,
  .form-group textarea{
    padding:12px 14px;
    border-radius:var(--radius-md);
    border:1px solid rgba(255,255,255,.12);
    background:rgba(8,12,30,.58);
    color:#fff;
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
    color:#fff;
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
    transition:background .2s ease, border-color .2s ease, color .2s ease;
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
    color:#fff;
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
      <div class="mini-icon stat-icon blue" style="width:38px;height:38px;border-radius:12px;font-size:16px">👥</div>
      <div><b>{{ $classe->students->count() }}</b><small>Enrolled Students</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon yellow" style="width:38px;height:38px;border-radius:12px;font-size:16px">🎓</div>
      <div><b>{{ $classe->professors->count() }}</b><small>Professors</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon green" style="width:38px;height:38px;border-radius:12px;font-size:16px">{{ $classe->is_active ? '✓' : '✕' }}</div>
      <div><b>{{ $classe->is_active ? 'Active' : 'Inactive' }}</b><small>Status</small></div>
    </div>
  </div>
</div>

<div class="glass-table glass" style="padding:32px">
  <form action="{{ route('admin.classes.update', $classe) }}" method="POST" class="form-grid">
    @csrf
    @method('PUT')

    <div class="form-row">
      <div class="form-group">
        <label for="code">Class Code *</label>
        <input type="text" id="code" name="code" value="{{ $classe->code }}" maxlength="20" placeholder="Enter class code" required>
      </div>
      <div class="form-group">
        <label for="room_code">Room Code *</label>
        <input type="text" id="room_code" name="room_code" value="{{ $classe->room_code }}" placeholder="Enter room code" required>
      </div>
      <div class="form-group">
        <label for="name">Class Name *</label>
        <input type="text" id="name" name="name" value="{{ $classe->name }}" placeholder="Enter class name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" placeholder="Enter class description (optional)">{{ $classe->description }}</textarea>
    </div>

    <div class="form-group">
      <label>Assigned Professors *</label>
      <div class="professor-selector">
        <div class="professor-list">
          <label>Available Professors</label>
          <select id="available-professors" multiple>
            @foreach($professors as $professor)
              @if(!$classe->professors->contains($professor->id))
                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="professor-buttons">
          <button type="button" id="add-professor" class="btn">Add →</button>
          <button type="button" id="remove-professor" class="btn">← Remove</button>
        </div>
        <div class="professor-list">
          <label>Selected Professors</label>
          <select id="selected-professors" name="professors[]" multiple required>
            @foreach($classe->professors as $professor)
              <option value="{{ $professor->id }}">{{ $professor->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="info-text">Select professors from available list and move them to selected</div>
    </div>

    <div class="form-group">
      <input type="hidden" name="is_active" value="0">
      <div class="checkbox-group">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $classe->is_active ? 'checked' : '' }}>
        <label for="is_active">Active class</label>
      </div>
    </div>

    <div class="form-actions" style="margin-top:32px">
      <button type="submit" class="btn primary">✓ Update Class</button>
      <a href="{{ route('admin.classes') }}" class="btn">Cancel</a>
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
</script>

<style>
  body.theme-light .glass,
  body.theme-light .glass-table {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .form-group label {
    color: #000000 !important;
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
  
  body.theme-light .checkbox-group {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .checkbox-group label {
    color: #000000 !important;
  }
  
  body.theme-light .info-text {
    color: #6b7280 !important;
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
</style>
@endsection
