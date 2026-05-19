@extends('layouts.student')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
<style>
  body.theme-light .qr-actions button:first-child:hover {
    background: #ffffff !important;
    color: #6d28d9 !important;
    border: 2px solid #6d28d9 !important;
  }

  body.theme-light .qr-actions button:last-child:hover {
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    text-decoration: none !important;
  }
</style>

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

      {{-- ── MAIN GRID ── --}}
      <div class="dash-grid" style="display:grid !important; grid-template-columns: 4.5fr 7.5fr !important; gap:24px !important; flex-grow:1 !important; align-items:stretch !important; width:100% !important;">

        {{-- ── LEFT: QR CARD ── --}}
        <div class="dash-col" style="display:flex !important; flex-direction:column !important;">
          <div class="card glass qr-container stretch" style="padding:35px 30px 25px 30px !important; display:flex !important; flex-direction:column !important; align-items:center !important; text-align:center !important; height:100% !important; box-sizing:border-box !important;">

            <div style="font-size:15px !important; font-weight:700 !important; margin-bottom:24px !important; letter-spacing:2px !important; text-transform:uppercase !important;">
              Your QR Code
            </div>

            <div class="qr-frame" style="padding:15px !important; margin-bottom:24px !important; background:#fff !important; border-radius:16px !important; width:100% !important; max-width:260px !important; box-shadow:0 4px 20px rgba(0,0,0,0.04) !important; display:flex !important; align-items:center !important; justify-content:center !important; box-sizing:border-box !important;">
              <canvas id="qrDashboard" style="width:100% !important; height:auto !important; max-width:230px !important; display:block;"></canvas>
            </div>

            <div class="qr-student-name" style="font-size:22px !important; font-weight:800 !important; margin-bottom:6px !important;">{{ Auth::user()->name }}</div>
            <div class="qr-student-id" style="font-size:13.5px !important; margin-bottom:12px !important; font-family:var(--mono) !important; font-weight:500 !important;">Student ID: {{ Auth::user()->id }}</div>
            <div class="qr-hint" style="font-size:14px !important; font-weight:500 !important; margin-bottom:30px !important;">
              Show to professor for attendance
            </div>

            {{-- Action buttons --}}
            <div class="qr-actions" style="display:flex !important; gap:16px !important; width:100% !important; max-width:100% !important; justify-content:center !important; margin-top:auto !important; margin-bottom:24px !important; box-sizing:border-box !important;">
              <button
                type="button"
                class="btn btn-pill primary"
                onclick="openQRModal()"
                style="flex:1 !important; width:50% !important; min-height:46px !important; padding:12px 20px !important; font-size:14px !important; font-weight:700 !important;">
                Show QR
              </button>
              <button
                type="button"
                class="btn btn-pill"
                onclick="downloadQR()"
                style="flex:1 !important; width:50% !important; min-height:46px !important; padding:12px 20px !important; font-size:14px !important; font-weight:700 !important;">
                Download
              </button>
            </div>

            {{-- Status dot --}}
            <div class="qr-status" style="font-size:11.5px !important; font-weight:700 !important; letter-spacing:1px !important; text-transform:uppercase !important; display:flex !important; align-items:center !important; justify-content:center !important; margin-top:5px !important;">
              <span style="width:8px !important; height:8px !important; border-radius:50% !important; background:#10b981 !important; box-shadow:0 0 10px rgba(16, 185, 129, 0.8) !important; display:inline-block; margin-right:8px !important;"></span>
              System Online
            </div>

          </div>
        </div>

        {{-- ── RIGHT: Recent Attendance + Classes ── --}}
        <div class="dash-col" style="display:flex; flex-direction:column; gap:24px;">

          {{-- Recent Attendance --}}
          <div class="card glass" style="padding:24px; display:flex; flex-direction:column; flex:1; box-sizing:border-box;">

            <div class="section-head" style="margin-bottom:16px;">
              <h3 style="font-size:16px; margin:0; font-weight:700;">📋 Recent Attendance</h3>
              <a href="{{ route('student.attendance') }}" style="font-size:13px; font-weight:600; text-decoration:none;">View all</a>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
              <span style="font-size:12px; font-weight:600;">Attendance Rate</span>
              <span style="font-size:14px; font-weight:800;">{{ $attendanceRate }}%</span>
            </div>
            <div class="prog-bar" style="height:6px; margin-bottom:20px; background:rgba(0,0,0,0.05); border-radius:4px;">
              <div class="prog-fill" style="width:{{ $attendanceRate }}%; height:100%; background:#10b981; border-radius:4px;"></div>
            </div>

            {{-- Scrollable Container --}}
            <div style="flex-grow:1; max-height:220px; overflow-y:auto; padding-right:10px;">
              @forelse($recentAttendance->take(5) as $record)
              <div class="att-row" style="padding:12px 16px; margin-bottom:8px; display:flex; align-items:center; border-radius:12px; box-shadow:0 2px 4px rgba(0,0,0,0.01);">
                <div style="flex-grow:1;">
                  <div class="att-class" style="font-size:14px; font-weight:600; margin-bottom:2px;">
                    {{ $record->classe->code }} — {{ $record->classe->name }}
                  </div>
                  <div class="att-date" style="font-size:12px; font-family:var(--mono);">
                    {{ $record->recorded_at->format('M d, Y') }}
                  </div>
                </div>
                <span class="att-time" style="font-size:13px; font-weight:700; margin-right:16px;">
                  {{ $record->recorded_at->format('h:i A') }}
                </span>
                <span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : ($record->status === 'absent' ? 'red' : 'purple')) }}"
                      style="font-size:11px; padding:4px 12px; border-radius:12px; font-weight:600;">
                  {{ ucfirst($record->status) }}
                </span>
              </div>
              @empty
              <div class="empty-state" style="padding:15px 0; font-size:13px;">No records yet.</div>
              @endforelse
            </div>

          </div>

          {{-- Your Classes --}}
          <div class="card glass" style="padding:24px; display:flex; flex-direction:column; flex:1; box-sizing:border-box;">

            <div class="section-head" style="margin-bottom:16px;">
              <h3 style="font-size:16px; margin:0; font-weight:700;">📚 Your Classes</h3>
              <a href="{{ route('student.classes') }}" style="font-size:13px; font-weight:600; text-decoration:none;">View all</a>
            </div>

            {{-- Scrollable Container --}}
            <div style="flex-grow:1; max-height:220px; overflow-y:auto; padding-right:10px;">
              @php $classList = collect($classes); @endphp
              @forelse($classList as $class)
              <div class="class-row" style="padding:12px 16px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center; border-radius:12px; box-shadow:0 2px 4px rgba(0,0,0,0.01);">
                <div style="flex-grow:1; padding-right:12px;">
                  <div class="class-row-name" style="font-size:14px; font-weight:600; margin-bottom:2px;">
                    {{ $class->code }} — {{ $class->name }}
                  </div>
                  <div class="class-row-code" style="font-size:12px; font-family:var(--mono);">{{ $class->code }}</div>
                  @if($class->schedules->first())
                  <div style="font-size:11px; color:var(--muted); margin-top:4px; line-height:1.35;">
                    {{ $class->schedules->first()->days }} · {{ $class->schedules->first()->time }} · Room {{ $class->schedules->first()->room }}
                  </div>
                  @endif
                </div>
                <div style="font-size:12px; text-align:right; min-width:100px;">
                  @if($class->professors->first())
                  <strong style="display:block; margin-bottom:2px;">{{ $class->professors->first()->name }}</strong>
                  <span style="font-size:11px;">Professor</span>
                  @endif
                </div>
              </div>
              @empty
              <div class="empty-state" style="padding:10px 0; font-size:12px;">No classes enrolled</div>
              @endforelse
            </div>

          </div>

        </div>

      </div>

    </div>
  </div>
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
    const modal = document.getElementById('qrModal');
    modal.style.display = 'flex';
    modal.classList.add('active');
  }

  function closeQRModal() {
    const modal = document.getElementById('qrModal');
    modal.style.display = 'none';
    modal.classList.remove('active');
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
      student_id: {{ Auth::user()->id }},
      student_name: '{{ Auth::user()->name }}',
      email: '{{ Auth::user()->email }}'
    };
    const qrData = JSON.stringify(qrPayload);

    generateQR('qrDashboard', qrData);
    generateQR('qrModalCanvas', qrData);
  }, 100);
</script>
@endsection