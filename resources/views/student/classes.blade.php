@extends('layouts.professor')

@section('title', 'My Classes - Student')
@section('header', 'My Classes')
@section('subheader', 'View your enrolled classes and manage your QR code.')

@section('content')
<div class="g-6-4">
    <div>
        <div class="sh">Your Enrolled Classes</div>
        <div class="card">
            @forelse($classes as $class)
                <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--border2);gap:12px;\">
                    <div style="flex:1;\">
                        <div style="font-size:12px;font-weight:600;">{{ $class->display_name }}</div>
                    </div>
                    <div style="text-align:right;min-width:120px;\">
                        <div style="font-size:11px;font-weight:600;\">{{ $class->professor->name ?? 'N/A' }}</div>
                        <div style="font-size:10px;color:var(--text2);\">Professor</div>
                    </div>
                </div>
            @empty
                <div style="text-align:center;color:var(--text2);\">You are not enrolled in any classes yet.</div>
            @endforelse
            @if($classes->count())
                <a class="btn btn-sm" href="{{ route('student.dashboard') }}" style="width:100%;justify-content:center;margin-top:8px;\">Back to Dashboard -></a>
            @endif
        </div>
    </div>

    <div>
        <div class="sh">Your QR Code</div>
        <div class="card" style="text-align:center;\">
            <div style="background:#fff;border-radius:var(--radius);display:inline-flex;padding:12px;margin-bottom:12px;\">
                <img id="student-qr-img" src="{{ route('student.qr-code') }}" alt="Student QR Code" style="width:140px;height:140px;border-radius:6px;background:#fff;object-fit:contain;\" />
            </div>
            <div style="font-size:11px;font-weight:600;\">{{ auth()->user()->name }}</div>
            <div style="font-size:10px;color:var(--text2);\">Student ID: {{ auth()->id() }}</div>
            <div style="font-size:10px;color:var(--text3);\">Show to professor for attendance</div>
            <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-top:12px;\">
                <button type="button" class="btn btn-sm btn-p" onclick="showStudentQR()" style="flex:1;min-width:80px;\">Show</button>
                <a href="{{ route('student.qr-code') }}" download="student-qr.svg" class="btn btn-sm" style="flex:1;min-width:80px;justify-content:center;\">Download</a>
            </div>
        </div>

        <div class="sh">Quick Stats</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;\">
            <div style="background:var(--navy3);\;border-radius:var(--radius);\;padding:10px;text-align:center;\">
                <div style="font-size:18px;font-weight:700;color:var(--green);\">@php
                    $present = $classes->count() ? 0 : 0;
                @endphp
                </div>
                <div style="font-size:10px;color:var(--text2);\">Present</div>
            </div>
            <div style="background:var(--navy3);border-radius:var(--radius);\;padding:10px;text-align:center;\">
                <div style="font-size:18px;font-weight:700;color:var(--amber);\">0</div>
                <div style="font-size:10px;color:var(--text2);\">Late</div>
            </div>
            <div style="background:var(--navy3);border-radius:var(--radius);\;padding:10px;text-align:center;\">
                <div style="font-size:18px;font-weight:700;color:var(--red);\">0</div>
                <div style="font-size:10px;color:var(--text2);\">Absent</div>
            </div>
            <div style="background:var(--navy3);border-radius:var(--radius);\;padding:10px;text-align:center;\">
                <div style="font-size:18px;font-weight:700;color:var(--blue);\">{{ $classes->count() }}</div>
                <div style="font-size:10px;color:var(--text2);\">Enrolled</div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="student-qr-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.75);z-index:10000;align-items:center;justify-content:center;padding:16px;\">
    <div style="position:relative;width:100%;max-width:400px;max-height:90vh;background:var(--navy2);border-radius:18px;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,0.45);padding:24px;text-align:center;\">
        <button onclick="closeStudentQRModal()" class="btn btn-sm btn-d" style="position:absolute;top:16px;right:16px;z-index:10;\">Close</button>
        <h3 style="margin-bottom:8px;\">Your Student QR Code</h3>
        <div style="font-size:12px;color:var(--text2);\">{{ auth()->user()->name }}</div>
        <div style="font-size:10px;color:var(--text3);\">Student ID: {{ auth()->id() }}</div>
        <div style="background:#fff;border-radius:var(--radius);\;display:inline-flex;padding:12px;margin:16px 0;\">
            <img id="modal-student-qr-image" src="{{ route('student.qr-code') }}" alt="Student QR" style="width:220px;height:220px;border-radius:6px;background:#fff;object-fit:contain;\" />
        </div>
        <div style="font-size:10px;color:var(--text2);\">Show this QR code to your professor for attendance scanning</div>
        <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:12px;\">
            <a href="{{ route('student.qr-code') }}" download="student-qr.svg" class="btn btn-p" style="flex:1;min-width:100px;justify-content:center;\">Download</a>
            <button type="button" class="btn btn-sm" onclick="closeStudentQRModal()" style="flex:1;min-width:100px;justify-content:center;\">Close</button>
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
</script>
@endsection
