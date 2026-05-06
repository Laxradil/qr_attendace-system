@extends('layouts.professor')

@section('title', 'Students - Professor')
@section('header', 'My Students')
@section('subheader', 'View all students in your assigned classes')

@section('content')
<div class="content">
    @if($classes->count())
        <div style="display:grid;gap:14px;">
            @foreach($classes as $classe)
                <details class="class-panel" {{ $loop->index === 0 ? 'open' : '' }}>
                    <summary>
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;width:100%;">
                            <div>
                                <div style="font-weight:700;">{{ $classe->display_name }}</div>
                                <div style="font-size:11px;color:var(--text3);margin-top:4px;">
                                    {{ $classe->students->count() }} student{{ $classe->students->count() === 1 ? '' : 's' }} enrolled
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text2);">
                                <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                                <span>{{ $loop->index === 0 ? 'collapse' : 'expand' }}</span>
                            </div>
                        </div>
                    </summary>

                    <div style="padding-top:14px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                            <span></span>
                            <button type="button" class="btn btn-p btn-sm" data-class-id="{{ $classe->id }}" onclick="handleAddStudentFromList(this)">+ Add Student</button>
                        </div>
                        @if($classe->students->count())
                            <div class="tbl-wrap" style="margin:0;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classe->students as $student)
                                            @php
                                                $requestKey = $classe->id . '_' . $student->id;
                                                $pending = $pendingRequests[$requestKey] ?? null;
                                            @endphp
                                            <tr>
                                                <td class="td-mono">{{ $student->student_id ?? 'N/A' }}</td>
                                                <td style="font-weight:500;">{{ $student->name }}</td>
                                                <td style="color:var(--text2);">{{ $student->email }}</td>
                                                <td>
                                                    <span class="badge {{ $student->is_active ? 'bg' : 'br' }}">
                                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($pending)
                                                        <span class="badge br" style="background:rgba(255, 165, 0, 0.16);color:var(--text);border:none;">Request Pending</span>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-d" style="padding:6px 10px;"
                                                            data-class-id="{{ $classe->id }}"
                                                            data-student-id="{{ $student->id }}"
                                                            data-student-name="{{ e($student->name) }}"
                                                            onclick="openDropRequestModal(this)">
                                                            Drop
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div style="padding:18px 0;color:var(--text2);font-size:13px;">No students enrolled in this class yet.</div>
                        @endif
                    </div>
                </details>
            @endforeach
        </div>
    @else
        <div style="padding:32px;text-align:center;color:var(--text2);">No students found in your assigned classes.</div>
    @endif
</div>

<style>
    .class-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 14px;
        transition: box-shadow 120ms ease;
    }
    .class-panel[open] {
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.08);
    }
    .class-panel summary {
        list-style: none;
        cursor: pointer;
        padding: 0;
    }
    .class-panel summary::-webkit-details-marker {
        display: none;
    }

    #dropRequestModal .card {
        background: var(--surface);
        border: 1px solid var(--border);
    }
    #dropRequestModal label,
    #dropRequestModal .card h3,
    #dropRequestModal .card div,
    #dropRequestModal option,
    #dropRequestModal .theme-select {
        color: var(--text);
    }
    #dropRequestModal .theme-select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        color: var(--text);
        background: var(--surface);
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
    }
    #dropRequestModal .theme-select option {
        background: var(--surface);
        color: var(--text);
    }
    #dropRequestModal .theme-select:hover,
    #dropRequestModal .theme-select:focus {
        border-color: rgba(255,255,255,0.18);
        background: rgba(255,255,255,0.04);
    }
    #dropRequestModal .theme-select option:checked {
        background: var(--surface);
        color: var(--text);
    }
    #dropRequestModal button.btn-p,
    #dropRequestModal button.btn-s {
        min-height: 42px;
    }
</style>

<!-- Add Student Modal -->
<div id="addStudentModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center;">
    <div class="card" style="width:90%;max-width:400px;padding:24px;">
        <h3 style="margin:0 0 16px 0;">Add Student to Class</h3>
        <form action="{{ route('professor.add-student') }}" method="POST">
            @csrf
            <input type="hidden" id="modalClassId" name="class_id" value="">
            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;">Student Email</label>
                <input type="email" name="student_email" required style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:4px;font-family:inherit;font-size:13px;box-sizing:border-box;">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-p" style="flex:1;">Add Student</button>
                <button type="button" class="btn btn-s" style="flex:1;" onclick="closeAddStudentModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function handleAddStudentFromList(button) {
    const classId = button.dataset.classId;
    openAddStudentModal(classId);
}

function openAddStudentModal(classId) {
    document.getElementById('modalClassId').value = classId;
    document.getElementById('addStudentModal').style.display = 'flex';
}

function closeAddStudentModal() {
    document.getElementById('addStudentModal').style.display = 'none';
}

function openDropRequestModal(button) {
    const classId = button.dataset.classId;
    const studentId = button.dataset.studentId;
    const studentName = button.dataset.studentName;

    document.getElementById('dropClassId').value = classId;
    document.getElementById('dropStudentId').value = studentId;
    document.getElementById('dropStudentName').textContent = studentName;
    document.getElementById('dropReasonSelect').value = '';
    document.getElementById('dropRequestModal').style.display = 'flex';
}

function closeDropRequestModal() {
    document.getElementById('dropRequestModal').style.display = 'none';
}

// Close modal when clicking outside
const addStudentModal = document.getElementById('addStudentModal');
if (addStudentModal) {
    addStudentModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddStudentModal();
        }
    });
}

const dropRequestModal = document.getElementById('dropRequestModal');
if (dropRequestModal) {
    dropRequestModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDropRequestModal();
        }
    });
}

</script>

<!-- Drop Request Modal -->
<div id="dropRequestModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.55);z-index:1000;justify-content:center;align-items:center;">
    <div class="card" style="width:90%;max-width:420px;padding:24px;">
        <h3 style="margin:0 0 16px 0;">Drop student</h3>
        <form action="{{ route('professor.drop-request') }}" method="POST">
            @csrf
            <input type="hidden" id="dropClassId" name="class_id" value="">
            <input type="hidden" id="dropStudentId" name="student_id" value="">
            <div style="margin-bottom:16px;">
                <div style="font-size:13px;font-weight:600;margin-bottom:8px;">Student</div>
                <div id="dropStudentName" style="padding:10px 12px;border:1px solid var(--border);border-radius:6px;background:var(--surface);"></div>
            </div>
            <div style="margin-bottom:18px;">
                <label for="dropReasonSelect" style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;">Reason for drop</label>
                <select id="dropReasonSelect" name="reason" required class="theme-select">
                    <option value="" disabled selected>Select reason</option>
                    <option value="Schedule conflict">Schedule conflict</option>
                    <option value="Transfer to another section">Transfer to another section</option>
                    <option value="Medical reason">Medical reason</option>
                    <option value="Academic performance">Academic performance</option>
                    <option value="Personal reasons">Personal reasons</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="submit" class="btn btn-p" style="flex:1;">Send Request</button>
                <button type="button" class="btn btn-s" style="flex:1;" onclick="closeDropRequestModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection
