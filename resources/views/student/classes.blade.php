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
                            <img src="{{ route('student.qr-code', $class->id) }}" alt="QR" style="width:48px;height:48px;border-radius:4px;border:1px solid var(--border);background:white;">
                        </td>
                        <td style="text-align:center;padding:10px 8px;">
                            <button type="button" class="btn btn-p" style="padding:7px 14px;font-size:11px;" onclick="showStudentQR('{{ $class->id }}', '{{ $class->code }} - {{ $class->name }}')">
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
        <img id="studentQRImage" src="" alt="QR Code" style="width:200px;height:200px;margin:20px auto;display:block;">
        <button class="btn btn-p" onclick="downloadStudentQR()">Download QR</button>
    </div>
</div>

<script>
function showStudentQR(classId, className) {
    document.getElementById('studentQRClass').innerText = className;
    document.getElementById('studentQRImage').src = `/student/qr-code/${classId}`;
    document.getElementById('studentQRModal').style.display = 'block';
}
function closeStudentQR() {
    document.getElementById('studentQRModal').style.display = 'none';
}
function downloadStudentQR() {
    const img = document.getElementById('studentQRImage');
    const url = img.src;
    const a = document.createElement('a');
    a.href = url;
    a.download = 'qr-code.png';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
</script>
@endsection
