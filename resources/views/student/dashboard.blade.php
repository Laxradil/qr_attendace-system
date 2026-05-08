@extends('layouts.student')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
<!-- ══ DASHBOARD ══ -->
<section class="page" id="dashboard">

  <!-- Stat row -->
  <div class="stats">
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $classes->count() }}</strong>
        <span class="stat-label">Enrolled Classes</span>
        <a href="{{ route('student.classes') }}">View classes →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon green">✓</div>
      <div class="stat-body">
        <strong>{{ $totalPresent }}</strong>
        <span class="stat-label">Present</span>
        <a href="{{ route('student.attendance') }}">View attendance →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">◷</div>
      <div class="stat-body">
        <strong>{{ $totalLate }}</strong>
        <span class="stat-label">Late</span>
        <a href="{{ route('student.attendance') }}">View attendance →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $totalAbsent }}</strong>
        <span class="stat-label">Absent</span>
        <a href="{{ route('student.attendance') }}">View attendance →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">✉</div>
      <div class="stat-body">
        <strong>{{ $totalExcused ?? 0 }}</strong>
        <span class="stat-label">Excused</span>
        <a href="{{ route('student.attendance') }}">View attendance →</a>
      </div>
    </div>
  </div>

  <!-- 3-column dashboard -->
  <div class="dash-grid">

    <!-- Col 1: Your Classes -->
    <div class="dash-col">
      <div class="card glass stretch">
        <div class="section-head">
          <h3>📚 Your Classes</h3>
          <a href="{{ route('student.classes') }}">View all →</a>
        </div>
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
        <div class="empty-state" style="padding:20px 0;font-size:12.5px">No classes enrolled</div>
        @endforelse
        <a href="{{ route('student.classes') }}" class="btn" style="width:100%;margin-top:auto;padding-top:11px;padding-bottom:11px;text-align:center;text-decoration:none">View All Classes →</a>
      </div>
    </div>

    <!-- Col 2: Recent Attendance + Quick Actions -->
    <div class="dash-col">
      <div class="card glass stretch">
        <div class="section-head">
          <h3>📋 Recent Attendance</h3>
          <a href="{{ route('student.attendance') }}">View all →</a>
        </div>
        <!-- Progress bar -->
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
        <div class="empty-state" style="padding:20px 0;font-size:12.5px">No records yet.</div>
        @endforelse

        <a href="{{ route('student.attendance') }}" class="btn" style="width:100%;margin-top:auto;text-align:center;text-decoration:none;display:block">View All Records →</a>
      </div>

      <!-- Quick Actions -->
      <div class="card glass" style="flex-shrink:0">
        <div class="section-head">
          <h3>⚡ Quick Actions</h3>
        </div>
        <div class="quick-grid">
          <a href="{{ route('student.classes') }}" class="quick" style="text-decoration:none;color:inherit">
            <div class="stat-icon blue" style="width:38px;height:38px;border-radius:11px;font-size:16px;flex-shrink:0">▤</div>
            <div><strong>My Classes</strong><span>View enrolled</span></div>
          </a>
          <a href="{{ route('student.attendance') }}" class="quick" style="text-decoration:none;color:inherit">
            <div class="stat-icon yellow" style="width:38px;height:38px;border-radius:11px;font-size:16px;flex-shrink:0">📋</div>
            <div><strong>Attendance</strong><span>View records</span></div>
          </a>
        </div>
      </div>
    </div>

    <!-- Col 3: QR Code (Big) -->
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
          <button class="btn primary" onclick="openQRModal()">Show QR</button>
          <button class="btn" onclick="showToast('Downloading QR...','📥','#4dffa0')">Download</button>
        </div>
        <div class="qr-status">
          <span style="width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 8px rgba(24,240,139,.8);display:inline-block"></span>
          System Online
        </div>
      </div>
    </div>

  </div><!-- /dash-grid -->
</section>

<!-- ════ QR FULLSCREEN MODAL ════ -->
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
