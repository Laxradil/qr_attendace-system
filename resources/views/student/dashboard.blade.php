@extends('layouts.student')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . '. Here is your attendance overview.')

@section('content')
<<<<<<< HEAD
<!-- ══ DASHBOARD ══ -->
<section class="page" id="dashboard">

  <!-- Stat row -->
  <div class="stats">
=======
<section class="page" id="dashboard" style="display: flex; flex-direction: column; height: calc(100vh - 100px); overflow: hidden;">

  <div class="stats" style="margin-bottom: 20px; flex-shrink: 0;">
>>>>>>> origin/branch-ni-kirb
    <div class="stat glass">
      <div class="stat-icon blue">▤</div>
      <div class="stat-body">
        <strong>{{ $classes->count() }}</strong>
        <span class="stat-label">Enrolled Classes</span>
<<<<<<< HEAD
        <a href="{{ route('student.classes') }}">View classes →</a>
=======
>>>>>>> origin/branch-ni-kirb
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon green">✓</div>
      <div class="stat-body">
        <strong>{{ $totalPresent }}</strong>
        <span class="stat-label">Present</span>
<<<<<<< HEAD
        <a href="{{ route('student.attendance') }}">View attendance →</a>
=======
>>>>>>> origin/branch-ni-kirb
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">◷</div>
      <div class="stat-body">
        <strong>{{ $totalLate }}</strong>
        <span class="stat-label">Late</span>
<<<<<<< HEAD
        <a href="{{ route('student.attendance') }}">View attendance →</a>
=======
>>>>>>> origin/branch-ni-kirb
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">✕</div>
      <div class="stat-body">
        <strong>{{ $totalAbsent }}</strong>
        <span class="stat-label">Absent</span>
<<<<<<< HEAD
        <a href="{{ route('student.attendance') }}">View attendance →</a>
=======
>>>>>>> origin/branch-ni-kirb
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">✉</div>
      <div class="stat-body">
        <strong>{{ $totalExcused ?? 0 }}</strong>
        <span class="stat-label">Excused</span>
<<<<<<< HEAD
        <a href="{{ route('student.attendance') }}">View attendance →</a>
=======
>>>>>>> origin/branch-ni-kirb
      </div>
    </div>
  </div>

