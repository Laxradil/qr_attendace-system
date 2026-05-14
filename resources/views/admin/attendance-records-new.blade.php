@extends('layouts.admin-new')

@section('title', 'Attendance Records - QR Attendance Admin')
@section('pageTitle', 'Attendance Records')
@section('pageSubtitle', 'View and manage all attendance records.')

@section('content')
<div class="stats">
  <div class="stat glass"><div class="stat-icon blue">▤</div><div class="stat-body"><strong>{{ $attendanceRecords->count() }}</strong><span>Total Records</span></div></div>
  <div class="stat glass"><div class="stat-icon green">✓</div><div class="stat-body"><strong>{{ $attendanceRecords->where('status', 'present')->count() }}</strong><span>Present</span></div></div>
  <div class="stat glass"><div class="stat-icon yellow">◷</div><div class="stat-body"><strong>{{ $attendanceRecords->where('status', 'late')->count() }}</strong><span>Late</span></div></div>
  <div class="stat glass"><div class="stat-icon red">✕</div><div class="stat-body"><strong>{{ $attendanceRecords->where('status', 'absent')->count() }}</strong><span>Absent</span></div></div>
</div>

<div class="glass-table glass" style="margin-top:16px">
<<<<<<< HEAD
  <div class="toolbar">
    <h3 style="font-size:16px;font-weight:800">📋 Attendance Records</h3>
    <div class="tools">
      <button class="btn">☰ Filter</button>
      <button class="btn" onclick="alert('Exporting CSV...')">📤 Export</button>
      <div class="search-bar" style="height:42px;width:240px">🔍 <span style="font-size:13px">Search records...</span></div>
    </div>
=======
  <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px">
    <h3 style="font-size:16px;font-weight:800;margin:0">📋 Attendance Records</h3>
    <input type="text" id="tableSearch" placeholder="Search table..." style="flex:1;min-width:200px;max-width:350px;padding:10px 14px;border-radius:var(--radius-md);border:1px solid rgba(255,255,255,.12);background:rgba(8,12,30,.58);color:#fff;font-size:13px" onkeyup="filterTable(this)">
>>>>>>> origin/branch-ni-kirb
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date & Time</th>
          <th>Student</th>
          <th>Class</th>
          <th>Status</th>
          <th>Minutes Late</th>
        </tr>
      </thead>
      <tbody>
        @forelse($attendanceRecords as $record)
        <tr>
          <td>
            <div style="font-family:var(--mono);font-size:12.5px">{{ $record->recorded_at->format('M d, Y') }}</div>
            <div class="muted" style="font-size:12px">{{ $record->recorded_at->format('h:i A') }}</div>
          </td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($record->student->name, 0, 2)) }}</span>
              <div>
                <div>{{ $record->student->name }}</div>
                <div class="muted" style="font-size:12px">{{ $record->student->email }}</div>
              </div>
            </div>
          </td>
          <td>
            <div>{{ $record->classe->code }} — {{ $record->classe->name }}</div>
            <div class="muted" style="font-size:12px">{{ $record->classe->code }}</div>
          </td>
          <td><span class="pill @if($record->status == 'present') green @elseif($record->status == 'late') yellow @else red @endif">{{ ucfirst($record->status) }}</span></td>
          <td class="muted">{{ $record->minutes_late ?? '—' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No attendance records found</td>
<<<<<<< HEAD
=======

<script>
function filterTable(input) {
  const searchValue = input.value.toLowerCase();
  const table = input.closest('.glass-table').querySelector('table');
  const rows = table.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    if (row.querySelector('td[colspan]')) return;
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
  });
}
</script>
>>>>>>> origin/branch-ni-kirb
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
<<<<<<< HEAD
=======

<style>
  /* Light theme solid overrides */
  body.theme-light #tableSearch {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill.green {
    background: #dcfce7 !important;
    color: #166534 !important;
  }
  
  body.theme-light .pill.yellow {
    background: #fef3c7 !important;
    color: #92400e !important;
  }
  
  body.theme-light .pill.red {
    background: #fee2e2 !important;
    color: #991b1b !important;
  }
</style>
>>>>>>> origin/branch-ni-kirb
