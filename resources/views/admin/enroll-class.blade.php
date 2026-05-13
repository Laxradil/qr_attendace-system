@extends('layouts.admin-new')

@section('title', 'Enroll Students - QR Attendance Admin')
@section('pageTitle', 'Enroll Students')
@section('pageSubtitle', 'Add selected students to this class section.')

@section('content')
<style>
    .enroll-shell{
        display:grid;
        gap:16px;
        max-width:980px;
    }
    .enroll-grid{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:12px;
    }
    .metric{
        border-radius:20px;
        padding:16px;
        border:1px solid rgba(255,255,255,.12);
        background:rgba(255,255,255,.06);
        display:flex;
        align-items:center;
        gap:12px;
    }
    .metric-icon{
        width:42px;
        height:42px;
        border-radius:14px;
        display:grid;
        place-items:center;
        font-size:18px;
        flex-shrink:0;
    }
    .metric-icon.purple{background:linear-gradient(145deg,rgba(139,92,255,.62),rgba(67,166,255,.2))}
    .metric-icon.blue{background:linear-gradient(145deg,rgba(67,166,255,.55),rgba(139,92,255,.22))}
    .metric-icon.green{background:linear-gradient(145deg,rgba(24,240,139,.42),rgba(67,166,255,.12))}
    .metric-icon.yellow{background:linear-gradient(145deg,rgba(255,199,90,.45),rgba(255,100,50,.15))}
    .metric b{
        display:block;
        font-size:20px;
        font-weight:900;
        letter-spacing:-.04em;
    }
    .metric small{
        color:var(--muted);
        font-size:11.5px;
        display:block;
        margin-top:2px;
    }
    .panel{
        border-radius:var(--radius-lg);
        padding:24px;
    }
    .panel-head{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:14px;
        margin-bottom:18px;
        flex-wrap:wrap;
    }
    .class-title{
        font-size:20px;
        font-weight:900;
        letter-spacing:-.04em;
        line-height:1.1;
    }
    .class-meta{
        margin-top:6px;
        color:var(--muted);
        font-size:13px;
        display:grid;
        gap:4px;
    }
    .pill-soft{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:6px 10px;
        border-radius:999px;
        font-size:11px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
        background:rgba(24,240,139,.12);
        color:#18f08b;
        border:1px solid rgba(24,240,139,.22);
    }
    .form-grid{
        display:grid;
        gap:14px;
    }
    .form-group{
        display:flex;
        flex-direction:column;
        gap:8px;
    }
    .form-group label{
        font-size:12px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
        color:#fff;
    }
    .student-select{
        width:100%;
        min-height:260px;
        padding:12px;
        border-radius:var(--radius-md);
        border:1px solid rgba(255,255,255,.12);
        background:rgba(8,12,30,.58);
        color:#fff;
        font:inherit;
        font-size:13px;
        line-height:1.45;
    }
    .student-select:focus{
        outline:none;
        border-color:rgba(143,91,255,.5);
        box-shadow:inset 0 0 0 2px rgba(143,91,255,.1),0 0 16px rgba(143,91,255,.2);
    }
    .helper-text{
        color:var(--muted);
        font-size:12px;
    }
    .actions{
        display:flex;
        flex-wrap:wrap;
        gap:12px;
        margin-top:10px;
    }
    @media (max-width: 980px){
        .enroll-grid{grid-template-columns:repeat(2,minmax(0,1fr));}
    }
    @media (max-width: 760px){
        .panel{padding:18px;}
        .enroll-grid{grid-template-columns:1fr;}
    }
</style>

<div class="enroll-shell">
    <div class="enroll-grid">
        <div class="metric glass">
            <div class="metric-icon purple">▦</div>
            <div>
                <b>{{ $classe->code }}</b>
                <small>Class Code</small>
            </div>
        </div>
        <div class="metric glass">
            <div class="metric-icon blue">👥</div>
            <div>
                <b>{{ $classe->students->count() }}</b>
                <small>Currently Enrolled</small>
            </div>
        </div>
        <div class="metric glass">
            <div class="metric-icon green">+</div>
            <div>
                <b>{{ $availableStudents->count() }}</b>
                <small>Available Students</small>
            </div>
        </div>
        <div class="metric glass">
            <div class="metric-icon yellow">🎓</div>
            <div>
                <b>{{ $classe->professors->count() }}</b>
                <small>Assigned Professors</small>
            </div>
        </div>
    </div>

    <div class="glass panel">
        <div class="panel-head">
            <div>
                <div class="class-title">{{ $classe->code }} - {{ $classe->name }}</div>
                <div class="class-meta">
                    <span>Assigned professors: {{ $classe->professors->pluck('name')->join(', ') ?: 'None' }}</span>
                    <span>Currently enrolled students: {{ $classe->students->count() }}</span>
                </div>
            </div>
            <span class="pill-soft">Enroll Mode</span>
        </div>

        <form action="{{ route('admin.classes.enroll.store', $classe) }}" method="POST" class="form-grid">
            @csrf

            <div class="form-group">
                <label for="student_ids">Select Students to Enroll *</label>
                <select id="student_ids" class="student-select" name="student_ids[]" multiple size="12" required>
                    @forelse($availableStudents as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} — {{ $student->email }}</option>
                    @empty
                        <option disabled>No available students to enroll.</option>
                    @endforelse
                </select>
                <div class="helper-text">Hold Ctrl / Cmd to select multiple students. Already enrolled students are blocked automatically.</div>
            </div>

            <div class="actions">
                <button type="submit" class="btn primary">Enroll Students</button>
                <a href="{{ route('admin.classes') }}" class="btn">Back to Classes</a>
            </div>
        </form>
    </div>
</div>
@endsection
