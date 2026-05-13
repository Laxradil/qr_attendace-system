@extends('layouts.professor')

@section('title', $classe->display_name . ' - Class Detail')
@section('header', $classe->display_name)
@section('subheader', 'View class details, students, and schedules')

@section('content')
<div class="content">
    <!-- Class Header -->
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <div style="font-size:18px;font-weight:700;margin-bottom:2px;">{{ $classe->display_name }}</div>
                <div style="font-size:11px;color:var(--text2);font-family:'JetBrains Mono',monospace;">{{ $classe->code }}</div>
                @if($classe->description)
                    <div style="font-size:11px;color:var(--text);margin-top:8px;line-height:1.4;">{{ $classe->description }}</div>
                @endif
            </div>
            <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;">
            <a class="btn btn-p btn-sm" href="{{ route('professor.scan-qr', ['class_id' => $classe->id]) }}">Scan QR</a>
        </div>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:12px;">
        <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $classe->students->count() }}</div><div class="stat-label">Students</div></div></div>
        <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val">{{ $classe->schedules->count() }}</div><div class="stat-label">Schedules</div></div></div>
        <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val">{{ $classe->attendanceRecords()->count() }}</div><div class="stat-label">Records</div></div></div>
    </div>

    <!-- Students List -->
    <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <div class="sh" style="margin:0;">Enrolled Students ({{ $classe->students->count() }})</div>
            <button class="btn btn-p btn-sm" onclick="document.getElementById('addStudentModal').style.display='block'">+ Add Student</button>
        </div>
        <div class="tbl-wrap">
            <table>
                <thead><tr><th>Student ID</th><th>Name</th><th>Email</th><th>Enrolled</th></tr></thead>
                <tbody>
                    @forelse($classe->students as $student)
                        <tr>
                            <td class="td-mono">{{ $student->student_id ?? 'N/A' }}</td>
                            <td>{{ $student->name }}</td>
                            <td style="color:var(--text2);">{{ $student->email }}</td>
                            <td style="color:var(--text2);">{{ $student->pivot->enrolled_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:var(--text2);">No students enrolled yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Schedules -->
    @if($classe->schedules->count() > 0)
        <div>
            <div class="sh">Class Schedules ({{ $classe->schedules->count() }})</div>
            <div class="tbl-wrap">
                <table>
                    <thead><tr><th>Days</th><th>Time</th><th>Room</th><th>Professor</th></tr></thead>
                    <tbody>
                        @foreach($classe->schedules as $schedule)
                            <tr>
                                <td style="font-weight:600;">{{ $schedule->days }}</td>
                                <td>{{ $schedule->time }}</td>
                                <td style="text-align:center;">{{ $schedule->room }}</td>
                                <td style="color:var(--text2);">{{ $schedule->professor }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center;">
    <div class="card" style="width:90%;max-width:400px;padding:24px;">
        <h3 style="margin:0 0 16px 0;">Add Student to {{ $classe->display_name }}</h3>
        <form action="{{ route('professor.add-student') }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $classe->id }}">
            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;">Student Email</label>
                <input type="email" name="student_email" required style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:4px;font-family:inherit;font-size:13px;box-sizing:border-box;">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-p" style="flex:1;">Add Student</button>
                <button type="button" class="btn btn-s" style="flex:1;" onclick="document.getElementById('addStudentModal').style.display='none';">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        if (window.location.search.includes('action=add-student')) {
            var modal = document.getElementById('addStudentModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }
    })();
</script>

@endsection
