@extends('layouts.student')

@section('title', 'My Classes')
@section('subtitle', 'View your enrolled classes and manage your QR code.')

@section('content')
<!-- ══ MY CLASSES ══ -->
<section class="page" id="classes">
  <div class="classes-layout">

    <!-- Left: enrolled classes -->
    <div>
      <div style="font-size:10.5px;font-weight:700;color:var(--muted);letter-spacing:.18em;text-transform:uppercase;margin-bottom:14px">Your Enrolled Classes</div>

      @forelse($classes as $class)
      <div class="class-card glass">
        <div class="class-card-top">
          <div>
            <div class="class-card-name">{{ $class->code }} — {{ $class->name }}</div>
            <div class="class-card-code">{{ $class->code }}</div>
          </div>
          <span class="pill green">Active</span>
        </div>
        <div class="class-card-meta">
          @if($class->professors->first())
          <div class="class-meta-item">🎓 Professor: <strong>{{ $class->professors->first()->name }}</strong></div>
          @endif
        </div>
      </div>
      @empty
      <div style="text-align:center;color:var(--faint);padding:40px 0;">Not enrolled in any classes.</div>
      @endforelse
    </div>

    <!-- Right: QR + Quick Stats (unified card) -->
    <div>
      <div class="qr-sidebar glass" style="gap:0">
        <div class="qr-label" style="margin-bottom:14px">Your QR Code</div>
        <div class="qr-frame">
          <canvas id="qrClasses"></canvas>
        </div>
        <div class="qr-student-name">{{ Auth::user()->name }}</div>
        <div class="qr-student-id">Student ID: {{ Auth::user()->id }}</div>
        <div class="qr-hint">Show to professor for attendance</div>
        <div class="qr-actions">
          <button class="btn btn-pill primary" onclick="openQRModal()">Show</button>
          <a class="btn btn-pill" href="#" onclick="downloadQR()">Download</a>
        </div>

        <!-- Divider -->
        <div style="width:100%;height:1px;background:rgba(255,255,255,.09);margin:18px 0 16px"></div>

        <span class="quick-stats-title">Quick Stats</span>
        <div class="quick-stats-grid" style="width:100%">
          <div class="qs">
            <span class="qs-val green">{{ $totalPresent }}</span>
            <span class="qs-lbl">Present</span>
          </div>
          <div class="qs">
            <span class="qs-val yellow">{{ $totalLate }}</span>
            <span class="qs-lbl">Late</span>
          </div>
          <div class="qs">
            <span class="qs-val red">{{ $totalAbsent }}</span>
            <span class="qs-lbl">Absent</span>
          </div>
          <div class="qs">
            <span class="qs-val purple">{{ $totalExcused ?? 0 }}</span>
            <span class="qs-lbl">Excused</span>
          </div>
          <div class="qs">
            <span class="qs-val blue">{{ $classes->count() }}</span>
            <span class="qs-lbl">Enrolled</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- QR Modal (reuse dashboard modal) -->
<div class="qr-modal" id="qrModal" style="display:none">
  <div class="qr-modal-overlay" onclick="closeQRModal()"></div>
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
  // Generate QR code for classes page
  setTimeout(function() {
    const qrDataClasses = JSON.stringify({
      type: 'student_attendance',
      student_id: {{ Auth::user()->id }},
      student_name: '{{ Auth::user()->name }}',
      email: '{{ Auth::user()->email }}'
    });
    generateQR('qrClasses', qrDataClasses);
    generateQR('qrModalCanvas', qrDataClasses);
  }, 100);

  function openQRModal() {
    document.getElementById('qrModal').style.display = 'flex';
  }
  function closeQRModal() {
    document.getElementById('qrModal').style.display = 'none';
  }
  function downloadQR() {
    const canvas = document.getElementById('qrClasses');
    const link = document.createElement('a');
    link.download = 'student-qr.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
  }
</script>
@endsection
