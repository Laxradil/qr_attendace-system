@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<div class="content">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
        @forelse($classes as $classe)
            <div class="card" style="margin-bottom:0;padding:0;overflow:hidden;display:flex;flex-direction:column;">
                <div style="background:linear-gradient(135deg,var(--purple),var(--blue));padding:14px;border-bottom:1px solid var(--border);">
                    <div style="font-size:14px;font-weight:700;">{{ $classe->code }}</div>
                    <div style="font-size:11px;color:var(--purple-light);margin-top:2px;">{{ $classe->name }}</div>
                </div>
                <div style="padding:12px;flex:1;display:flex;flex-direction:column;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-size:11px;">
                        <span style="color:var(--text2);">Students:</span>
                        <span style="font-weight:600;color:var(--text);">{{ $classe->students_count }}</span>
                    </div>
                    @if($classe->description)
                        <div style="font-size:10px;color:var(--text2);margin-bottom:8px;line-height:1.4;">{{ substr($classe->description, 0, 80) }}{{ strlen($classe->description) > 80 ? '...' : '' }}</div>
                    @endif
                    <div style="flex:1;"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid var(--border);margin-top:8px;">
                        <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}" style="font-size:9px;">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-sm" onclick="showClassStudents({{ $classe->id }}, '{{ $classe->code }} - {{ addslashes($classe->name) }}')">View →</button>
                            <button class="btn btn-sm btn-p" onclick="showAddStudentModal({{ $classe->id }}, '{{ $classe->code }} - {{ addslashes($classe->name) }}')">+ Add Student</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column:1 / -1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px;text-align:center;">
                <div style="width:60px;height:60px;border-radius:50%;background:var(--navy3);display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:24px;">📚</div>
                <div style="font-size:13px;color:var(--text2);">No classes assigned yet</div>
                <div style="font-size:10px;color:var(--text3);margin-top:4px;">Your assigned classes will appear here</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:350px;">
        <span class="close" onclick="closeAddStudentModal()">&times;</span>
        <h3 id="addStudentClass"></h3>
        <form id="addStudentForm" method="POST" action="{{ route('professor.add-student') }}">
            @csrf
            <input type="hidden" name="class_id" id="addStudentClassId">
            <div style="margin-bottom:12px;">
                <label for="student_email" style="font-size:12px;">Student Email</label>
                <input type="email" name="student_email" id="studentEmailInput" class="input" required style="width:100%;margin-top:4px;">
            </div>
            <button type="submit" class="btn btn-p" style="width:100%;">Add Student</button>
        </form>
    </div>
</div>
<script>
function showAddStudentModal(classId, className) {
    document.getElementById('addStudentClass').innerText = className;
    document.getElementById('addStudentClassId').value = classId;
    document.getElementById('addStudentModal').style.display = 'block';
}
function closeAddStudentModal() {
    document.getElementById('addStudentModal').style.display = 'none';
}
function showClassStudents(classId, className) {
    var modal = document.getElementById('studentsModal');
    var title = document.getElementById('studentsModalTitle');
    var body = document.getElementById('studentsModalBody');
    title.textContent = 'Students in ' + className;
    body.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--text2);">Loading...</td></tr>';
    modal.style.display = 'flex';
    fetch('/professor/class/' + classId + '/students')
        .then(response => response.json())
        .then(data => {
            if (data.students.length === 0) {
                body.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--text2);">No students enrolled.</td></tr>';
            } else {
                body.innerHTML = data.students.map(s => '<tr><td>' + s.name + '</td><td>' + s.email + '</td><td>' + new Date(s.enrolled_at).toLocaleDateString() + '</td></tr>').join('');
            }
        })
        .catch(err => {
            body.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--red);">Error loading students.</td></tr>';
        });
}
function closeStudentsModal() {
    document.getElementById('studentsModal').style.display = 'none';
}
</script>

<!-- Students Modal -->
<div id="studentsModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#181a20;padding:24px 32px;border-radius:8px;min-width:400px;max-width:90vw;max-height:80vh;overflow:auto;">
        <div style="font-size:18px;font-weight:600;margin-bottom:12px;" id="studentsModalTitle">Class Students</div>
        <table style="width:100%;margin-bottom:12px;">
            <thead>
                <tr><th style="text-align:left;">Name</th><th style="text-align:left;">Email</th><th style="text-align:left;">Enrolled</th></tr>
            </thead>
            <tbody id="studentsModalBody">
                <tr><td colspan="3" style="text-align:center;color:var(--text2);">Loading...</td></tr>
            </tbody>
        </table>
        <button onclick="closeStudentsModal()" class="btn btn-p" style="float:right;">Close</button>
    </div>
</div>

@endsection
