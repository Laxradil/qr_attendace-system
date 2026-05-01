@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header', 'Student Dashboard')
@section('subheader', 'View your classes and generate QR codes for attendance.')

@section('content')
<!-- Stats Overview -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:12px;">
    <div class="stat">
        <div class="stat-val" style="font-size:24px;color:var(--green);">{{ $totalPresent }}</div>
        <div class="stat-label">Present</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="font-size:24px;color:var(--amber);">{{ $totalLate }}</div>
        <div class="stat-label">Late</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="font-size:24px;color:var(--red);">{{ $totalAbsent }}</div>
        <div class="stat-label">Absent</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="font-size:24px;color:var(--blue);">{{ $classes->count() }}</div>
        <div class="stat-label">Enrolled Classes</div>
    </div>
</div>

<div class="g-6-4">
    <!-- Classes with QR Codes -->
    <div>
        <div class="sh">Your Classes & QR Codes</div>
        <div class="card">
            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Professor</th>
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>
                                    <div style="font-size:12px;font-weight:500;">{{ $class->name }}</div>
                                    <div style="font-size:10px;color:var(--text2);">{{ $class->code }}</div>
                                </td>
                                <td style="font-size:11px;">{{ $class->professor->name ?? 'N/A' }}</td>
                                <td>
                                    @if($class->qrCodes->count())
                                        <img src="{{ route('admin.qr-codes.image', $class->qrCodes->first()->uuid) }}" alt="QR" style="width:50px;height:50px;border-radius:4px;border:1px solid var(--border);">
                                    @else
                                        <span style="color:var(--text2);font-size:10px;">No active QR</span>
                                    @endif
                                </td>
                                <td>
                                    @if($class->qrCodes->count())
                                        <button type="button" class="btn btn-p" style="padding:6px 10px;font-size:10px;" onclick="showStudentQR('{{ $class->qrCodes->first()->uuid }}', '{{ $class->code }} - {{ $class->name }}')">
                                            📱 Show QR
                                        </button>
                                    @else
                                        <span style="color:var(--text2);font-size:10px;">-</span>
                                    @endif
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
        <div class="sh">Recent Attendance</div>
        <div class="card">
            @forelse($recentAttendance as $record)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border2);">
                    <div>
                        <div style="font-size:11px;font-weight:500;">{{ $record->classe->name ?? 'N/A' }}</div>
                        <div style="font-size:9px;color:var(--text2);">{{ $record->recorded_at?->format('M d, Y h:i A') }}</div>
                    </div>
                    @if($record->status === 'present')
                        <span class="badge bg">Present</span>
                    @elseif($record->status === 'late')
                        <span class="badge" style="background:var(--amber);color:white;">Late</span>
                    @else
                        <span class="badge br">Absent</span>
                    @endif
                </div>
            @empty
                <div style="text-align:center;color:var(--text2);padding:20px;font-size:11px;">No attendance records yet.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- QR Code Modal for Student -->
<div id="student-qr-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:24px;border-radius:12px;max-width:350px;width:90%;text-align:center;">
        <div style="font-size:16px;font-weight:600;margin-bottom:4px;">Student Attendance QR</div>
        <div id="modal-class-name" style="font-size:11px;color:var(--text2);margin-bottom:16px;"></div>
        <img id="modal-student-qr-image" src="" alt="QR Code" style="width:220px;height:220px;border:1px solid var(--border);border-radius:8px;margin-bottom:12px;">
        <div id="modal-student-qr-uuid" style="font-size:10px;color:var(--text3);margin-bottom:12px;font-family:monospace;word-break:break-all;"></div>
        <div style="font-size:10px;color:var(--text2);margin-bottom:16px;">Show this QR code to your professor for attendance scanning</div>
        <div style="display:flex;gap:8px;justify-content:center;">
            <button type="button" class="btn btn-p" onclick="downloadStudentQR()">Download</button>
            <button type="button" class="btn" onclick="closeStudentQRModal()">Close</button>
        </div>
    </div>
</div>

<script>
let currentStudentQRUuid = '';

function showStudentQR(uuid, className) {
    currentStudentQRUuid = uuid;
    document.getElementById('modal-student-qr-image').src = '/qr-codes/' + uuid + '/image';
    document.getElementById('modal-student-qr-uuid').textContent = uuid;
    document.getElementById('modal-class-name').textContent = className;
    document.getElementById('student-qr-modal').style.display = 'flex';
}

function closeStudentQRModal() {
    document.getElementById('student-qr-modal').style.display = 'none';
}

function downloadStudentQR() {
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.src = '/qr-codes/' + currentStudentQRUuid + '/image';
    
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
