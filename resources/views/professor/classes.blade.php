@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<div style="display:flex;flex-direction:column;gap:22px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:18px;flex-wrap:wrap;">
        <div>
            <div style="font-size:28px;font-weight:700;color:var(--text);">My Classes</div>
            <div style="font-size:13px;color:var(--text2);margin-top:6px;">View and manage all your classes.</div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(2,minmax(240px,1fr));gap:12px;width:min(740px,100%);">
            <input type="text" placeholder="Search classes..." class="fi" style="width:100%;" />
            <select class="fi" style="width:100%;">
                <option>All Semesters</option>
                <option>Spring 2024</option>
                <option>Summer 2024</option>
            </select>
        </div>
    </div>

    <div style="display:grid;gap:14px;">
        @forelse($classes as $classe)
            @php
                $schedule = $classe->schedules->first();
                $scheduleText = $schedule ? trim(($schedule->days ? $schedule->days . ' · ' : '') . ($schedule->time ?? '') . ($schedule->room ? ' · ' . $schedule->room : '')) : 'No schedule yet';
                $initials = strtoupper(substr($classe->name, 0, 2));
            @endphp
            <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:18px;padding:18px 22px;">
                <div style="display:flex;gap:16px;align-items:flex-start;min-width:0;flex:1;">
                    <div style="width:54px;height:54px;border-radius:18px;background:linear-gradient(135deg,#4b57ff,#6f52ff);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;color:#fff;flex-shrink:0;">{{ $initials }}</div>
                    <div style="min-width:0;">
                        <div style="font-size:16px;font-weight:700;color:var(--text);">{{ $classe->name }}</div>
                        <div style="font-size:12px;color:var(--text2);margin-top:6px;">{{ $classe->code }} · {{ $classe->description ?? 'No section info' }}</div>
                        <div style="font-size:12px;color:var(--text2);margin-top:10px;display:flex;gap:10px;flex-wrap:wrap;">
                            <span style="display:flex;align-items:center;gap:8px;"><i class="fas fa-calendar"></i>{{ $scheduleText }}</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;justify-content:flex-end;">
                    <div style="text-align:right;min-width:80px;">
                        <div style="font-size:11px;color:var(--text2);">Students</div>
                        <div style="font-size:16px;font-weight:700;color:var(--text);">{{ $classe->students_count }}</div>
                    </div>
                    <a href="{{ route('professor.class-detail', $classe) }}" class="btn" style="padding:10px 18px;">View Class</a>
                    <a href="{{ route('professor.scan-qr') }}?class_id={{ $classe->id }}" class="btn btn-p" style="padding:10px 18px;">Scan QR</a>
                </div>
            </div>
        @empty
            <div class="card" style="padding:30px;text-align:center;">
                <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No classes found</div>
                <div style="color:var(--text2);">You currently have no assigned classes. Once a class is assigned, it will appear here.</div>
            </div>
        @endforelse
    </div>

    <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:16px;padding:18px 22px;">
        <div style="display:flex;align-items:center;gap:14px;min-width:0;">
            <div style="width:44px;height:44px;border-radius:14px;background:rgba(111,82,255,0.14);display:flex;align-items:center;justify-content:center;color:#6f52ff;flex-shrink:0;"><i class="fas fa-chart-line"></i></div>
            <div style="min-width:0;">
                <div style="font-size:11px;font-weight:600;letter-spacing:0.18em;text-transform:uppercase;color:var(--text2);">Classes Overview · This Semester</div>
                <div style="font-size:18px;font-weight:700;color:var(--text);margin-top:6px;">My Classes</div>
            </div>
        </div>
        <div style="display:flex;gap:16px;flex-wrap:wrap;justify-content:flex-end;">
            <div style="min-width:120px;">
                <div style="font-size:11px;color:var(--text2);margin-bottom:6px;">Total Classes</div>
                <div style="font-size:18px;font-weight:700;">{{ $totalClasses }}</div>
            </div>
            <div style="min-width:120px;">
                <div style="font-size:11px;color:var(--text2);margin-bottom:6px;">Total Students</div>
                <div style="font-size:18px;font-weight:700;">{{ $totalStudents }}</div>
            </div>
            <div style="min-width:120px;">
                <div style="font-size:11px;color:var(--text2);margin-bottom:6px;">Avg Attendance</div>
                <div style="font-size:18px;font-weight:700;color:var(--green);">{{ $attendanceRate }}%</div>
            </div>
        </div>
    </div>
</div>
@endsection
