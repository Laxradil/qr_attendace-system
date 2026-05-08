@extends('layouts.student')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
@php /** @var \Illuminate\Support\Collection $classes */ @endphp
<section class="page" id="dashboard">

  <div class="stats">
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $classes->count() }}</strong>
        <span class="stat-label">Enrolled Classes</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon green">✓</div>
      <div class="stat-body">
        <strong>{{ $totalPresent }}</strong>
        <span class="stat-label">Present</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">◷</div>
      <div class="stat-body">
        <strong>{{ $totalLate }}</strong>
        <span class="stat-label">Late</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $totalAbsent }}</strong>
        <span class="stat-label">Absent</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">✉</div>
      <div class="stat-body">
        <strong>{{ $totalExcused ?? 0 }}</strong>
        <span class="stat-label">Excused</span>
      </div>
    </div>
  </div>

  <div class="dash-grid">

    <div class="dash-col">
      <div class="card glass stretch">
        <div class="section-head">
          <h3>📚 Your Classes</h3>
        </div>
        @php /** @var \Illuminate\Support\Collection $classes */ @endphp
        @forelse($classes->take(3) as $class)
        <div class="class-row">
          <div class="class-row-left">
            <div class="class-row-name">{{ $class->code }} — {{ $class->name }}</div>
            <div class="class-row-code">{{ $class->code }}</div>
          </div>
          <div class="class-row-prof">
            @if($class->professors->first())
            <strong>{{ $class->professors->first()->name }}</strong>
            Professor
            @endif
          </div>
        </div>
        @empty
        <div class="empty-state" style="padding:30px 10px;font-size:13px;color:#ffffff;text-align:center;font-weight:500;">
          No classes enrolled
        </div>
        @endforelse
        <a href="{{ route('student.classes') }}" class="btn btn-pill" style="width:100%;margin-top:auto;justify-content:center;text-decoration:none;display:flex">View All Classes →</a>
      </div>
    </div>

    <div class="dash-col">
      <div class="card glass stretch">
        <div class="section-head">
          <h3>📋 Recent Attendance</h3>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
          <span style="font-size:12.5px;color:var(--muted);font-weight:600">Attendance Rate</span>
          <span style="font-size:13px;font-weight:800;color:#4dffa0">{{ $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0 }}%</span>
        </div>
        <div class="prog-bar"><div class="prog-fill" style="width:{{ $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0 }}%"></div></div>

        @forelse($recentAttendance->take(3) as $record)
        <div class="att-row">
          <div>
            <div class="att-class">{{ $record->classe->code }} — {{ $record->classe->name }}</div>
            <div class="att-date">{{ $record->recorded_at->format('M d, Y') }}</div>
          </div>
          <span class="att-time">{{ $record->recorded_at->format('h:i A') }}</span>
          <span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : ($record->status === 'absent' ? 'red' : 'purple')) }}">
            {{ ucfirst($record->status) }}
          </span>
        </div>
        @empty
        <div class="empty-state" style="padding:30px 10px;font-size:13px;color:#ffffff;text-align:center;font-weight:500;">
          No records yet.
        </div>
        @endforelse

        <a href="{{ route('student.attendance') }}" class="btn btn-pill" style="width:100%;margin-top:auto;justify-content:center;text-decoration:none;display:flex">View All Records →</a>
      </div>

      <div class="card glass" style="flex-shrink:0">
        <div class="section-head">
          <h3>🕒 Next class</h3>
        </div>
        @php
          $nextClass = $classes->first();
          $nextSchedule = optional($nextClass)->schedules->first();
          $nextProfessor = optional($nextClass)->professors->first();
          $nextStart = $nextSchedule && $nextSchedule->start_time ? \Carbon\Carbon::parse($nextSchedule->start_time)->format('g:i A') : null;
          $nextEnd = $nextSchedule && $nextSchedule->end_time ? \Carbon\Carbon::parse($nextSchedule->end_time)->format('g:i A') : null;
          $nextTimeLabel = $nextStart && $nextEnd ? "{$nextStart} — {$nextEnd}" : ($nextSchedule->time ?? null);
          $timerLabel = $nextSchedule && $nextStart ? ($nextStart . ' · Room ' . ($nextSchedule->room ?? 'TBD')) : 'Room TBD';
          $timerProgress = $nextSchedule ? 55 : 0;
        @endphp
        @if($nextClass && $nextSchedule)
        <div style="display:grid;gap:20px">
          <div style="display:grid;grid-template-columns:1fr;gap:14px;">
            <div>
              <div style="font-size:12.5px;color:var(--muted);font-weight:700;margin-bottom:6px">Time until class</div>
              <div style="font-size:34px;font-weight:800;line-height:1;letter-spacing:-.02em">39:25</div>
            </div>
            <div>
              <div class="prog-bar" style="margin:0 0 10px"><div class="prog-fill" style="width:{{ $timerProgress }}%"></div></div>
              <div style="font-size:13px;color:var(--muted);font-weight:600">{{ $timerLabel }}</div>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr;gap:10px;">
            <div style="font-size:16px;font-weight:800;line-height:1.2">{{ $nextClass->code }} — {{ $nextClass->name }}</div>
            <div style="font-size:13px;color:var(--muted);font-weight:700">{{ $nextProfessor?->name ?? 'Professor not assigned' }}</div>
            <div style="font-size:13px;color:var(--muted);line-height:1.5">Room {{ $nextSchedule->room ?? 'TBD' }} · {{ $nextTimeLabel }}</div>
            <span class="pill green" style="padding:8px 12px;font-size:12px;justify-self:start">QR ready</span>
          </div>
        </div>
        @elseif($nextClass)
        <div class="empty-state" style="padding:30px 10px;font-size:13px;color:#ffffff;text-align:center;font-weight:500;">
          No scheduled classes for today.
        </div>
        @else
        <div class="empty-state" style="padding:30px 10px;font-size:13px;color:#ffffff;text-align:center;font-weight:500;">
          You are not enrolled in any classes yet.
        </div>
        @endif
      </div>
    </div>

    <div class="dash-col">
      <div class="card glass qr-container stretch">
        <div class="qr-label">Your QR Code</div>
        <div class="qr-frame">
          <canvas id="qrDashboard"></canvas>
        </div>
        <div class="qr-student-name">{{ Auth::user()->name }}</div>
        <div class="qr-student-id">Student ID: {{ Auth::user()->id }}</div>
        <div class="qr-hint">Show to professor for attendance</div>
        <div class="qr-actions">
          <button class="btn btn-pill primary" onclick="openQRModal()">Show QR</button>
          <button class="btn btn-pill" onclick="downloadQR()">Download</button>
        </div>
        <div class="qr-status">
          <span style="width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 8px rgba(24,240,139,.8);display:inline-block"></span>
          System Online
        </div>
      </div>
    </div>

  </div></section>

