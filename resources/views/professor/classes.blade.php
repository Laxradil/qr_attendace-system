@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;flex-wrap:wrap;margin-bottom:14px;">
    <div>
        <div style="font-size:16px;font-weight:700;">My Classes</div>
        <div style="font-size:11px;color:var(--text2);margin-top:4px;">View and manage all your classes.</div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex:1;min-width:0;justify-content:flex-end;">
        <div style="position:relative;flex:0 0 150px;min-width:120px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;left:12px;transform:translateY(-50%);color:var(--text2);">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
            </svg>
            <input id="classSearch" class="fi" type="search" placeholder="Search classes..." style="padding-left:34px;width:100%;" oninput="filterClasses()">
        </div>
        <select id="semesterFilter" class="fi" onchange="filterClasses()" style="min-width:100px;flex:0 0 110px;">
            <option value="all">All Semesters</option>
            <option value="spring">Spring</option>
            <option value="summer">Summer</option>
            <option value="fall">Fall</option>
        </select>
    </div>
</div>

<div id="classesList" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;align-items:start;">
    @forelse($classes as $classe)
        <div class="card class-row" data-semester="{{ strtolower($classe->semester ?? 'all') }}" style="overflow:hidden;display:flex;flex-direction:column;min-height:260px;">
            <div style="padding:16px;background:linear-gradient(135deg,rgba(21,101,192,0.95),rgba(0,150,136,0.95));color:#fff;display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                    <div>
                        <div style="font-size:10px;letter-spacing:0.08em;text-transform:uppercase;opacity:0.85;">{{ $classe->code }}</div>
                        <div style="font-size:16px;font-weight:800;line-height:1.1;margin-top:6px;">{{ \Illuminate\Support\Str::limit($classe->name, 30) }}</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:14px;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;">{{ strtoupper(substr($classe->code, 0, 1)) }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;">{{ strtoupper(substr($classe->professor?->name ?? 'P', 0, 1)) }}</div>
                    <div>
                        <div style="font-size:11px;opacity:0.8;">Professor</div>
                        <div style="font-size:13px;font-weight:700;">{{ $classe->professor?->name ?? 'Unassigned' }}</div>
                    </div>
                </div>
            </div>
            <div style="padding:16px;display:flex;flex-direction:column;justify-content:space-between;gap:14px;flex:1;background:var(--bg);">
                <div style="display:flex;flex-wrap:wrap;gap:8px;color:var(--text2);font-size:11px;">
                    <span style="background:rgba(0,0,0,0.04);padding:6px 10px;border-radius:999px;">{{ $classe->students_count }} students</span>
                    <span style="background:rgba(0,0,0,0.04);padding:6px 10px;border-radius:999px;">{{ $classe->schedules->isNotEmpty() ? $classe->schedules->first()->days : 'No schedule' }}</span>
                </div>
                <div style="font-size:11px;color:var(--text3);min-height:28px;">
                    {{ $classe->schedules->isNotEmpty() ? $classe->schedules->first()->time : 'Schedule not set' }}
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                    <button class="btn btn-sm" onclick="showClassStudents({{ $classe->id }}, '{{ addslashes($classe->code . ' - ' . $classe->name) }}')" style="flex:1;min-width:120px;">Students</button>
                    <button type="button" class="btn btn-sm btn-p" onclick="openScanModal({{ $classe->id }})" style="flex:1;min-width:120px;">Scan QR</button>
                </div>
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
        card.style.display = matchesQuery && matchesSemester ? 'flex' : 'none';
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

const scanQrRoute = "{{ route('professor.scan-qr') }}";
function openScanModal(classId) {
    const modal = document.getElementById('scanQrModal');
    const iframe = document.getElementById('scanQrModalIframe');
    iframe.onload = () => {
        try {
            iframe.contentWindow.startScanner();
        } catch (err) {
            console.warn('Auto-start camera failed:', err);
        }
    };
    iframe.src = `${scanQrRoute}?class_id=${classId}&modal=1`;
    modal.style.display = 'flex';
}
function closeScanModal() {
    const modal = document.getElementById('scanQrModal');
    const iframe = document.getElementById('scanQrModalIframe');
    iframe.onload = null;
    iframe.src = 'about:blank';
    modal.style.display = 'none';
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

<!-- Scan QR Modal -->
<div id="scanQrModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.75);z-index:10000;align-items:center;justify-content:center;padding:16px;">
    <div style="position:relative;width:100%;max-width:1040px;max-height:90vh;background:var(--navy2);border-radius:18px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,0.45);">
        <button onclick="closeScanModal()" class="btn btn-sm btn-d" style="position:absolute;top:16px;right:16px;z-index:10;">Close</button>
        <iframe id="scanQrModalIframe" src="" frameborder="0" scrolling="no" style="width:100%;height:100%;min-height:640px;overflow:hidden;border:none"></iframe>
    </div>
</div>

@endsection
