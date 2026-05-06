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
                        <div style="font-size:16px;font-weight:800;line-height:1.1;margin-top:6px;">{{ \Illuminate\Support\Str::limit($classe->display_name, 30) }}</div>
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
                    @if($classe->schedules->isNotEmpty() && ($classe->schedules->first()->start_time || $classe->schedules->first()->end_time))
                        {{ $classe->schedules->first()->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $classe->schedules->first()->start_time)->format('g:i A') : '' }}
                        @if($classe->schedules->first()->start_time && $classe->schedules->first()->end_time)
                            -
                        @endif
                        {{ $classe->schedules->first()->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $classe->schedules->first()->end_time)->format('g:i A') : '' }}
                    @elseif($classe->schedules->isNotEmpty() && $classe->schedules->first()->time)
                        {{ $classe->schedules->first()->time }}
                    @else
                        Schedule not set
                    @endif
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                    <button class="btn btn-sm" data-class-id="{{ $classe->id }}" data-class-name="{{ $classe->display_name }}" onclick="handleShowClassStudents(this)" style="flex:1;min-width:100px;">Students</button>
                    <button type="button" class="btn btn-sm" data-class-id="{{ $classe->id }}" data-class-name="{{ $classe->display_name }}" data-class-code="{{ $classe->code }}" data-class-desc="{{ $classe->description }}" data-schedule-id="{{ optional($classe->schedules->first())->id }}" data-schedule-days='@json(optional($classe->schedules->first()) && optional($classe->schedules->first())->days ? explode(",", optional($classe->schedules->first())->days) : [])' data-schedule-start-time="{{ optional($classe->schedules->first())->start_time }}" data-schedule-end-time="{{ optional($classe->schedules->first())->end_time }}" data-schedule-room="{{ optional($classe->schedules->first())->room }}" onclick="handleEditSchedule(this)" style="flex:1;min-width:100px;">Edit Class</button>
                    <button type="button" class="btn btn-sm btn-p" data-class-id="{{ $classe->id }}" data-class-name="{{ $classe->display_name }}" onclick="handleAddStudent(this)" style="flex:1;min-width:100px;">+ Add</button>
                    <button type="button" class="btn btn-sm btn-p" data-class-id="{{ $classe->id }}" onclick="handleScanQR(this)" style="flex:1;min-width:100px;">Scan QR</button>
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

<div id="addStudentModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.75);z-index:10000;align-items:center;justify-content:center;padding:16px;">
    <div style="position:relative;width:100%;max-width:420px;max-height:90vh;background:var(--navy2);border-radius:18px;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,0.45);padding:24px;">
        <button onclick="closeAddStudentModal()" class="btn btn-sm btn-d" style="position:absolute;top:16px;right:16px;z-index:10;">Close</button>
        <h3 id="addStudentClass" style="margin-bottom:14px;"></h3>
        <form id="addStudentForm" method="POST" action="{{ route('professor.add-student') }}">
            @csrf
            <input type="hidden" name="class_id" id="addStudentClassId">
            <div style="margin-bottom:12px;">
                <label for="studentSearch" style="font-size:12px;display:block;margin-bottom:6px;">Search student</label>
                <input type="search" id="studentSearch" class="fi" placeholder="Search by name or email..." oninput="renderAvailableStudentList()" style="width:100%;margin-top:4px;">
                <input type="hidden" name="student_id" id="selectedStudentId">
            </div>
            <div id="availableStudentList" style="max-height:260px;overflow:auto;border:1px solid var(--border);border-radius:12px;padding:8px;background:var(--navy3);margin-bottom:12px;">
                <div style="font-size:11px;color:var(--text2);">Search to select a student.</div>
            </div>
            <div style="margin-bottom:12px;font-size:11px;color:var(--text2);">Selected: <span id="selectedStudentLabel">None</span></div>
            <button type="submit" class="btn btn-p" style="width:100%;">Add Student</button>
        </form>
    </div>
</div>