<<<<<<< HEAD
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
        <a href="{{ route('student.classes') }}" class="btn btn-pill" style="width:100%;margin-top:auto;justify-content:center;text-decoration:none">View All Classes →</a>
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

        <a href="{{ route('student.attendance') }}" class="btn btn-pill" style="width:100%;margin-top:auto;justify-content:center;text-decoration:none;display:flex">View All Records →</a>
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
          <button class="btn btn-pill primary" onclick="openQRModal()">Show QR</button>
          <button class="btn btn-pill" onclick="showToast('Downloading QR...','📥','#4dffa0')">Download</button>
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
=======
  <div class="dash-grid" style="grid-template-columns: 1fr 1fr; gap: 20px; flex-grow: 1; min-height: 0;">

    <div class="dash-col" style="display: flex; flex-direction: column;">
      <div class="card glass qr-container stretch" style="padding: 30px; display: flex; flex-direction: column; align-items: center; text-align: center; height: 100%;">
        <div class="qr-label" style="font-size: 18px; font-weight: 700; margin-bottom: 24px;">Your QR Code</div>
        
        <div class="qr-frame" style="padding: 15px; margin-bottom: 20px; background: white; border-radius: 12px; width: 100%; max-width: 280px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
          <canvas id="qrDashboard" style="width: 100%; height: auto;"></canvas>
        </div>
        
        <div class="qr-student-name" style="font-size: 22px; font-weight: 800; margin-bottom: 6px;">{{ Auth::user()->name }}</div>
        <div class="qr-student-id" style="font-size: 14px; color: var(--muted); margin-bottom: 12px;">Student ID: {{ Auth::user()->id }}</div>
        
        <div class="qr-hint" style="font-size: 14.5px; font-weight: 600; color: #ffffff;">
          Show to professor for attendance
        </div>
        
        <div style="flex-grow: 1;"></div>
        
        <div class="qr-actions" style="display: flex; gap: 12px; width: 100%; justify-content: center; margin-top: 30px; margin-bottom: 20px;">
          <button class="btn btn-pill primary" onclick="openQRModal()" style="flex: 1; padding: 10px 20px; font-size: 14px; color: #ffffff;">Show QR</button>
          <button class="btn btn-pill" onclick="downloadQR()" style="flex: 1; padding: 10px 20px; font-size: 14px; color: #ffffff; background: rgba(255, 255, 255, 0.15);">Download</button>
        </div>
        
        <div class="qr-status" style="font-size: 13px; color: var(--muted); font-weight: 600;">
          <span style="width:8px;height:8px;border-radius:50%;background:var(--green);box-shadow:0 0 8px rgba(24,240,139,.8);display:inline-block;margin-right:6px;"></span>
          System Online
        </div>
      </div>
    </div>

    <div class="dash-col" style="display: flex; flex-direction: column; gap: 20px;">
      
      <div class="card glass" style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
        <div class="section-head" style="margin-bottom: 16px;">
          <h3 style="font-size: 16px; margin: 0;">📋 Recent Attendance</h3>
          <a href="{{ route('student.attendance') }}" style="font-size: 13px; color: #ffffff; font-weight: 600; text-decoration: none;">View all</a>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
          <span style="font-size:12px;color:var(--muted);font-weight:600">Attendance Rate</span>
          <span style="font-size:14px;font-weight:800;color:#4dffa0">{{ $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0 }}%</span>
        </div>
        <div class="prog-bar" style="height: 6px; margin-bottom: 16px;"><div class="prog-fill" style="width:{{ $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100) : 0 }}%"></div></div>

        <div style="flex-grow: 1; overflow-y: auto; padding-right: 12px;">
          @forelse($recentAttendance->take(3) as $record)
          <div class="att-row" style="padding: 12px 16px; margin-bottom: 8px; display: flex; align-items: center; background: rgba(255,255,255,0.04); border-radius: 10px;">
            <div style="flex-grow: 1;">
              <div class="att-class" style="font-size: 14px; font-weight: 600; margin-bottom: 2px;">{{ $record->classe->code }} — {{ $record->classe->name }}</div>
              <div class="att-date" style="font-size: 12px; color: var(--muted);">{{ $record->recorded_at->format('M d, Y') }}</div>
            </div>
            
            <span class="att-time" style="font-size: 13px; font-weight: 700; color: #ffffff; margin-right: 16px;">{{ $record->recorded_at->format('h:i A') }}</span>
            <span class="pill {{ $record->status === 'present' ? 'green' : ($record->status === 'late' ? 'yellow' : ($record->status === 'absent' ? 'red' : 'purple')) }}" style="font-size: 11px; padding: 4px 10px;">
              {{ ucfirst($record->status) }}
            </span>
          </div>
          @empty
          <div class="empty-state" style="padding:15px 0;font-size:13px">No records yet.</div>
          @endforelse
        </div>

        <a href="{{ route('student.attendance') }}" class="btn btn-pill" style="width:100%;margin-top:auto;justify-content:center;text-decoration:none;display:flex;padding: 8px;font-size:13px; color: #ffffff; background: rgba(255, 255, 255, 0.1);">View All Records →</a>
      </div>

      <div class="card glass" style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
        <div class="section-head" style="margin-bottom: 16px;">
          <h3 style="font-size: 16px; margin: 0;">📚 Your Classes</h3>
          <a href="{{ route('student.classes') }}" style="font-size: 13px; color: #ffffff; font-weight: 600; text-decoration: none;">View all</a>
        </div>
        
        <div style="flex-grow: 1; overflow: hidden; padding-right: 4px;">
          @php $classList = collect($classes); @endphp
          @forelse($classList->take(3) as $class)
          <div class="class-row" style="padding: 10px 14px; margin-bottom: 6px; display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.04); border-radius: 8px;">
            <div class="class-row-left" style="flex-grow: 1; padding-right: 12px;">
              <div class="class-row-name" style="font-size: 13.5px; font-weight: 600; margin-bottom: 2px;">{{ $class->code }} — {{ $class->name }}</div>
              <div class="class-row-code" style="font-size: 11px; color: var(--muted);">{{ $class->code }}</div>
            </div>
            <div class="class-row-prof" style="font-size: 11px; text-align: right; min-width: 100px;">
              @if($class->professors->first())
              <strong style="display: block; color: #ffffff; margin-bottom: 2px;">{{ $class->professors->first()->name }}</strong>
              <span style="color: var(--muted);">Professor</span>
              @endif
            </div>
          </div>
          @empty
          <div class="empty-state" style="padding:10px 0;font-size:12px">No classes enrolled</div>
          @endforelse
        </div>
        
        <a href="{{ route('student.classes') }}" class="btn btn-pill" style="width:100%;margin-top:auto;justify-content:center;text-decoration:none;padding: 8px;font-size:13px; color: #ffffff; background: rgba(255, 255, 255, 0.1); display:flex;">View All Classes</a>
      </div>

    </div>

  </div>
</section>

>>>>>>> origin/branch-ni-kirb
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
<<<<<<< HEAD
=======
    function downloadQR() {
      const canvas = document.getElementById('qrDashboard');
      if (!canvas) return;
      const link = document.createElement('a');
      link.download = 'student-qr.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    }
>>>>>>> origin/branch-ni-kirb
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