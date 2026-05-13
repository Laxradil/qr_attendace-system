@extends('layouts.admin-new')

@section('title', 'Classes - QR Attendance Admin')
@section('pageTitle', 'Classes')
@section('pageSubtitle', 'Manage class sections and subject assignments.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px">
    <a href="{{ route('admin.classes.create') }}" class="btn primary">＋ Add Class</a>
    <input type="text" id="tableSearch" placeholder="Search table..." style="flex:1;min-width:200px;max-width:350px;padding:10px 14px;border-radius:var(--radius-md);border:1px solid rgba(255,255,255,.12);background:rgba(8,12,30,.58);color:#fff;font-size:13px" onkeyup="filterTable(this)">
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Class Code</th>
          <th>Room Code</th>
          <th>Class Name</th>
          <th>Professor</th>
          <th>Students</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($classes as $class)
        <tr>
          <td><span style="font-family:var(--mono);font-size:13px">{{ $class->code }}</span></td>
          <td><span style="font-family:var(--mono);font-size:13px">{{ $class->room_code ?? '—' }}</span></td>
          <td><b>{{ $class->name }}</b></td>
          <td>{{ $class->professor->name ?? 'N/A' }}</td>
          <td>{{ $class->students->count() }} students</td>
          <td><span class="pill green">Active</span></td>
          <td>
            <a href="{{ route('admin.classes.enroll', $class) }}" class="btn slim">Enroll</a>
            <a href="{{ route('admin.classes.edit', $class) }}" class="btn slim">Edit</a>
            <form method="POST" action="{{ route('admin.classes.delete', $class) }}" style="display:inline" onsubmit="return confirm('Delete this class?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn danger slim">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">No classes found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

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
@endsection
