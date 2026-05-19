@extends('layouts.student')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
@php
  $attendanceRate = $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0;
@endphp

<section class="page" id="dashboard"
  data-student-id="{{ Auth::user()->id }}"
  data-student-name="{{ Auth::user()->name }}"
  data-student-email="{{ Auth::user()->email }}">

  {{-- Outer scroll wrapper --}}
  <div style="width:100%; overflow-y:auto; padding: 0 16px 48px; box-sizing:border-box;">
  <div style="max-width:1200px; margin:0 auto;">

    {{-- ── STAT CARDS ── --}}
    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px; margin-bottom:20px;">

      <div class="stat glass w-full p-6">
        <div class="stat-icon blue">▤</div>
        <div class="stat-body">
          <strong>{{ $classes->count() }}</strong>
          <span class="stat-label">Enrolled Classes</span>
        </div>
      </div>

      <div class="stat glass w-full p-6">
        <div class="stat-icon green">✓</div>
        <div class="stat-body">
          <strong>{{ $totalPresent }}</strong>
          <span class="stat-label">Present</span>
        </div>
      </div>

      <div class="stat glass w-full p-6">
        <div class="stat-icon yellow">◷</div>
        <div class="stat-body">
          <strong>{{ $totalLate }}</strong>
          <span class="stat-label">Late</span>
        </div>
      </div>

      <div class="stat glass w-full p-6">
        <div class="stat-icon red">✕</div>
        <div class="stat-body">
          <strong>{{ $totalAbsent }}</strong>
          <span class="stat-label">Absent</span>
        </div>
      </div>

      <div class="stat glass w-full p-6">
        <div class="stat-icon purple">✉</div>
        <div class="stat-body">
          <strong>{{ $totalExcused ?? 0 }}</strong>
          <span class="stat-label">Excused</span>
        </div>
      </div>

    </div>
    {{-- end stat cards --}}

    {{-- ── MAIN GRID: QR left | Right column ── --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

      {{-- ── LEFT: QR CARD ── --}}
      <div class="card glass" style="padding:30px; display:flex; flex-direction:column; align-items:center; text-align:center; color:var(--text); box-sizing:border-box;">

        <div style="font-size:18px; font-weight:700; margin-bottom:24px; text-transform:uppercase; letter-spacing:.05em;">
          Your QR Code
        </div>

        {{-- QR canvas frame --}}
        <div style="padding:15px; margin-bottom:20px; background:#fff; border-radius:12px; width:100%; max-width:280px; box-shadow:0 4px 15px rgba(0,0,0,0.08); box-sizing:border-box;">
          <canvas id="qrDashboard" style="width:100%; height:auto; display:block;"></canvas>
        </div>

        <div style="font-size:22px; font-weight:800; margin-bottom:6px;">{{ Auth::user()->name }}</div>
        <div style="font-size:14px; color:var(--muted); font-family:var(--mono); margin-bottom:10px;">Student ID: {{ Auth::user()->id }}</div>
        <div style="font-size:14.5px; font-weight:600; color:var(--text); margin-bottom:28px;">
          Show to professor for attendance
        </div>

        {{-- Action buttons --}}
        <div style="display:flex; gap:12px; width:100%; margin-bottom:20px;">
          <button
            class="btn btn-pill primary"
            onclick="openQRModal()"
            style="flex:1; padding:12px 20px; font-size:14px; font-weight:700;
                   background:linear-gradient(135deg,#7c3aed 80%,#2563eb 100%);
                   color:#fff; border:none; cursor:pointer;">
            Show QR
          </button>
          <button
            class="btn btn-pill"
            onclick="downloadQR()"
            style="flex:1; padding:12px 20px; font-size:14px; font-weight:700;
                   background:#ede9fe; color:#5b21b6;
                   border:1.5px solid #7c3aed; cursor:pointer;
                   transition:background .2s, color .2s;">
            Download
          </button>
        </div>

        {{-- Status dot --}}
        <div style="font-size:13px; color:var(--muted); font-weight:600; display:flex; align-items:center; gap:6px;">
          <span style="width:8px; height:8px; border-radius:50%; background:var(--green);
                       box-shadow:0 0 8px rgba(24,240,139,.8); display:inline-block;"></span>
          System Online
        </div>

      </div>
      {{-- end QR card --}}

      {{-- ── RIGHT: Recent Attendance + Classes ── --}}
      <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Recent Attendance --}}
        <div class="card glass" style="padding:20px; display:flex; flex-direction:column; box-sizing:border-box;">

          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:16px; margin:0;">📋 Recent Attendance</h3>
            <a href="{{ route('student.attendance') }}" style="font-size:13px; color:#fff; font-weight:600; text-decoration:none;">View all</a>
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <span style="font-size:12px; color:var(--muted); font-weight:600;">Attendance Rate</span>
            <span style="font-size:14px; font-weight:800; color:#4dffa0;">{{ $attendanceRate }}%</span>
          </div>
          <div class="prog-bar" style="height:6px; margin-bottom:16px;">
            <div class="prog-fill" id="attendanceRateFill" data-rate="{{ $attendanceRate }}" style="width:0%;"></div>
          </div>

          <div>
            @forelse($recentAttendance->take(3) as $record)
            <div style="padding:12px 16px; margin-bottom:8px; display:flex; align-items:center;
                        background:rgba(255,255,255,0.04); border-radius:10px;">
              <div style="flex-grow:1;">
                <div style="font-size:14px; font-weight:600; margin-bottom:2px;">
                  {{ $record->classe->code }} — {{ $record->classe->name }}
                </div>
                <div style="font-size:12px; color:var(--muted);">
                  {{ $record->recorded_at->format('M d, Y') }}
                </div>
              </div>
              <span style="font-size:13px; font-weight:700; color:#fff; margin-right:16px;">
                {{ $record->recorded_at->format('h:i A') }}
              </span>
              <span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : ($record->status === 'absent' ? 'red' : 'purple')) }}"
                    style="font-size:11px; padding:4px 10px;">
                {{ ucfirst($record->status) }}
              </span>
            </div>
            @empty
            <div style="padding:15px 0; font-size:13px; color:var(--muted);">No records yet.</div>
            @endforelse
          </div>

          <a href="{{ route('student.attendance') }}"
             class="btn btn-pill"
             style="width:100%; margin-top:16px; justify-content:center; text-decoration:none;
                    display:flex; padding:10px; font-size:13px;
                    background:#ede9fe; color:#5b21b6;
                    border:1.5px solid #7c3aed; font-weight:700;
                    transition:background .2s, color .2s; box-sizing:border-box;">
            View All Records →
          </a>

        </div>
        {{-- end Recent Attendance --}}

        {{-- Your Classes --}}
        <div class="card glass" style="padding:20px; display:flex; flex-direction:column; box-sizing:border-box;">

          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:16px; margin:0;">📚 Your Classes</h3>
            <a href="{{ route('student.classes') }}" style="font-size:13px; color:#fff; font-weight:600; text-decoration:none;">View all</a>
          </div>

          @php $classList = collect($classes); @endphp
          @forelse($classList->take(3) as $class)
          <div style="padding:10px 14px; margin-bottom:6px; display:flex; justify-content:space-between;
                      align-items:center; background:rgba(255,255,255,0.04); border-radius:8px;">
            <div style="flex-grow:1; padding-right:12px;">
              <div style="font-size:13.5px; font-weight:600; margin-bottom:2px;">
                {{ $class->code }} — {{ $class->name }}
              </div>
              <div style="font-size:11px; color:var(--muted);">{{ $class->code }}</div>
              @if($class->schedules->first())
              <div style="font-size:11px; color:var(--muted); margin-top:4px; line-height:1.35;">
                {{ $class->schedules->first()->days }} · {{ $class->schedules->first()->time }} · Room {{ $class->schedules->first()->room }}
              </div>
              @endif
            </div>
            <div style="font-size:11px; text-align:right; min-width:100px;">
              @if($class->professors->first())
              <strong style="display:block; color:#fff; margin-bottom:2px;">{{ $class->professors->first()->name }}</strong>
              <span style="color:var(--muted);">Professor</span>
              @endif
            </div>
          </div>
          @empty
          <div style="padding:10px 0; font-size:12px; color:var(--muted);">No classes enrolled</div>
          @endforelse

          <a href="{{ route('student.classes') }}"
             class="btn btn-pill"
             style="width:100%; margin-top:16px; justify-content:center; text-decoration:none;
                    padding:10px; font-size:13px;
                    background:#ede9fe; color:#5b21b6;
                    border:1.5px solid #7c3aed; font-weight:700;
                    transition:background .2s, color .2s; display:flex; box-sizing:border-box;">
            View All Classes
          </a>

        </div>
        {{-- end Your Classes --}}

      </div>
      {{-- end right column --}}

    </div>
    {{-- end main grid --}}

  </div>
  </div>
  {{-- end outer scroll wrapper --}}

