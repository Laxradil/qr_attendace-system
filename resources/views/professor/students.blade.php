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
              <input type="text" class="student-search" placeholder="Search students..." style="padding:9px 12px;border-radius:12px;background:rgba(255,255,255,.96);border:1px solid rgba(0,0,0,.08);color:#0b1220;font-size:13px;font-family:var(--font);outline:none;transition:.2s ease;flex:1;min-width:160px" oninput="filterStudents(this)">
              <button type="button" class="btn primary slim" data-class-id="{{ $classe->id }}" data-class-name="{{ $classe->display_name }}" onclick="showAddStudentModal(this)">+ Add Student</button>
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
                          <button class="btn secondary slim" onclick="dropStudent({{ $student->id }}, '{{ $student->name }}', {{ $classe->id }})">Drop</button>
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
  
  .btn.secondary {
    background: rgba(255,255,255,.12);
    border-color: rgba(139,92,255,.36);
    color: #8b5cff;
  }
  
  .btn.secondary:hover {
    background: rgba(139,92,255,.12);
    border-color: rgba(139,92,255,.55);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.18), 0 8px 24px rgba(139,92,255,.18);
  }
  
  .btn.slim {
    padding: 7px 10px;
    font-size: 12px;
    border-radius: 10px;
  }

  
  .pill {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,.14);
  }
  
  .pill.green {
    color: #166534;
    background: #dcfce7;
    border-color: #bbf7d0;
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

<style>
  body.theme-light .glass {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .student-search {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .btn {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .btn:hover {
    background: #f9fafb !important;
    border-color: #d1d5db !important;
  }
  
  body.theme-light .btn.primary {
    background: #3b82f6 !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
  }
  
  body.theme-light .btn.secondary {
    background: #ffffff !important;
    border-color: rgba(139,92,255,.4) !important;
    color: #7c3aed !important;
  }
  
  body.theme-light .btn.secondary:hover {
    background: rgba(139,92,255,.12) !important;
    border-color: #8b5cff !important;
  }
  
  body.theme-light .pill {
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill.green {
    background: #ecfdf5 !important;
    border-color: #d1fae5 !important;
    color: #065f46 !important;
  }
  
  body.theme-light th {
    background: #f9fafb !important;
    color: #374151 !important;
    border-bottom: 1px solid #e5e7eb !important;
  }
  
  body.theme-light td {
    color: #000000 !important;
    border-bottom: 1px solid #e5e7eb !important;
  }
  
  body.theme-light tr:hover td {
    background: #f3f4f6 !important;
  }
  
  body.theme-light .small-avatar {
    background: #e5e7eb !important;
    border: 1px solid #d1d5db !important;
    color: #000000 !important;
  }
</style>

<div id="addStudentModal" style="display:none;position:fixed;inset:0;background:rgba(255,255,255,0.22);backdrop-filter:blur(18px);z-index:1050;align-items:center;justify-content:center;">
  <div class="card glass" style="max-width:420px;width:100%;padding:28px;border-radius:24px;position:relative;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
      <div>
        <div style="font-size:18px;font-weight:800;color:var(--text);">Add Student to class</div>
        <div id="addStudentModalSubtitle" style="font-size:13px;color:var(--muted);margin-top:6px;">Enter the student email to enroll</div>
      </div>
      <button type="button" class="btn slim" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.18);" onclick="hideAddStudentModal()">✕</button>
    </div>

    <form id="addStudentForm" action="{{ route('professor.add-student') }}" method="POST">
      @csrf
      <input type="hidden" id="modal_class_id" name="class_id" value="">
      <div style="display:grid;gap:16px;">
        <div style="display:flex;flex-direction:column;gap:8px;">
          <label style="font-size:13px;font-weight:700;color:var(--text);">Student Email</label>
          <input type="email" id="modal_student_email" name="student_email" placeholder="Enter a registered student email" required style="width:100%;padding:14px 16px;border-radius:14px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.85);color:var(--text);font-size:14px;outline:none;">
        </div>

        <div style="font-size:13px;color:var(--muted);line-height:1.6;">This will enroll an existing student account into the selected class. If the student does not exist, please ask them to register first.</div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <button type="submit" class="btn primary slim" style="flex:1;">Add Student</button>
          <button type="button" class="btn secondary slim" style="flex:1;" onclick="hideAddStudentModal()">Cancel</button>
        </div>
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
  
  function showAddStudentModal(button) {
    const classId = button.dataset.classId;
    const className = button.dataset.className || 'this class';
    document.getElementById('modal_class_id').value = classId;
    document.getElementById('addStudentModalSubtitle').textContent = `Add a student to ${className}`;
    document.getElementById('addStudentModal').style.display = 'flex';
    document.getElementById('modal_student_email').focus();
  }

  function hideAddStudentModal() {
    document.getElementById('addStudentModal').style.display = 'none';
    document.getElementById('modal_student_email').value = '';
  }

  function dropStudent(studentId, studentName, classId) {
    if (!confirm(`Are you sure you want to drop ${studentName} from this class?`)) {
      return;
    }

    const reasons = [
      'Schedule conflict',
      'Transfer to another section',
      'Medical reason',
      'Academic performance',
      'Personal reasons',
    ];

    let reason = prompt(
      `Reason for dropping ${studentName}:\n\n` +
      reasons.map((r, index) => `${index + 1}. ${r}`).join('\n'),
      reasons[0]
    );

    if (!reason) {
      return;
    }

    reason = reason.trim();
    const selectedIndex = parseInt(reason, 10);
    if (!reasons.includes(reason) && selectedIndex >= 1 && selectedIndex <= reasons.length) {
      reason = reasons[selectedIndex - 1];
    }

    if (!reasons.includes(reason)) {
      alert('Please choose a valid drop reason from the list.');
      return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('professor.drop-request') }}';
    form.innerHTML = `
      @csrf
      <input type="hidden" name="student_id" value="${studentId}">
      <input type="hidden" name="class_id" value="${classId}">
      <input type="hidden" name="reason" value="${reason}">
    `;
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  }
</script>

@endsection
