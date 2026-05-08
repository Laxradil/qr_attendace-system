@extends('layouts.admin-new')

@section('title', 'Students - QR Attendance Admin')
@section('pageTitle', 'Students')
@section('pageSubtitle', 'Manage all student accounts.')

@section('content')
<style>
  .students-note{
    border-radius:22px;
    padding:14px 18px;
    color:var(--muted);
    font-size:13.5px;
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:16px;
  }

  .students-list{
    display:grid;
    gap:14px;
  }

  .student-class{
    border-radius:var(--radius-lg);
    overflow:hidden;
    padding:0;
  }

  .student-summary{
    list-style:none;
    cursor:pointer;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    padding:18px 20px;
  }

  .student-summary::-webkit-details-marker{display:none}

  .student-title{
    font-size:18px;
    font-weight:800;
    letter-spacing:-.03em;
    margin:0 0 6px;
  }

  .student-meta{
    display:flex;
    align-items:center;
    gap:14px;
    flex-wrap:wrap;
    color:var(--muted);
    font-size:13px;
  }

  .student-meta span{
    display:inline-flex;
    align-items:center;
    gap:6px;
  }

  .student-toggle{
    flex-shrink:0;
    border:1px solid rgba(255,255,255,.15);
    background:rgba(255,255,255,.08);
    color:#fff;
    border-radius:12px;
    padding:8px 12px;
    font-weight:700;
    font-size:12px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
    min-width:92px;
  }

  .student-body{
    padding:0 20px 18px;
  }

  .student-table-shell{
    margin-top:2px;
    border-radius:18px;
    overflow:hidden;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.03);
  }

  .student-empty{
    padding:18px 0 2px;
    color:var(--muted);
    font-size:13px;
  }

  .student-id{
    font-family:var(--mono);
    font-size:12px;
    color:var(--muted);
  }

  .student-actions{
    white-space:nowrap;
    display:flex;
    gap:6px;
    align-items:center;
  }

  .student-class[open] .student-toggle{opacity:.95}

  @media (max-width: 760px){
    .student-summary{padding:16px}
    .student-body{padding:0 16px 16px}
    .student-title{font-size:16px}
  }
</style>

<div class="info-strip glass students-note" style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center">
  <span style="flex:1">ℹ️ Students are grouped by subject. Expand each subject card to view enrolled students.</span>
  <input type="text" id="tableSearch" placeholder="Search table..." style="flex:1;min-width:200px;max-width:350px;padding:10px 14px;border-radius:var(--radius-md);border:1px solid rgba(255,255,255,.12);background:rgba(8,12,30,.58);color:#fff;font-size:13px" onkeyup="filterStudentTable(this)">
</div>

<div class="students-list">
  @forelse($classes as $classe)
    <details class="class-card glass student-class" @if($loop->first) open @endif>
      <summary class="student-summary">
        <div>
          <h3 class="student-title">{{ $classe->code }} — {{ $classe->name }}</h3>
          <div class="student-meta">
            <span>👥 {{ $classe->students->count() }} students</span>
            <span>Prof: {{ $classe->professor->name ?? 'N/A' }}</span>
            <span class="pill green"><span class="status-dot" style="background:var(--green);color:var(--green)"></span> Active</span>
          </div>
        </div>
        <span class="student-toggle"></span>
      </summary>

      <div class="student-body">
        @if($classe->students->count())
          <div class="student-table-shell">
            <div class="table-wrap" style="margin:0;">
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
                      <td><span class="student-id">{{ $student->student_id ?: 'N/A' }}</span></td>
                      <td>
                        <div class="user-cell">
                          <span class="small-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                          <span>{{ $student->name }}</span>
                        </div>
                      </td>
                      <td class="muted">{{ $student->email }}</td>
                      <td class="muted">{{ $student->username }}</td>
                      <td><span class="pill green">Active</span></td>
                      <td class="muted">{{ $student->created_at?->format('M d, Y') }}</td>
                      <td>
                        <div class="student-actions">
                          <a href="{{ route('admin.users.edit', $student) }}" class="btn slim">Edit</a>
                          <form method="POST" action="{{ route('admin.users.delete', $student) }}" style="display:inline" onsubmit="return confirm('Delete this student?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn danger slim">Delete</button>
                          </form>
                        </div>
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
        @else
          <div class="student-empty">No students enrolled in this class yet.</div>
        @endif
      </div>
    </details>
  @empty
    <div class="card glass" style="text-align:center;padding:40px;color:var(--muted)">
      No classes found
    </div>
  @endforelse
</div>

<script>
function filterStudentTable(input) {
  const searchValue = input.value.toLowerCase();
  const classes = document.querySelectorAll('.student-class');
  
  classes.forEach(classCard => {
    const rows = classCard.querySelectorAll('tbody tr');
    let anyVisibleRow = false;
    
    rows.forEach(row => {
      if (row.querySelector('td[colspan]')) {
        row.style.display = 'none';
        return;
      }
      const text = row.textContent.toLowerCase();
      const isVisible = text.includes(searchValue);
      row.style.display = isVisible ? '' : 'none';
      if (isVisible) anyVisibleRow = true;
    });
    
    classCard.style.display = anyVisibleRow || searchValue === '' ? '' : 'none';
  });
}
</script>

<script>
  document.querySelectorAll('.student-class').forEach((details) => {
    const toggle = details.querySelector('.student-toggle');

    const sync = () => {
      toggle.textContent = details.open ? '⌃ Collapse' : '⌄ Expand';
    };

    sync();
    details.addEventListener('toggle', sync);
  });
</script>
@endsection
