@extends('layouts.professor')

@section('title', 'My Students - Professor')
@section('header', 'My Students')
@section('subheader', 'View all students in your assigned classes')

@section('content')
<style>
  .search-bar {
    display: none !important;
  }
</style>
@if($classes && $classes->count())
  <div style="display:grid;gap:14px">
    @foreach($classes as $classe)
      <div class="glass" style="border-radius:var(--radius-lg);padding:20px;transition:.3s ease">
        <details style="cursor:pointer" {{ $loop->index === 0 ? 'open' : '' }}>
          <summary style="display:flex;justify-content:space-between;align-items:center;gap:12px;list-style:none;user-select:none">
            <div>
              <div style="font-weight:700;font-size:15px">{{ $classe->display_name ?? 'Class' }}</div>
              <div style="font-size:11px;color:var(--muted);margin-top:4px">
                {{ $classe->students->count() ?? 0 }} student{{ ($classe->students->count() ?? 0) === 1 ? '' : 's' }} enrolled
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);flex-shrink:0">
              <span class="pill green">Active</span>
              <span style="transition:.2s ease" id="chevron-{{ $loop->index }}">▼</span>
            </div>
          </summary>

          <div style="padding-top:14px;border-top:1px solid rgba(255,255,255,.07);margin-top:14px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:10px;flex-wrap:wrap">
              <input type="text" class="student-search" placeholder="Search students..." style="padding:9px 12px;border-radius:12px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.28);color:var(--text);font-size:13px;font-family:var(--font);outline:none;transition:.2s ease;flex:1;min-width:160px" oninput="filterStudents(this)">
              <button type="button" class="btn primary slim" onclick="alert('Add student feature coming soon')">+ Add Student</button>
            </div>

            @if($classe->students && $classe->students->count())
              <div class="table-wrap">
                <table id="studentsTable">
                  <thead>
                    <tr>
                      <th>Student ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($classe->students as $student)
                      <tr>
                        <td>
                          <span style="font-family:var(--mono);font-size:12px">{{ $student->student_id ?? 'N/A' }}</span>
                        </td>
                        <td>
                          <div class="user-cell">
                            <div class="small-avatar">{{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}</div>
                            <strong>{{ $student->name ?? 'Unknown' }}</strong>
                          </div>
                        </td>
                        <td>{{ $student->email ?? 'N/A' }}</td>
                        <td>
                          <span class="pill green">Active</span>
                        </td>
                        <td>
                          <button class="btn slim drop" onclick="dropStudent({{ $student->id }}, '{{ $student->name }}', {{ $classe->id }})">Drop</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div style="text-align:center;padding:24px;color:var(--muted)">
                <div style="font-size:32px;margin-bottom:8px">👥</div>
                <div style="font-size:13px">No students enrolled in this class yet.</div>
              </div>
            @endif
          </div>
        </details>
      </div>
    @endforeach
  </div>
@else
  <div class="glass" style="border-radius:var(--radius-lg);padding:40px;text-align:center">
    <div style="font-size:48px;margin-bottom:12px">📚</div>
    <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:4px">No Classes Yet</div>
    <div style="font-size:13px;color:var(--muted)">You haven't been assigned any classes yet.</div>
  </div>
@endif

<style>
  details summary::-webkit-details-marker {
    display: none;
  }
  
  .btn {
    padding: 9px 16px;
    border-radius: 12px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13px;
    font-family: var(--font);
    outline: none;
    cursor: pointer;
    transition: .2s ease;
  }
  
  .btn:hover {
    transform: translateY(-2px);
    background: rgba(255,255,255,.13);
    border-color: rgba(255,255,255,.24);
  }
  
  .btn.primary {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));
    border-color: rgba(139,92,255,.5);
    color: #fff;
  }
  
  .btn.primary:hover {
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25), 0 10px 28px rgba(80,94,255,.38);
  }
  
  .btn.slim {
    padding: 7px 10px;
    font-size: 12px;
    border-radius: 10px;
  }
  
  .btn.drop {
    background: rgba(255,61,114,.15);
    border-color: rgba(255,61,114,.3);
    color: #ff3d72;
  }
  
  .btn.drop:hover {
    background: rgba(255,61,114,.28);
    border-color: rgba(255,61,114,.5);
    box-shadow: 0 8px 24px rgba(255,61,114,.2);
  }
  
  .pill {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,.14);
  }
  
  .pill.green {
    color: #4dffa0;
    background: rgba(24,240,139,.11);
    border-color: rgba(24,240,139,.2);
  }
  
  .table-wrap {
    overflow-x: auto;
    border-radius: var(--radius-md);
    scrollbar-width: thin;
  }
  
  table {
    width: 100%;
    min-width: 600px;
    border-collapse: separate;
    border-spacing: 0;
  }
  
  th, td {
    padding: 14px 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255,255,255,.07);
  }
  
  th {
    background: rgba(255,255,255,.12);
    color: var(--text);
    font-size: 11px;
    letter-spacing: .12em;
    text-transform: uppercase;
    font-weight: 700;
  }
  
  td {
    color: #f0f4ff;
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
</style>
            </div>
        </form>
    </div>
</div>

<script>
  function filterStudents(input) {
    const searchTerm = input.value.toLowerCase();
    const table = input.closest('.table-wrap')?.querySelector('table');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  }
  
  function dropStudent(studentId, studentName, classId) {
    if (!confirm(`Are you sure you want to drop ${studentName} from this class?`)) {
      return;
    }
    
    // Submit drop request to backend
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/professor/students/drop';
    form.innerHTML = `
      @csrf
      <input type="hidden" name="student_id" value="${studentId}">
      <input type="hidden" name="class_id" value="${classId}">
    `;
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }
</script>

@endsection
