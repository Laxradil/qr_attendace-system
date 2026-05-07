@extends('layouts.student')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
<!-- ══ DASHBOARD ══ -->
<section class="page active" id="dashboard">

  <!-- Stat row -->
  <div class="stats">
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $classes->count() }}</strong>
        <span class="stat-label">Enrolled Classes</span>
        <a onclick="navigate('classes')">View classes →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon green">✓</div>
      <div class="stat-body">
        <strong>{{ $totalPresent }}</strong>
        <span class="stat-label">Present</span>
        <a onclick="navigate('attendance')">View attendance →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">◷</div>
      <div class="stat-body">
        <strong>{{ $totalLate }}</strong>
        <span class="stat-label">Late</span>
        <a onclick="navigate('attendance')">View attendance →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $totalAbsent }}</strong>
        <span class="stat-label">Absent</span>
        <a onclick="navigate('attendance')">View attendance →</a>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">✉</div>
      <div class="stat-body">
        <strong>{{ $totalExcused ?? 0 }}</strong>
        <span class="stat-label">Excused</span>
        <a onclick="navigate('attendance')">View attendance →</a>
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
          <a onclick="navigate('classes')">View all →</a>
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
        <button class="btn" style="width:100%;margin-top:auto;padding-top:11px;padding-bottom:11px" onclick="navigate('classes')">View All Classes →</button>
      </div>
    </div>

    <!-- Col 2: Recent Attendance + Quick Actions -->
    <div class="dash-col">
      <div class="card glass stretch">
        <div class="section-head">
          <h3>📋 Recent Attendance</h3>
          <a onclick="navigate('attendance')">View all →</a>
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

        <button class="btn" style="width:100%;margin-top:auto" onclick="navigate('attendance')">View All Records →</button>
      </div>

      <!-- Quick Actions -->
      <div class="card glass" style="flex-shrink:0">
        <div class="section-head">
          <h3>⚡ Quick Actions</h3>
        </div>
        <div class="quick-grid">
          <div class="quick" onclick="navigate('classes')">
            <div class="stat-icon blue" style="width:38px;height:38px;border-radius:11px;font-size:16px;flex-shrink:0">▤</div>
            <div><strong>My Classes</strong><span>View enrolled</span></div>
          </div>
          <div class="quick" onclick="navigate('attendance')">
            <div class="stat-icon yellow" style="width:38px;height:38px;border-radius:11px;font-size:16px;flex-shrink:0">📋</div>
            <div><strong>Attendance</strong><span>View records</span></div>
          </div>
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
          <button class="btn primary" onclick="showToast('QR displayed fullscreen','▦','#b9c4ff')">Show QR</button>
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
  }, 100);
</script>
@endsection
