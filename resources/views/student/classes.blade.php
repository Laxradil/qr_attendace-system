@extends('layouts.app')

@section('title', 'My Classes')
@section('header', 'My Classes')
@section('subheader', 'View your enrolled classes and personal QR codes.')

@section('content')

<div class="card" style="padding:0 0 12px 0;">
    <div class="sh" style="font-size:18px;font-weight:600;margin-bottom:10px;">Your Classes & Personal QR Codes</div>
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
                                    <canvas class="student-qr-canvas" width="48" height="48" data-qr="{{ base64_encode(json_encode([ 'type' => 'student_attendance', 'student_id' => auth()->id(), 'student_name' => auth()->user()->name, 'student_email' => auth()->user()->email, 'class_id' => $class->id, 'class_name' => $class->name, 'class_code' => $class->code, 'generated_at' => now()->toIso8601String() ])) }}" style="width:48px;height:48px;border-radius:4px;border:1px solid var(--border);background:white;"></canvas>
                                </td>
                        <td style="text-align:center;padding:10px 8px;">
                            <button type="button" class="btn btn-p" style="padding:7px 14px;font-size:11px;" data-class-name="{{ $class->code }} - {{ $class->name }}" onclick="showStudentQR(this)">
                                📱 Show QR
                            </button>
                            <button type="button" class="btn btn-s" style="padding:7px 14px;font-size:11px;margin-left:4px;" onclick="showClassStudents({{ $class->id }}, '{{ $class->name }}')">
                                👥 View Students
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

<!-- Modal for QR code display -->
<div id="studentQRModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:350px;">
        <span class="close" onclick="closeStudentQR()">&times;</span>
        <h3 id="studentQRClass"></h3>
        <canvas id="studentQRModalCanvas" width="200" height="200" style="width:200px;height:200px;margin:20px auto;display:block;border:1px solid var(--border);border-radius:8px;background:white;"></canvas>
        <button class="btn btn-p" onclick="downloadStudentQR()">Download QR</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script>
let currentStudentQRData = null;

function renderStudentQRCodes() {
    document.querySelectorAll('.student-qr-canvas').forEach(canvas => {
        if (canvas.dataset.rendered === '1') {
            return;
        }
        const encoded = canvas.dataset.qr;
        if (!encoded) {
            return;
        }
        try {
            const text = atob(encoded);
            QRCode.toCanvas(canvas, text, {
                width: 48,
                margin: 1,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            });
            canvas.dataset.rendered = '1';
        } catch (err) {
            console.error('Failed to render student QR', err);
        }
    });
}

function showStudentQR(button) {
    const row = button.closest('tr');
    const canvas = row.querySelector('.student-qr-canvas');
    if (!canvas) {
        return;
    }

    const encoded = canvas.dataset.qr;
    if (!encoded) {
        return;
    }

    currentStudentQRData = atob(encoded);
    const className = button.dataset.className || '';
    document.getElementById('studentQRClass').innerText = className;
    document.getElementById('studentQRModal').style.display = 'block';

    const modalCanvas = document.getElementById('studentQRModalCanvas');
    QRCode.toCanvas(modalCanvas, currentStudentQRData, {
        width: 200,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#ffffff'
        }
    });
}

function closeStudentQR() {
    document.getElementById('studentQRModal').style.display = 'none';
}

function downloadStudentQR() {
    if (!currentStudentQRData) {
        return;
    }
    const canvas = document.getElementById('studentQRModalCanvas');
    const link = document.createElement('a');
    link.download = 'student-qr.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

document.addEventListener('DOMContentLoaded', renderStudentQRCodes);
</script>
@endsection
