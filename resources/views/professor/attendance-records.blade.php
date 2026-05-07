{{-- @var \Illuminate\Pagination\LengthAwarePaginator $records --}}
{{-- @var \Illuminate\Support\Collection $classes --}}
{{-- @var int $totalRecords --}}
{{-- @var int $presentCount --}}
{{-- @var int $lateCount --}}
{{-- @var int $absentCount --}}
{{-- @var int $excusedCount --}}
@extends('layouts.professor')

@section('title', 'Attendance Records - Professor')
@section('header', 'Attendance Records')
@section('subheader', 'View and update attendance records for your classes.')

@section('content')
<!-- Stats row -->
<div class="stats" style="grid-template-columns:repeat(5,1fr);margin-bottom:14px">
  <div class="stat glass" style="padding:12px">
    <div class="stat-icon blue" style="width:34px;height:34px;font-size:15px">▤</div>
    <div class="stat-body"><strong style="font-size:22px">{{ $totalRecords }}</strong><span>Total Records</span></div>
  </div>
  <div class="stat glass" style="padding:12px">
    <div class="stat-icon green" style="width:34px;height:34px;font-size:15px">✓</div>
    <div class="stat-body"><strong style="font-size:22px">{{ $presentCount }}</strong><span>Present</span></div>
  </div>
  <div class="stat glass" style="padding:12px">
    <div class="stat-icon yellow" style="width:34px;height:34px;font-size:15px">◷</div>
    <div class="stat-body"><strong style="font-size:22px">{{ $lateCount }}</strong><span>Late</span></div>
  </div>
  <div class="stat glass" style="padding:12px">
    <div class="stat-icon red" style="width:34px;height:34px;font-size:15px">✕</div>
    <div class="stat-body"><strong style="font-size:22px">{{ $absentCount }}</strong><span>Absent</span></div>
  </div>
  <div class="stat glass" style="padding:12px">
    <div class="stat-icon cyan" style="width:34px;height:34px;font-size:15px;background:rgba(0,229,255,.12);border:1px solid rgba(0,229,255,.2)">◈</div>
    <div class="stat-body"><strong style="font-size:22px">{{ $excusedCount ?? 0 }}</strong><span>Excused</span></div>
  </div>
</div>

