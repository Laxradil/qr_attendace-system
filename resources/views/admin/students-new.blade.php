@extends('layouts.admin-new')

@section('title', 'Students - QR Attendance Admin')
@section('pageTitle', 'Students')
@section('pageSubtitle', 'Manage all student accounts.')

@section('content')
<div class="info-strip glass" style="margin-bottom:16px">
  ℹ️ Students are grouped by subject. Expand each subject card to view enrolled students.
</div>

<div class="class-list">
  @forelse($classes as $classe)
  <div class="class-card glass">
    <div class="class-head">
      <div>
        <h3>{{ $classe->code }} — {{ $classe->name }}</h3>
        <div class="class-meta" style="margin-top:6px">
          <span>👥 {{ $classe->students->count() }} students</span>
          <span>Prof: {{ $classe->professor->name ?? 'N/A' }}</span>
          <span class="pill green"><span class="status-dot" style="background:var(--green);color:var(--green)"></span> Active</span>
        </div>
      </div>
      <button class="btn slim" onclick="toggleTable(this)">⌄ Expand</button>
    </div>
    <div class="table-wrap" style="display:none">
      <table>
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Student</th>
            <th>Email</th>
            <th>Username</th>
            <th>Status</th>
            <th>Date Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($classe->students as $student)
          <tr>
            <td><span style="font-family:var(--mono);font-size:12px;color:var(--muted)">{{ $student->id }}</span></td>
            <td>
              <div class="user-cell">
                <span class="small-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                {{ $student->name }}
              </div>
            </td>
            <td class="muted">{{ $student->email }}</td>
            <td class="muted">{{ $student->username }}</td>
            <td><span class="pill green">Active</span></td>
            <td class="muted">{{ $student->created_at->format('M d, Y') }}</td>
            <td>
              <a href="{{ route('admin.users.edit', $student) }}" class="btn slim">Edit</a>
              <form method="POST" action="{{ route('admin.users.delete', $student) }}" style="display:inline" onsubmit="return confirm('Delete this student?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn danger slim">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:20px;color:var(--muted)">No students enrolled</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @empty
  <div class="card glass" style="text-align:center;padding:40px;color:var(--muted)">
    No classes found
  </div>
  @endforelse
</div>

<script>
  function toggleTable(btn) {
    const table = btn.parentElement.parentElement.nextElementSibling;
    if (table.style.display === 'none') {
      table.style.display = 'block';
      btn.textContent = '⌃ Collapse';
    } else {
      table.style.display = 'none';
      btn.textContent = '⌄ Expand';
    }
  }
</script>
@endsection
