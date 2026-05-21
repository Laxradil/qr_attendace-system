@extends('layouts.admin')

@section('title', 'Student QR Codes')
@section('header', 'Student QR Codes')
@section('subheader', 'View and manage student attendance QR codes.')

@section('content')
<div class="tbl-wrap">
    <table>
        <thead><tr><th>Student</th><th>Email</th><th>Classes</th><th style="width:120px;">QR Code</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>
                        <div style="font-weight:500;font-size:11px;">{{ $student->name }}</div>
                    </td>
                    <td style="font-size:10px;color:var(--text2);">{{ $student->email }}</td>
                    <td style="font-size:10px;">
                        @if($student->enrolledClasses->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($student->enrolledClasses as $class)
                                    <span class="badge bp">{{ $class->code }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color:var(--text3);">No class</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle;text-align:center;width:96px;">
                        <div style="display:inline-flex;align-items:center;justify-content:center;height:100%;">
                            <img src="{{ route('admin.students.qr-code', $student) }}" alt="QR" style="width:60px;height:60px;border-radius:4px;border:1px solid var(--border);">
                        </div>
                    </td>
                    <td style="vertical-align:middle;">
                        <div style="display:inline-flex;gap:4px;align-items:center;white-space:nowrap;height:100%;">
                            <button type="button" class="btn btn-sm" onclick='viewStudentQR({{ json_encode(route('admin.students.qr-code', $student)) }}, {{ json_encode($student->name) }})'>Open</button>
                            <button type="button" class="btn btn-sm btn-p" onclick='downloadQR({{ json_encode(route('admin.students.qr-code', $student)) }}, {{ json_encode($student->name) }}, "png")'>Download PNG</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text2);">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</span><div>{{ $students->links() }}</div></div>
</div>

<!-- QR Code Modal -->
<div id="qr-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:24px;border-radius:12px;max-width:400px;width:90%;text-align:center;">
        <div style="font-size:18px;font-weight:600;margin-bottom:16px;">QR Code</div>
        <img id="modal-qr-image" src="" alt="QR Code" style="width:200px;height:200px;border:1px solid var(--border);border-radius:8px;margin-bottom:16px;">
        <div id="modal-qr-uuid" style="font-size:12px;color:var(--text2);margin-bottom:16px;font-family:monospace;"></div>
        <div style="display:flex;gap:8px;justify-content:center;">
            <button type="button" class="btn btn-p" onclick="downloadQRFromModal('png')">Download PNG</button>
            <button type="button" class="btn" onclick="closeQRModal()">Close</button>
        </div>
    </div>
</div>

<script>
let currentQRUrl = '';
let currentQRLabel = '';

function viewQR(url, label) {
    currentQRUrl = url;
    currentQRLabel = label;
    const img = document.getElementById('modal-qr-image');
    img.src = url;
    document.getElementById('modal-qr-uuid').textContent = label;
    document.getElementById('qr-modal').style.display = 'flex';
}

function viewStudentQR(url, label) {
    viewQR(url, label);
}

function closeQRModal() {
    document.getElementById('qr-modal').style.display = 'none';
}

function downloadQRFromModal(type = 'png') {
    if (!currentQRUrl) {
        alert('No QR selected');
        return;
    }
    downloadQR(currentQRUrl, currentQRLabel, type);
}

function downloadQR(url, label, type = 'png') {
    fetch(url)
        .then(response => response.text())
        .then(svgText => {
            const canvas = document.createElement('canvas');
            canvas.width = 300;
            canvas.height = 300;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, 300, 300);
            const img = new Image();
            const svgBlob = new Blob([svgText], { type: 'image/svg+xml' });
            const svgUrl = URL.createObjectURL(svgBlob);

            img.onload = function() {
                ctx.drawImage(img, 0, 0);
                URL.revokeObjectURL(svgUrl);
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    const safeLabel = label ? label.replace(/[^a-zA-Z0-9-_]/g, '_') : 'qr-code';
                    link.download = safeLabel + '-qr.' + (type === 'png' ? 'png' : 'jpg');
                    link.click();
                    URL.revokeObjectURL(url);
                }, type === 'png' ? 'image/png' : 'image/jpeg', 0.95);
            };

            img.onerror = function() {
                URL.revokeObjectURL(svgUrl);
                alert('Failed to load QR code image');
            };

            img.src = svgUrl;
        })
        .catch(error => {
            console.error('Download failed:', error);
            alert('Failed to download QR code');
        });
}

// Close modal on outside click
document.getElementById('qr-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});
</script>
@endsection