<div class="qr-modal" id="qrModal">
  <div class="qr-modal-overlay"></div>
  <div class="qr-modal-content glass">
    <button class="qr-modal-close" onclick="closeQRModal()">✕</button>
    <div class="qr-modal-body">
      <div style="text-align:center">
        <div style="font-size:16px;font-weight:700;margin-bottom:16px;color:var(--text)">Your QR Code</div>
        <div class="qr-modal-frame">
          <canvas id="qrModalCanvas"></canvas>
        </div>
        <div style="margin-top:16px">
          <div style="font-size:16px;font-weight:800;color:var(--text)">{{ Auth::user()->name }}</div>
          <div style="font-size:13px;color:var(--muted);font-family:var(--mono);margin-top:4px">Student ID: {{ Auth::user()->id }}</div>
          <div style="font-size:12px;color:var(--faint);margin-top:8px">Show to professor for attendance</div>
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
  // Generate QR code for dashboard
  setTimeout(function() {
    const qrData = JSON.stringify({
      type: 'student_attendance',
      student_id: {{ Auth::user()->id }},
      student_name: '{{ Auth::user()->name }}',
      email: '{{ Auth::user()->email }}'
    });
    generateQR('qrDashboard', qrData);
    generateQR('qrModalCanvas', qrData);
  }, 100);

  function openQRModal() {
    document.getElementById('qrModal').classList.add('active');
  }

  function closeQRModal() {
    document.getElementById('qrModal').classList.remove('active');
  }

  // Close modal on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('qrModal').classList.contains('active')) {
      closeQRModal();
    }
  });
</script>
@endsection