<!-- Table with filters -->
<div class="glass-table glass">
  <form method="GET" action="{{ route('professor.attendance-records') }}">
    <div class="filter-row">
      <div class="filter-field">
        <label>Class</label>
        <select name="class_id" class="filter-select">
          <option value="">All Classes</option>
          @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
              {{ $class->subject_code }} — {{ $class->subject_name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="filter-field">
        <label>Date</label>
        <input type="date" name="date" class="filter-input" value="{{ request('date') }}">
      </div>
      <div style="display:flex;gap:8px;align-self:flex-end">
        <button type="submit" class="filter-btn apply">Filter</button>
        <a href="{{ route('professor.attendance-records') }}" class="filter-btn reset">Reset</a>
      </div>
    </div>
    
    <div class="table-wrap">
      <table id="attTable">
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>Student</th>
            <th>Class</th>
            <th>Status</th>
            <th>Minutes Late</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $record)
            <tr>
              <td>{{ $record->created_at->format('M d, Y H:i A') }}</td>
              <td>
                <div class="user-cell">
                  <div class="small-avatar">{{ strtoupper(substr($record->student->name ?? 'S', 0, 1)) }}</div>
                  <div>
                    <strong>{{ $record->student->name ?? 'Unknown' }}</strong>
                    <div class="muted">{{ $record->student->student_id ?? 'N/A' }}</div>
                  </div>
                </div>
              </td>
              <td>{{ $record->classe->subject_code ?? 'N/A' }} — {{ $record->classe->subject_name ?? 'N/A' }}</td>
              <td>
                <span class="pill {{ strtolower($record->status ?? 'present') }}">{{ ucfirst($record->status ?? 'Present') }}</span>
              </td>
              <td>{{ $record->minutes_late ?? '—' }}</td>
              <td>
                <a href="#" class="btn slim" onclick="alert('Edit attendance feature coming soon')">Edit</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center;padding:40px 20px;color:var(--muted)">
                No attendance records found
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </form>
  
  @if($records && $records->count() > 0)
    <div style="padding:14px;text-align:right;color:var(--muted);font-size:12px;border-top:1px solid rgba(255,255,255,.07)">
      Showing {{ $records->count() }} of {{ $totalRecords }} records
    </div>
  @endif
</div>

<style>
  .glass-table {
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: .3s ease;
  }
  
  .glass-table:hover {
    transform: translateY(-3px);
    border-color: rgba(255,255,255,.3);
  }
  
  .table-wrap {
    overflow-x: auto;
    border-radius: var(--radius-md);
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
  }
  
  table {
    width: 100%;
    min-width: 700px;
    border-collapse: separate;
    border-spacing: 0;
  }
  
  th, td {
    padding: 14px 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255,255,255,.07);
    vertical-align: middle;
  }
  
  th {
    background: rgba(255,255,255,.055);
    color: var(--faint);
    font-size: 11px;
    letter-spacing: .12em;
    text-transform: uppercase;
    font-weight: 700;
    position: sticky;
    top: 0;
    backdrop-filter: blur(8px);
  }
  
  th:first-child {
    border-radius: var(--radius-md) 0 0 0;
  }
  
  th:last-child {
    border-radius: 0 var(--radius-md) 0 0;
  }
  
  td {
    color: #e8eeff;
    font-size: 13.5px;
  }
  
  tr:last-child td {
    border-bottom: 0;
  }
  
  tr:hover td {
    background: rgba(255,255,255,.028);
  }
  
  .user-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
  }
  
  .small-avatar {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    display: grid;
    place-items: center;
    background: linear-gradient(145deg,rgba(139,92,255,.36),rgba(67,166,255,.2));
    font-size: 11px;
    font-weight: 900;
    border: 1px solid rgba(139,92,255,.3);
    flex-shrink: 0;
  }
  
  .muted {
    color: var(--muted);
    font-weight: 400;
    font-size: 11px;
  }
  
  .filter-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
    align-items: flex-end;
  }
  
  .filter-field {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }
  
  .filter-field label {
    font-size: 11px;
    color: var(--muted);
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
  }
  
  .filter-select,
  .filter-input {
    padding: 9px 12px;
    border-radius: 12px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13px;
    font-family: var(--font);
    outline: none;
    transition: .2s ease;
    min-width: 160px;
  }
  
  .filter-select:focus,
  .filter-input:focus {
    border-color: rgba(139,92,255,.5);
    box-shadow: 0 0 0 3px rgba(139,92,255,.12);
  }
  
  .filter-select option {
    background: #0a0d22;
  }
  
  .filter-btn {
    padding: 9px 16px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 13px;
    font-family: var(--font);
    cursor: pointer;
    transition: .2s ease;
    border: 0;
    text-decoration: none;
    display: inline-block;
  }
  
  .filter-btn.apply {
    background: linear-gradient(135deg,rgba(139,92,255,.9),rgba(67,166,255,.55));
    color: #fff;
  }
  
  .filter-btn.reset {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--muted);
  }
  
  .filter-btn:hover {
    transform: translateY(-2px);
  }
  
  .pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 999px;
    padding: 6px 11px;
    font-size: 11.5px;
    font-weight: 700;
    border: 1px solid rgba(255,255,255,.10);
    white-space: nowrap;
    letter-spacing: .01em;
  }
  
  .pill.green, .pill.present {
    color: #4dffa0;
    background: rgba(24,240,139,.11);
    border-color: rgba(24,240,139,.2);
  }
  
  .pill.red, .pill.absent {
    color: #ff7f96;
    background: rgba(255,61,114,.12);
    border-color: rgba(255,61,114,.22);
  }
  
  .pill.yellow, .pill.late {
    color: #ffd584;
    background: rgba(255,199,90,.12);
    border-color: rgba(255,199,90,.22);
  }
  
  .btn.slim {
    padding: 7px 10px;
    font-size: 12px;
    border-radius: 10px;
  }
</style>
@endsection
