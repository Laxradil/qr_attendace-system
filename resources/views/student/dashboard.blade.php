@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header', 'Student Dashboard')
@section('subheader', 'View your classes and generate QR codes for attendance.')

@section('content')
<!-- Stats Overview -->

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin-bottom:24px;">
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--green);font-weight:700;">{{ $totalPresent }}</div>
        <div class="stat-label" style="font-size:13px;">Present</div>
    </div>
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--amber);font-weight:700;">{{ $totalLate }}</div>
        <div class="stat-label" style="font-size:13px;">Late</div>
    </div>
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--red);font-weight:700;">{{ $totalAbsent }}</div>
        <div class="stat-label" style="font-size:13px;">Absent</div>
    </div>
    <div class="stat card" style="padding:18px 0;text-align:center;">
        <div class="stat-val" style="font-size:28px;color:var(--blue);font-weight:700;">{{ $classes->count() }}</div>
        <div class="stat-label" style="font-size:13px;">Enrolled Classes</div>
    </div>
</div>

<div class="g-6-4">
    <!-- Classes with QR Codes -->
    <div>
        <div class="sh" style="font-size:18px;font-weight:600;margin-bottom:10px;">Your Classes & QR Codes</div>
        <div class="card" style="padding:0 0 12px 0;">
            <div class="tbl-wrap" style="overflow-x:auto;">
                <table style="width:100%;border-collapse:separate;border-spacing:0 4px;">
                    <thead>
                        <tr style="background:var(--bg2);">
                            <th style="padding:10px 8px;text-align:left;">Class</th>
                            <th style="padding:10px 8px;text-align:left;">Professor</th>
                            <th style="padding:10px 8px;text-align:center;">QR Code</th>
                            <th style="padding:10px 8px;text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr style="background:var(--bg1);box-shadow:0 1px 4px rgba(0,0,0,0.03);">
                                <td style="padding:10px 8px;min-width:120px;">
                                    <div style="font-size:13px;font-weight:600;">{{ $class->name }}</div>
                                    <div style="font-size:11px;color:var(--text2);">{{ $class->code }}</div>
                                </td>
                                <td style="font-size:12px;padding:10px 8px;min-width:100px;">{{ $class->professor->name ?? 'N/A' }}</td>
                                <td style="text-align:center;padding:10px 8px;">
                                    <object data="{{ route('student.qr-code', $class->id) }}" type="image/svg+xml" style="width:48px;height:48px;border-radius:4px;border:1px solid var(--border);background:white;">
                                    </object>
                                </td>
                                <td style="text-align:center;padding:10px 8px;">
                                    <button type="button" class="btn btn-p" style="padding:7px 14px;font-size:11px;" onclick="showStudentQR('{{ $class->id }}', '{{ $class->code }} - {{ $class->name }}')">
                                        📱 Show QR
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center;color:var(--text2);padding:20px;">You are not enrolled in any classes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div>
        <div class="sh" style="font-size:18px;font-weight:600;margin-bottom:10px;">Recent Attendance</div>
        <div class="card" style="padding:0 0 12px 0;">
            @forelse($recentAttendance as $record)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0 12px 0;border-bottom:1px solid var(--border2);">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $record->classe->name ?? 'N/A' }}</div>
                        <div style="font-size:10px;color:var(--text2);">{{ $record->recorded_at?->format('M d, Y h:i A') }}</div>
                    </div>
                    @if($record->status === 'present')
                        <span class="badge bg" style="font-size:11px;">Present</span>
                    @elseif($record->status === 'late')
                        <span class="badge" style="background:var(--amber);color:white;font-size:11px;">Late</span>
                    @else
                        <span class="badge br" style="font-size:11px;">Absent</span>
                    @endif
                </div>
            @empty
                <div style="text-align:center;color:var(--text2);padding:20px;font-size:12px;">No attendance records yet.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- QR Code Modal for Student -->
<div id="student-qr-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:24px;border-radius:12px;max-width:350px;width:90%;text-align:center;">
        <div style="font-size:16px;font-weight:600;margin-bottom:4px;">Student Attendance QR</div>
        <div id="modal-class-name" style="font-size:11px;color:var(--text2);margin-bottom:16px;"></div>
        <object id="modal-student-qr-image" data="" type="image/svg+xml" style="width:220px;height:220px;border:1px solid var(--border);border-radius:8px;margin-bottom:12px;">
        </object>
        <div id="modal-student-qr-uuid" style="font-size:10px;color:var(--text3);margin-bottom:12px;font-family:monospace;word-break:break-all;"></div>
        <div style="font-size:10px;color:var(--text2);margin-bottom:16px;">Show this QR code to your professor for attendance scanning</div>
        <div style="display:flex;gap:8px;justify-content:center;">
            <button type="button" class="btn btn-p" onclick="downloadStudentQR()">Download</button>
            <button type="button" class="btn" onclick="closeStudentQRModal()">Close</button>
        </div>
    </div>
</div>

<script>
let currentStudentClassId = '';
let currentClassName = '';

function showStudentQR(classId, className) {
    currentStudentClassId = classId;
    currentClassName = className;
    document.getElementById('modal-student-qr-image').data = '/student/qr-code/' + classId;
    document.getElementById('modal-student-qr-uuid').textContent = 'Class ID: ' + classId;
    document.getElementById('modal-class-name').textContent = className;
    document.getElementById('student-qr-modal').style.display = 'flex';
}

function closeStudentQRModal() {
    document.getElementById('student-qr-modal').style.display = 'none';
}

function downloadStudentQR() {
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.src = '/student/qr-code/' + currentStudentClassId;
    
    img.onload = function() {
        const canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
        
        const link = document.createElement('a');
        link.download = 'student-qr-' + currentStudentQRUuid + '.jpg';
        link.href = canvas.toDataURL('image/jpeg', 0.9);
        link.click();
    };
    
    img.onerror = function() {
        window.open('/qr-codes/' + currentStudentQRUuid + '/image', '_blank');
    };
}

// Close modal on outside click
document.getElementById('student-qr-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStudentQRModal();
    }
});
</script>
@endsection
