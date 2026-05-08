@extends('layouts.admin-new')

@section('title', 'Create Class - QR Attendance Admin')
@section('pageTitle', 'Create Class')
@section('pageSubtitle', 'Create a class and assign a professor.')

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
    grid-template-columns:1fr 1fr;
    gap:16px;
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
    border:none;
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
  <form action="{{ route('admin.classes.store') }}" method="POST" class="form-grid">
    @csrf

    <div class="form-row">
      <div class="form-group">
        <label for="code">Class Code *</label>
        <input type="text" id="code" name="code" value="{{ old('code') }}" maxlength="20" placeholder="Enter class code" required>
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
      <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:8px;">Select professors from available list and move them to selected</div>
    </div>

    <div class="form-actions" style="margin-top:32px">
      <button type="submit" class="btn primary">✓ Create Class</button>
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
@endsection
