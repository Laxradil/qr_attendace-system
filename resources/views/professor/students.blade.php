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
              <button type="button" class="btn primary slim" onclick="alert('Add student feature coming soon')">+ Add Student</button>
              <button type="button" class="btn slim refresh-btn" data-class-id="{{ $classe->id }}">Refresh</button>
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
                          @php $dropKey = $classe->id . '_' . $student->id; @endphp
                          @if(isset($pendingRequests[$dropKey]))
                            <span class="pill yellow">Pending</span>
                          @else
                            <span class="pill green">Active</span>
                          @endif
                        </td>
                        <td>
                          <button class="btn slim drop drop-btn" data-student-id="{{ $student->id }}" data-student-name="{{ addslashes($student->name) }}" data-class-id="{{ $classe->id }}">Drop</button>
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
  
  body.theme-light .btn.drop {
    background: #fef2f2 !important;
    border-color: #fecaca !important;
    color: #dc2626 !important;
  }
  
  body.theme-light .btn.drop:hover {
    background: #fee2e2 !important;
    border-color: #fca5a5 !important;
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
    const modal = document.getElementById('dropModalBackdrop');
    const messageEl = modal.querySelector('.modal-message');
    const confirmBtn = modal.querySelector('.modal-confirm');
    // set message and dataset values for later use
    messageEl.textContent = `Are you sure you want to drop ${studentName} from this class?`;
    confirmBtn.dataset.studentId = studentId;
    confirmBtn.dataset.classId = classId;
    // show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  // Modal handlers
  document.addEventListener('DOMContentLoaded', function () {
    // wire up drop buttons (use delegation)
    document.querySelectorAll('.drop-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const sid = this.dataset.studentId;
        const sname = this.dataset.studentName;
        const cid = this.dataset.classId;
        dropStudent(sid, sname, cid);
      });
    });
    const modal = document.getElementById('dropModalBackdrop');
    if (!modal) return;
    const cancel = modal.querySelector('.modal-cancel');
    const confirm = modal.querySelector('.modal-confirm');

    function hideModal() {
      modal.style.display = 'none';
      document.body.style.overflow = '';
      confirm.removeAttribute('data-student-id');
      confirm.removeAttribute('data-class-id');
    }

    cancel.addEventListener('click', hideModal);
    modal.addEventListener('click', function (e) {
      if (e.target === modal) hideModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.style.display === 'flex') hideModal();
    });

    confirm.addEventListener('click', function () {
      const studentId = this.dataset.studentId;
      const classId = this.dataset.classId;
      const reasonSelect = modal.querySelector('.modal-reason');
      const reason = reasonSelect ? reasonSelect.value : 'Personal reasons';
      if (!studentId || !classId) {
        hideModal();
        return;
      }
      // submit POST form to professor.drop-request route with reason
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `{{ route('professor.drop-request') }}`;
      form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="student_id" value="${studentId}">
        <input type="hidden" name="class_id" value="${classId}">
        <input type="hidden" name="reason" value="${reason}">
      `;
      document.body.appendChild(form);
      form.submit();
      document.body.removeChild(form);
    });

    // wire up refresh buttons
    document.querySelectorAll('.refresh-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        refreshClassStudents(this.dataset.classId, this);
      });
    });
  });

  // Drop confirmation modal markup and styles
</script>

<script>
  // Refresh students for a class via AJAX and update the table
  async function refreshClassStudents(classId, btn) {
    const url = `/professor/class/${classId}/students`;
    try {
      btn.disabled = true;
      btn.textContent = 'Refreshing...';
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Failed to fetch');
      const data = await res.json();
      const students = data.students || [];
      // find the table within the nearest details block
      const details = btn.closest('details');
      if (!details) return;
      const tbody = details.querySelector('table tbody');
      if (!tbody) return;
      // rebuild tbody
      tbody.innerHTML = '';
      if (students.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:18px;color:var(--muted)">No students enrolled in this class.</td></tr>`;
      } else {
        for (const s of students) {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td><span style="font-family:var(--mono);font-size:12px">${s.student_id ?? 'N/A'}</span></td>
            <td><div class="user-cell"><div class="small-avatar">${(s.name||'S').charAt(0).toUpperCase()}</div><strong>${s.name}</strong></div></td>
            <td>${s.email ?? 'N/A'}</td>
            <td><span class="pill green">Active</span></td>
            <td><button class="btn slim drop-btn" data-student-id="${s.id}" data-student-name="${(s.name||'').replace(/'/g,"\\'")}" data-class-id="${classId}">Drop</button></td>
          `;
          tbody.appendChild(tr);
        }
      }
      // re-wire drop buttons for the updated rows
      details.querySelectorAll('.drop-btn').forEach(function (b) {
        b.addEventListener('click', function () {
          const sid = this.dataset.studentId;
          const sname = this.dataset.studentName;
          const cid = this.dataset.classId;
          dropStudent(sid, sname, cid);
        });
      });
    } catch (e) {
      console.error(e);
      alert('Could not refresh students.');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Refresh';
    }
  }
</script>

@endsection

<!-- Drop confirmation modal -->
<div id="dropModalBackdrop" class="modal-backdrop" style="display:none;">
  <div class="modal-card small">
    <div class="modal-body">
      <div class="modal-icon">⚠️</div>
      <div>
        <div class="modal-title">Confirm Removal</div>
        <div class="modal-message" style="margin-top:8px;color:var(--muted);">Are you sure?</div>
        <div style="margin-top:12px">
          <label style="font-size:12px;color:var(--muted);display:block;margin-bottom:6px">Reason for drop</label>
          <select class="modal-reason" style="padding:8px 10px;border-radius:8px;border:1px solid rgba(255,255,255,.08);min-width:220px">
            <option>Schedule conflict</option>
            <option>Transfer to another section</option>
            <option>Medical reason</option>
            <option>Academic performance</option>
            <option selected>Personal reasons</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-actions">
      <button type="button" class="btn modal-cancel">Cancel</button>
      <button type="button" class="btn modal-confirm" style="margin-left:8px;background:linear-gradient(135deg,rgba(139,92,255,.9),rgba(67,166,255,.55));color:#fff;border:0">OK</button>
    </div>
  </div>
</div>

<style>
  /* Simple modal styles reusing theme variables */
  #dropModalBackdrop.modal-backdrop {
    position:fixed;inset:0;display:flex;align-items:center;justify-content:center;z-index:1100;background:rgba(2,6,23,0.6);backdrop-filter:blur(6px);
  }
  .modal-card.small{min-width:360px;max-width:90%;border-radius:14px;padding:18px;background:var(--glass);border:1px solid rgba(255,255,255,.06);box-shadow:0 20px 40px rgba(0,0,0,.6);}
  .modal-body{display:flex;gap:12px;align-items:center}
  .modal-icon{font-size:28px}
  .modal-title{font-weight:800;font-size:15px}
  .modal-actions{display:flex;justify-content:flex-end;margin-top:12px}
  /* Theme-specific adjustments */
  body.theme-light #dropModalBackdrop{background:rgba(255,255,255,0.45)}
  body.theme-light .modal-card.small{background:#ffffff;border:1px solid #e5e7eb;color:#0b1220}
  body.theme-ash #dropModalBackdrop{background:rgba(3,6,12,0.6)}
  body.theme-dark #dropModalBackdrop{background:rgba(2,6,23,0.7)}
  body.theme-onyx #dropModalBackdrop{background:rgba(0,0,0,0.75)}
</style>
