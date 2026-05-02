@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header', 'Student Dashboard')
@section('subheader', 'View your classes and generate QR codes for attendance.')

@section('content')
<div style="padding:24px;">
    <div style="display:flex;flex-wrap:wrap;gap:20px;margin-bottom:24px;">
        <div style="flex:1 1 170px;min-width:170px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:20px;text-align:center;">
            <div style="font-size:26px;font-weight:700;color:var(--green);">{{ $totalPresent }}</div>
            <div style="font-size:13px;color:rgba(255,255,255,0.7);margin-top:6px;letter-spacing:.03em;">Present</div>
        </div>
        <div style="flex:1 1 170px;min-width:170px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:20px;text-align:center;">
            <div style="font-size:26px;font-weight:700;color:var(--amber);">{{ $totalLate }}</div>
            <div style="font-size:13px;color:rgba(255,255,255,0.7);margin-top:6px;letter-spacing:.03em;">Late</div>
        </div>
        <div style="flex:1 1 170px;min-width:170px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:20px;text-align:center;">
            <div style="font-size:26px;font-weight:700;color:var(--red);">{{ $totalAbsent }}</div>
            <div style="font-size:13px;color:rgba(255,255,255,0.7);margin-top:6px;letter-spacing:.03em;">Absent</div>
        </div>
        <div style="flex:1 1 170px;min-width:170px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:20px;text-align:center;">
            <div style="font-size:26px;font-weight:700;color:var(--blue);">{{ $classes->count() }}</div>
            <div style="font-size:13px;color:rgba(255,255,255,0.7);margin-top:6px;letter-spacing:.03em;">Enrolled Classes</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1.7fr 1fr;gap:20px;align-items:start;">
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:22px;">
            <div style="font-size:18px;font-weight:700;margin-bottom:14px;color:#fff;">Your Classes & QR Codes</div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:separate;border-spacing:0 6px;">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.04);">
                            <th style="padding:12px 10px;text-align:left;color:rgba(255,255,255,0.7);">Class</th>
                            <th style="padding:12px 10px;text-align:left;color:rgba(255,255,255,0.7);">Professor</th>
                            <th style="padding:12px 10px;text-align:center;color:rgba(255,255,255,0.7);">QR Code</th>
                            <th style="padding:12px 10px;text-align:center;color:rgba(255,255,255,0.7);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:14px;">
                                <td style="padding:12px 10px;min-width:150px;">
                                    <div style="font-size:13px;font-weight:700;color:#fff;">{{ $class->name }}</div>
                                    <div style="font-size:11px;color:rgba(255,255,255,0.6);">{{ $class->code }}</div>
                                </td>
                                <td style="font-size:12px;padding:12px 10px;min-width:120px;color:rgba(255,255,255,0.75);">{{ $class->professor->name ?? 'N/A' }}</td>
                                <td style="text-align:center;padding:12px 10px;">
                                    <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:#fff;border-radius:14px;border:1px solid rgba(0,0,0,0.08);">
                                        <canvas class="student-qr-canvas" width="48" height="48" data-qr="{{ base64_encode(json_encode([ 'type' => 'student_attendance', 'student_id' => auth()->id(), 'student_name' => auth()->user()->name, 'student_email' => auth()->user()->email, 'class_id' => $class->id, 'class_name' => $class->name, 'class_code' => $class->code, 'generated_at' => now()->toIso8601String() ])) }}" style="width:48px;height:48px;border-radius:10px;background:#fff;"></canvas>
                                    </div>
                                </td>
                                <td style="text-align:center;padding:12px 10px;">
                                    <button type="button" class="btn btn-p" style="padding:8px 14px;font-size:12px;" data-class-name="{{ $class->code }} - {{ $class->name }}" onclick="showStudentQR(this)">
                                        📱 Show QR
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center;color:rgba(255,255,255,0.65);padding:24px;font-size:13px;">You are not enrolled in any classes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:22px;">
            <div style="font-size:18px;font-weight:700;margin-bottom:14px;color:#fff;">Recent Attendance</div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @forelse($recentAttendance as $record)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:14px;border-radius:14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#fff;">{{ $record->classe->name ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:rgba(255,255,255,0.55);">{{ $record->recorded_at?->format('M d, Y h:i A') }}</div>
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
                    <div style="text-align:center;color:rgba(255,255,255,0.65);padding:24px;font-size:13px;border-radius:14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);">
                        No attendance records yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal for Student -->
<div id="student-qr-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:24px;border-radius:12px;max-width:350px;width:90%;text-align:center;">
        <div style="font-size:16px;font-weight:600;margin-bottom:4px;">Student Attendance QR</div>
        <div id="modal-class-name" style="font-size:11px;color:var(--text2);margin-bottom:16px;"></div>
        <canvas id="modal-student-qr-canvas" width="220" height="220" style="width:220px;height:220px;border:1px solid var(--border);border-radius:8px;margin-bottom:12px;background:white;"></canvas>
        <div id="modal-student-qr-uuid" style="font-size:10px;color:var(--text3);margin-bottom:12px;font-family:monospace;word-break:break-all;"></div>
        <div style="font-size:10px;color:var(--text2);margin-bottom:16px;">Show this QR code to your professor for attendance scanning</div>
        <div style="display:flex;gap:8px;justify-content:center;">
            <button type="button" class="btn btn-p" onclick="downloadStudentQR()">Download</button>
            <button type="button" class="btn" onclick="closeStudentQRModal()">Close</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script>
let currentStudentQRData = null;

function renderStudentQRCodes() {
    document.querySelectorAll('.student-qr-canvas').forEach(canvas => {
        const encoded = canvas.dataset.qr;
        if (!encoded || canvas.dataset.rendered === '1') {
            return;
        }
        try {
            const json = atob(encoded);
            QRCode.toCanvas(canvas, json, {
                width: 48,
                margin: 1,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            });
            canvas.dataset.rendered = '1';
        } catch (err) {
            console.error('Failed to render QR:', err);
        }
    });
}

function showStudentQR(button) {
    const row = button.closest('tr');
    const canvas = row.querySelector('.student-qr-canvas');
    const encoded = canvas?.dataset.qr;
    if (!encoded) {
        return;
    }

    const qrJson = atob(encoded);
    currentStudentQRData = qrJson;

    const className = button.dataset.className || '';
    document.getElementById('modal-class-name').textContent = className;
    document.getElementById('student-qr-modal').style.display = 'flex';

    const modalCanvas = document.getElementById('modal-student-qr-canvas');
    QRCode.toCanvas(modalCanvas, qrJson, {
        width: 220,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#ffffff'
        }
    });

    try {
        const parsed = JSON.parse(qrJson);
        document.getElementById('modal-student-qr-uuid').textContent = parsed.class_code + ' • ' + parsed.student_name;
    } catch (e) {
        document.getElementById('modal-student-qr-uuid').textContent = 'Student QR';
    }
}

function closeStudentQRModal() {
    document.getElementById('student-qr-modal').style.display = 'none';
}

function downloadStudentQR() {
    if (!currentStudentQRData) {
        return;
    }
    const canvas = document.getElementById('modal-student-qr-canvas');
    const link = document.createElement('a');
    link.download = 'student-qr.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

// Close modal on outside click
document.getElementById('student-qr-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStudentQRModal();
    }
});

window.addEventListener('load', renderStudentQRCodes);

@endsection
