@extends('layouts.app')

@section('title', 'My Classes')
@section('header', 'My Classes')
@section('subheader', 'View your enrolled classes and personal QR codes.')

@section('content')

<div class="card" style="padding:0 0 12px 0;">
    <div class="sh" style="font-size:18px;font-weight:600;margin-bottom:10px;">Your Personal Student QR Code</div>
    <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between;padding:10px 0 16px 0;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:100px;height:100px;background:white;border-radius:18px;display:flex;align-items:center;justify-content:center;">
                <img id="student-qr-img" src="{{ route('student.qr-code') }}" alt="Student QR Code" style="width:88px;height:88px;border-radius:18px;background:#fff;object-fit:contain;" />
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;">{{ auth()->user()->name }}</div>
                <div style="font-size:12px;color:var(--text2);">Student ID: {{ auth()->id() }}</div>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="button" class="btn btn-p" style="padding:8px 14px;font-size:12px;" onclick="showStudentQR()">View QR</button>
            <a href="{{ route('student.qr-code') }}" download="student-qr.svg" class="btn" style="padding:8px 14px;font-size:12px;">Download</a>
        </div>
    </div>
    <div class="tbl-wrap" style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0 4px;">
            <thead>
                <tr style="background:var(--bg2);">
                    <th style="padding:10px 8px;text-align:left;">Class</th>
                    <th style="padding:10px 8px;text-align:left;">Professor</th>
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
                            <button type="button" class="btn btn-p" style="padding:7px 14px;font-size:11px;" onclick="showStudentQR()">
                                📱 View QR
                            </button>
                            <button type="button" class="btn btn-s" style="padding:7px 14px;font-size:11px;margin-left:4px;" onclick="showClassStudents({{ $class->id }}, '{{ $class->name }}')">
                                👥 View Students
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text2);padding:20px;">You are not enrolled in any classes.</td></tr>
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
        <img id="studentQRModalImage" src="{{ route('student.qr-code') }}" alt="Student QR" style="width:200px;height:200px;margin:20px auto;display:block;border:1px solid var(--border);border-radius:8px;background:white;object-fit:contain;" />
        <a href="{{ route('student.qr-code') }}" download="student-qr.svg" class="btn btn-p">Download QR</a>
    </div>
</div>

<script>
function showStudentQR() {
    document.getElementById('studentQRClass').innerText = 'Student QR Code';
    document.getElementById('studentQRModal').style.display = 'block';
}

function closeStudentQR() {
    document.getElementById('studentQRModal').style.display = 'none';
}

const studentQRModal = document.getElementById('studentQRModal');
if (studentQRModal) {
    studentQRModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeStudentQR();
        }
    });
}
</script>
@endsection