<div id="editScheduleModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.75);z-index:10000;align-items:center;justify-content:center;padding:16px;">
    <div style="position:relative;width:100%;max-width:520px;max-height:90vh;background:var(--navy2);border-radius:18px;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,0.45);padding:24px;">
        <button onclick="closeEditScheduleModal()" class="btn btn-sm btn-d" style="position:absolute;top:16px;right:16px;z-index:10;">Close</button>
        <h3 style="margin-bottom:8px;">Edit Class</h3>
        <div id="editScheduleClassName" style="font-size:12px;color:var(--text2);margin-bottom:14px;"></div>
        <form id="editScheduleForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="schedule_id" id="editScheduleId">
            <div style="margin-bottom:12px;">
                <label for="editClassCode" style="font-size:12px;">Class Code</label>
                <input type="text" name="code" id="editClassCode" class="input" required style="width:100%;margin-top:4px;">
            </div>
            <div style="margin-bottom:12px;">
                <label for="editClassName" style="font-size:12px;">Class Name</label>
                <input type="text" name="name" id="editClassName" class="input" required style="width:100%;margin-top:4px;">
            </div>
            <div style="margin-bottom:12px;">
                <label for="editClassDescription" style="font-size:12px;">Description</label>
                <textarea name="description" id="editClassDescription" class="input" rows="3" style="width:100%;margin-top:4px;"></textarea>
            </div>
            <div style="margin-bottom:12px;">
                <label style="font-size:12px;display:block;margin-bottom:6px;">Days</label>
                <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;">
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="days[]" value="{{ $day }}" class="day-checkbox" style="width:16px;height:16px;">
                            <span style="font-size:12px;">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div style="margin-bottom:12px;">
                <label style="font-size:12px;display:block;margin-bottom:6px;">Time</label>
                <div style="display:flex;gap:8px;">
                    <div style="flex:1;">
                        <label for="editScheduleStartTime" style="font-size:10px;color:#666;">Start Time</label>
                        <input type="time" name="start_time" id="editScheduleStartTime" class="input" required style="width:100%;margin-top:2px;">
                    </div>
                    <div style="flex:1;">
                        <label for="editScheduleEndTime" style="font-size:10px;color:#666;">End Time</label>
                        <input type="time" name="end_time" id="editScheduleEndTime" class="input" required style="width:100%;margin-top:2px;">
                    </div>
                </div>
            </div>
            <div style="margin-bottom:12px;">
                <label for="editScheduleRoom" style="font-size:12px;">Room</label>
                <input type="text" name="room" id="editScheduleRoom" class="input" required style="width:100%;margin-top:4px;">
            </div>
            <button type="submit" class="btn btn-p" style="width:100%;">Save Class</button>
        </form>
    </div>
</div>
<script>
function handleEditSchedule(button) {
    const classId = button.dataset.classId;
    const className = button.dataset.className;
    const classCode = button.dataset.classCode || '';
    const classDescription = button.dataset.classDesc || '';
    let scheduleDays = [];
    if (button.dataset.scheduleDays) {
        try {
            scheduleDays = JSON.parse(button.dataset.scheduleDays);
        } catch (err) {
            scheduleDays = button.dataset.scheduleDays;
        }
    }
    const scheduleStartTime = button.dataset.scheduleStartTime || '';
    const scheduleEndTime = button.dataset.scheduleEndTime || '';
    const scheduleRoom = button.dataset.scheduleRoom || '';
    const scheduleId = button.dataset.scheduleId || '';

    const form = document.getElementById('editScheduleForm');
    const classNameEl = document.getElementById('editScheduleClassName');
    form.action = '/professor/classes/' + classId;
    document.getElementById('editScheduleId').value = scheduleId;
    document.getElementById('editClassCode').value = classCode;
    document.getElementById('editClassName').value = className;
    document.getElementById('editClassDescription').value = classDescription;
    setScheduleDays(scheduleDays);
    document.getElementById('editScheduleStartTime').value = normalizeTimeForInput(scheduleStartTime);
    document.getElementById('editScheduleEndTime').value = normalizeTimeForInput(scheduleEndTime);
    document.getElementById('editScheduleRoom').value = scheduleRoom;
    classNameEl.textContent = className;
    document.getElementById('editScheduleModal').style.display = 'flex';
}

function normalizeTimeForInput(timeValue) {
    if (!timeValue) {
        return '';
    }
    const parts = timeValue.split(':');
    if (parts.length < 2) {
        return '';
    }
    return `${parts[0].padStart(2, '0')}:${parts[1].padStart(2, '0')}`;
}

