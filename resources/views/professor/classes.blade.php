@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
    <div>
        <div style="font-size:16px;font-weight:700;">My Classes</div>
        <div style="font-size:11px;color:var(--text2);margin-top:4px;">View and manage all your classes.</div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <div style="position:relative;min-width:220px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;left:12px;transform:translateY(-50%);color:var(--text2);">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
            </svg>
            <input id="classSearch" class="fi" type="search" placeholder="Search classes..." style="padding-left:34px;min-width:220px;" oninput="filterClasses()">
        </div>
        <select id="semesterFilter" class="fi" onchange="filterClasses()" style="min-width:150px;">
            <option value="all">All Semesters</option>
            <option value="spring">Spring</option>
            <option value="summer">Summer</option>
            <option value="fall">Fall</option>
        </select>
    </div>
</div>

<div id="classesList" style="display:grid;gap:10px;">
    @forelse($classes as $classe)
        <div class="card class-row" data-semester="{{ strtolower($classe->semester ?? 'all') }}" style="display:grid;grid-template-columns:1fr auto;align-items:center;gap:12px;padding:14px;">
            <div style="display:flex;gap:12px;align-items:center;">
                <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,var(--purple2),var(--blue));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:12px;flex-shrink:0;">
                    {{ strtoupper(substr($classe->code, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $classe->name }}</div>
                    <div style="font-size:11px;color:var(--text2);margin-top:4px;">{{ $classe->code }}</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;font-size:10px;color:var(--text2);">
                        <span>{{ $classe->students_count }} students</span>
                        <span>{{ $classe->schedules->isNotEmpty() ? $classe->schedules->first()->days . ' · ' . $classe->schedules->first()->time : 'No schedule' }}</span>
                        <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;justify-content:flex-end;flex-wrap:wrap;">
                <button class="btn btn-sm" onclick="showClassStudents({{ $classe->id }}, '{{ addslashes($classe->code . ' - ' . $classe->name) }}')">View Class</button>
                <a class="btn btn-sm btn-p" href="{{ route('professor.scan-qr', ['class_id' => $classe->id]) }}">Scan QR</a>
            </div>
        </div>
    @empty
        <div style="padding:24px;border:1px solid var(--border);border-radius:var(--radius-lg);text-align:center;color:var(--text2);">
            <div style="font-size:14px;font-weight:700;margin-bottom:10px;">No classes assigned yet</div>
            <div style="font-size:11px;">Once your classes are assigned, they will appear here for quick access.</div>
        </div>
    @endforelse
</div>

<div class="card" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:14px;">
    <div style="display:flex;flex-direction:column;gap:6px;">
        <div style="font-size:12px;color:var(--text2);">Classes Overview</div>
        <div style="font-size:18px;font-weight:700;color:var(--text);">{{ $classes->count() }}</div>
            <div style="font-size:10px;color:var(--text3);">Total classes currently assigned.</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;">
            <div style="font-size:12px;color:var(--text2);">Total Students</div>
            <div style="font-size:18px;font-weight:700;color:var(--text);">{{ $classes->sum('students_count') }}</div>
            <div style="font-size:10px;color:var(--text3);">Students across all your classes.</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;">
            <div style="font-size:12px;color:var(--text2);">Active Classes</div>
            <div style="font-size:18px;font-weight:700;color:var(--text);">{{ $classes->where('is_active', true)->count() }}</div>
            <div style="font-size:10px;color:var(--text3);">Classes marked active right now.</div>
        </div>
    </div>

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
function filterClasses() {
    const query = document.getElementById('classSearch').value.toLowerCase();
    const semester = document.getElementById('semesterFilter').value;
    document.querySelectorAll('.class-row').forEach(card => {
        const text = card.innerText.toLowerCase();
        const matchesQuery = !query || text.includes(query);
        const matchesSemester = semester === 'all' || card.dataset.semester === semester;
        card.style.display = matchesQuery && matchesSemester ? 'grid' : 'none';
    });
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
