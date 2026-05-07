@extends('layouts.professor')

@section('title', 'Dashboard - Student')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
<style>
@keyframes qrScan {
    0% { 
        top: -4px;
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% { 
        top: 100%;
        opacity: 0;
    }
}
</style>

<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.classes') }}">
        <div class="stat-val">{{ $classes->count() }}</div>
        <div class="stat-label">Enrolled Classes</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View classes -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalPresent }}</div>
        <div class="stat-label">Present</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View attendance -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalLate }}</div>
        <div class="stat-label">Late</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View attendance -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="{{ route('student.attendance') }}">
        <div class="stat-val">{{ $totalAbsent }}</div>
        <div class="stat-label">Absent</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View attendance -></div>
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:16px;align-items:start;">
    <div style="display:grid;gap:16px;">
        <div>
            <div class="sh">Your Classes</div>
            <div class="card">
                @forelse($classes as $class)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border2);gap:12px;">
                        <div>
                            <div style="font-size:12px;font-weight:600;">{{ $class->display_name }}</div>
                            <div style="font-size:10px;color:var(--text2);">{{ $class->code }}</div>
                        </div>
                        <div style="text-align:right;font-size:11px;color:var(--text2);">{{ $class->professor->name ?? 'N/A' }}</div>
                    </div>
                @empty
                    <div style="color:var(--text2);font-size:11px;">Not enrolled in any classes.</div>
                @endforelse
                <a class="btn btn-sm" href="{{ route('student.classes') }}" style="width:100%;justify-content:center;margin-top:8px;">View All Classes -></a>
            </div>
        </div>

        <div>
            <div class="sh">Recent Attendance</div>
            <div class="card">
                @forelse($recentAttendance as $record)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border2);gap:12px;">
                        <div style="flex:1;">
                            <div style="font-size:11px;font-weight:600;">{{ $record->classe->name ?? 'N/A' }}</div>
                            <div style="font-size:9px;color:var(--text3);">{{ $record->recorded_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</div>
                        </div>
                        @if($record->status === 'present')
                            <span class="badge bg" style="font-size:10px;">Present</span>
                        @elseif($record->status === 'late')
                            <span class="badge" style="background:var(--amber);color:#000;font-size:10px;">Late</span>
                        @else
                            <span class="badge br" style="font-size:10px;">Absent</span>
                        @endif
                    </div>
                @empty
                    <div style="text-align:center;color:var(--text2);padding:12px;font-size:11px;">
                        No attendance records yet.
                    </div>
                @endforelse
                <a class="btn btn-sm" href="{{ route('student.attendance') }}" style="width:100%;justify-content:center;margin-top:8px;">View All Records -></a>
            </div>
        </div>

        <div>
            <div class="sh">Quick Actions</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                <a class="btn" href="{{ route('student.classes') }}" style="justify-content:center;">My Classes</a>
                <a class="btn" href="{{ route('student.attendance') }}" style="justify-content:center;">Attendance</a>
            </div>
        </div>
    </div>

    <div>
        <div class="sh">Your QR Code</div>
        <div class="card" style="text-align:center;position:relative;border:1.5px solid rgba(108,92,231,0.4);background:rgba(9,8,24,0.65);overflow:hidden;">
            <div style="position:absolute;top:12px;left:12px;width:16px;height:16px;border-top:2px solid #a29bfe;border-left:2px solid #a29bfe;"></div>
            <div style="position:absolute;top:12px;right:12px;width:16px;height:16px;border-top:2px solid #a29bfe;border-right:2px solid #a29bfe;"></div>
            <div style="position:absolute;bottom:12px;left:12px;width:16px;height:16px;border-bottom:2px solid #a29bfe;border-left:2px solid #a29bfe;"></div>
            <div style="position:absolute;bottom:12px;right:12px;width:16px;height:16px;border-bottom:2px solid #a29bfe;border-right:2px solid #a29bfe;"></div>
            
            <div style="position:relative;background:#fff;border-radius:6px;display:inline-flex;padding:16px;margin:20px auto 16px;width:fit-content;box-shadow:0 0 20px rgba(108,92,231,0.35);overflow:hidden;">
                <img id="student-qr-img" src="{{ $studentQrDataUri }}" alt="Student QR Code" style="width:160px;height:160px;border-radius:4px;background:#fff;object-fit:contain;" />
                <div style="position:absolute;left:0;right:0;top:0;height:8px;background:linear-gradient(180deg,rgba(108,92,231,0),rgba(108,92,231,1),rgba(108,92,231,0));animation:qrScan 2.5s infinite;box-shadow:0 0 12px rgba(108,92,231,0.9),0 0 24px rgba(108,92,231,0.5);"></div>
            </div>
            
            <div style="font-size:10px;color:var(--text2);margin:0 12px 12px;letter-spacing:1px;text-transform:uppercase;">Show to Professors</div>
            <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;padding:0 12px 12px;">
                <button type="button" class="btn btn-sm btn-p" onclick="showStudentQR()" style="flex:1;min-width:100px;display:flex;justify-content:center;align-items:center;text-align:center;">Show QR</button>
                <button type="button" class="btn btn-sm" onclick="downloadStudentQrAsPng('student-qr.png')" style="flex:1;min-width:100px;display:flex;justify-content:center;align-items:center;text-align:center;">Download</button>
            </div>
            <div style="font-size:9px;color:#a29bfe;padding:8px;letter-spacing:2px;text-transform:uppercase;">● SYSTEM ONLINE</div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="student-qr-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.75);z-index:10000;align-items:center;justify-content:center;padding:16px;">
    <div style="position:relative;width:100%;max-width:420px;max-height:90vh;background:var(--navy2);border-radius:18px;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,0.45);padding:24px;text-align:center;">
        <button onclick="closeStudentQRModal()" class="btn btn-sm btn-d" style="position:absolute;top:16px;right:16px;z-index:10;">Close</button>
        <h3 style="margin-bottom:8px;">Your Student QR Code</h3>
        <div style="font-size:12px;color:var(--text2);margin-bottom:16px;">Show this to professors for attendance</div>
        <div style="position:relative;background:#fff;border-radius:var(--radius);display:inline-flex;padding:12px;margin-bottom:12px;box-shadow:0 0 25px rgba(108,92,231,0.35);overflow:hidden;">
            <img id="modal-student-qr-image" src="{{ $studentQrDataUri }}" alt="Student QR" style="width:220px;height:220px;border-radius:6px;background:#fff;object-fit:contain;" />
        </div>
        <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
            <button type="button" class="btn btn-p" onclick="downloadStudentQrAsPng('student-qr.png')" style="flex:1;min-width:100px;display:flex;justify-content:center;align-items:center;text-align:center;">Download</button>
        </div>
    </div>
</div>

<script>
function showStudentQR() {
    document.getElementById('student-qr-modal').style.display = 'flex';
}
function closeStudentQRModal() {
    document.getElementById('student-qr-modal').style.display = 'none';
}

const studentQRModal = document.getElementById('student-qr-modal');
if (studentQRModal) {
    studentQRModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeStudentQRModal();
        }
    });
}

const studentQrDataUri = '{{ $studentQrDataUri }}';

function downloadStudentQrAsPng(filename = 'student-qr.png') {
    const svgText = decodeURIComponent(studentQrDataUri.replace(/^data:image\/svg\+xml;charset=utf-8,/, ''));
    const svgBlob = new Blob([svgText], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(svgBlob);
    const image = new Image();

    image.onload = function() {
        const canvas = document.createElement('canvas');
        canvas.width = image.width;
        canvas.height = image.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(image, 0, 0);
        canvas.toBlob(function(blob) {
            if (!blob) {
                alert('Unable to generate PNG file.');
                return;
            }
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }, 'image/png');
        URL.revokeObjectURL(url);
    };

    image.onerror = function() {
        alert('Unable to load QR code for download.');
        URL.revokeObjectURL(url);
    };

    image.src = studentQrDataUri;
}
</script>
@endsection
