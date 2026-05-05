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
            <div style="display:flex;flex-wrap:wrap;gap:20px;margin-bottom:20px;">
                <div style="flex:1 1 320px;min-width:280px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:20px;text-align:center;">
                    <div style="font-size:18px;font-weight:700;margin-bottom:12px;color:#fff;">Your Student QR Code</div>
                    <div style="background:#fff;border-radius:18px;display:inline-flex;padding:16px;">
                        <img id="student-qr-img" src="{{ route('student.qr-code') }}" alt="Student QR Code" style="width:220px;height:220px;border-radius:18px;background:#fff;object-fit:contain;" />
                    </div>
                    <div style="display:flex;gap:10px;justify-content:center;margin-top:16px;flex-wrap:wrap;">
                        <button type="button" class="btn btn-p" style="padding:10px 16px;font-size:12px;" onclick="showStudentQR()">Show QR</button>
                        <a href="{{ route('student.qr-code') }}" download="student-qr.svg" class="btn" style="padding:10px 16px;font-size:12px;">Download</a>
                    </div>
                </div>
            </div>

            <div style="font-size:18px;font-weight:700;margin-bottom:14px;color:#fff;">Your Classes</div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:separate;border-spacing:0 6px;">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.04);">
                            <th style="padding:12px 10px;text-align:left;color:rgba(255,255,255,0.7);">Class</th>
                            <th style="padding:12px 10px;text-align:left;color:rgba(255,255,255,0.7);">Professor</th>
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
                                    <button type="button" class="btn btn-p" style="padding:8px 14px;font-size:12px;" onclick="showStudentQR()">
                                        📱 View QR
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center;color:rgba(255,255,255,0.65);padding:24px;font-size:13px;">You are not enrolled in any classes.</td></tr>
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
        <img id="modal-student-qr-image" src="{{ route('student.qr-code') }}" alt="Student QR" style="width:220px;height:220px;border:1px solid var(--border);border-radius:8px;margin-bottom:12px;background:white;object-fit:contain;" />
        <div id="modal-student-qr-uuid" style="font-size:10px;color:var(--text3);margin-bottom:12px;font-family:monospace;word-break:break-all;"></div>
        <div style="font-size:10px;color:var(--text2);margin-bottom:16px;">Show this QR code to your professor for attendance scanning</div>
        <div style="display:flex;gap:8px;justify-content:center;">
            <button type="button" class="btn btn-p" onclick="downloadStudentQR()">Download</button>
            <button type="button" class="btn" onclick="closeStudentQRModal()">Close</button>
        </div>
    </div>
</div>

<script>
function showStudentQR() {
    document.getElementById('modal-class-name').textContent = 'Student QR Code';
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
</script>
@endsection