</section>

{{-- ── QR MODAL ── --}}
<div class="qr-modal" id="qrModal">
  <div class="qr-modal-overlay" onclick="closeQRModal()"></div>
  <div class="qr-modal-content glass">
    <button class="qr-modal-close" onclick="closeQRModal()">✕</button>
    <div class="qr-modal-body">
      <div style="text-align:center;">
        <div style="font-size:16px; font-weight:700; margin-bottom:16px; color:var(--text);">Your QR Code</div>
        <div class="qr-modal-frame">
          <canvas id="qrModalCanvas"></canvas>
        </div>
        <div style="margin-top:16px;">
          <div style="font-size:16px; font-weight:800; color:var(--text);">{{ Auth::user()->name }}</div>
          <div style="font-size:13px; color:var(--muted); font-family:var(--mono); margin-top:4px;">Student ID: {{ Auth::user()->id }}</div>
          <div style="font-size:12px; color:var(--faint); margin-top:8px;">Show to professor for attendance</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function downloadQR() {
    const canvas = document.getElementById('qrDashboard');
    if (!canvas) return;
    const link = document.createElement('a');
    link.download = 'student-qr.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
  }

  function openQRModal() {
    document.getElementById('qrModal').classList.add('active');
  }

  function closeQRModal() {
    document.getElementById('qrModal').classList.remove('active');
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('qrModal').classList.contains('active')) {
      closeQRModal();
    }
  });

  setTimeout(function() {
    const dashboard = document.getElementById('dashboard');
    if (!dashboard) return;

    const qrPayload = {
      type: 'student_attendance',
      student_id: Number(dashboard.dataset.studentId),
      student_name: dashboard.dataset.studentName || '',
      email: dashboard.dataset.studentEmail || ''
    };
    const qrData = JSON.stringify(qrPayload);

    // Animate progress bar
    const rateFill = document.getElementById('attendanceRateFill');
    if (rateFill) {
      const rate = Number(rateFill.dataset.rate || 0);
      rateFill.style.width = rate + '%';
    }

    generateQR('qrDashboard', qrData);
    generateQR('qrModalCanvas', qrData);
  }, 100);
</script>
@endsection