function parseDaysString(daysString) {
    const allowed = {
        mon: 'Monday',
        tue: 'Tuesday',
        wed: 'Wednesday',
        thu: 'Thursday',
        fri: 'Friday',
        sat: 'Saturday',
        sun: 'Sunday',
    };
    if (!daysString) {
        return [];
    }
    const tokens = daysString
        .replace(/\band\b/gi, ',')
        .replace(/[\/|;]/g, ',')
        .split(/[,\s]+/)
        .map(token => token.trim())
        .filter(Boolean);
    return tokens.map(token => {
        const lower = token.toLowerCase();
        if (allowed[lower]) {
            return allowed[lower];
        }
        if (lower.length <= 3) {
            return allowed[lower.slice(0, 3)] ?? null;
        }
        for (const key in allowed) {
            if (lower.startsWith(key)) {
                return allowed[key];
            }
        }
        return null;
    }).filter(Boolean);
}

function setScheduleDays(days) {
    const selected = Array.isArray(days)
        ? days.map(day => day.trim()).filter(Boolean)
        : parseDaysString(days);
    document.querySelectorAll('#editScheduleModal .day-checkbox').forEach(checkbox => {
        checkbox.checked = selected.includes(checkbox.value);
    });
}
function closeEditScheduleModal() {
    document.getElementById('editScheduleModal').style.display = 'none';
}
function handleShowClassStudents(button) {
    const classId = button.dataset.classId;
    const className = button.dataset.className;
    showClassStudents(classId, className);
}
function handleAddStudent(button) {
    const classId = button.dataset.classId;
    const className = button.dataset.className;
    showAddStudentModal(classId, className);
}
function handleScanQR(button) {
    const classId = button.dataset.classId;
    openScanModal(classId);
}
function showAddStudentModal(classId, className) {
    document.getElementById('addStudentClass').innerText = className;
    document.getElementById('addStudentClassId').value = classId;
    document.getElementById('studentSearch').value = '';
    document.getElementById('selectedStudentId').value = '';
    document.getElementById('selectedStudentLabel').innerText = 'None';
    renderAvailableStudentList();
    document.getElementById('addStudentModal').style.display = 'flex';
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

const professorAvailableStudents = @json($availableStudents);
const classExistingStudentIds = {
    @foreach($classes as $classe)
        {{ $classe->id }}: [@foreach($classe->students as $student){{ $student->id }},@endforeach],
    @endforeach
};

function renderAvailableStudentList() {
    const query = document.getElementById('studentSearch').value.trim().toLowerCase();
    const list = document.getElementById('availableStudentList');
    const selectedId = document.getElementById('selectedStudentId').value;
    const classId = document.getElementById('addStudentClassId').value;

    const filtered = professorAvailableStudents.filter(student => {
        const text = (student.name + ' ' + student.email).toLowerCase();
        return !query || text.includes(query);
    });

    if (!filtered.length) {
        list.innerHTML = '<div style="font-size:11px;color:var(--text2);">No students match that search.</div>';
        return;
    }

    list.innerHTML = filtered.map(student => {
        const enrolled = classExistingStudentIds[classId] && classExistingStudentIds[classId].includes(student.id);
        const active = selectedId === String(student.id);
        return `
            <button type="button" class="btn" style="display:flex;align-items:center;justify-content:space-between;width:100%;margin-bottom:6px;padding:8px 10px;text-align:left;${active ? 'border:1px solid var(--purple);background:rgba(108,92,231,0.12);' : 'background:var(--navy2);'}" ${enrolled ? 'disabled' : ''} onclick="selectAvailableStudent(${student.id}, ${classId})">
                <span style="display:flex;flex-direction:column;align-items:flex-start;gap:2px;">
                    <span style="font-size:12px;font-weight:700;color:var(--text);">${student.name}</span>
                    <span style="font-size:11px;color:var(--text2);">${student.email}</span>
                </span>
                <span style="font-size:11px;color:${enrolled ? 'var(--text3)' : 'var(--purple-light)'};">${enrolled ? 'Enrolled' : 'Select'}</span>
            </button>
        `;
    }).join('');
}

function selectAvailableStudent(studentId) {
    const student = professorAvailableStudents.find(s => s.id === studentId);
    if (!student) {
        return;
    }
    document.getElementById('selectedStudentId').value = student.id;
    document.getElementById('selectedStudentLabel').innerText = `${student.name} (${student.email})`;
    renderAvailableStudentList();
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
