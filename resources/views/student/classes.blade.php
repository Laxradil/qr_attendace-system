@extends('layouts.student')

@section('title', 'My Classes')
@section('subtitle', 'View your enrolled classes and manage your QR code.')

@section('content')
<style>
  .class-card {
    cursor: pointer;
    transition: background 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
    box-shadow: none !important;
  }
  
  .class-card:hover {
    transform: none;
    background: rgba(255, 255, 255, 0.10);
    border-color: rgba(255, 255, 255, 0.24);
    box-shadow: none !important;
  }

  .class-card::before {
    content: "";
    position: absolute;
    top: -40%;
    left: -30%;
    width: 80%;
    height: 80%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(139,92,255,.12), transparent 70%);
    pointer-events: none;
  }
  
  .class-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
  
  .class-card-expand-icon {
    display: inline-block;
    transition: transform 0.3s ease;
    margin-left: 8px;
  }
  
  .class-card.expanded .class-card-expand-icon {
    transform: rotate(180deg);
  }
  
  .class-card-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, opacity 0.3s ease, margin 0.3s ease;
    opacity: 0;
    margin-top: 0;
  }
  
  .class-card.expanded .class-card-content {
    max-height: 500px;
    opacity: 1;
    margin-top: 14px;
  }
  
  .class-card-divider {
    width: 100%;
    height: 1px;
    background: rgba(255, 255, 255, 0.09);
    margin: 14px 0;
  }
  
  .classmates-section {
    margin-top: 14px;
  }
  
  .classmates-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: 0.18em;
    text-transform: uppercase;
    margin-bottom: 10px;
    display: block;
  }
  
  .classmates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 8px;
  }
  
  .classmate-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    transition: all 0.2s ease;
  }
  
  .classmate-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.15);
  }
  
  .classmate-name {
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 4px;
    word-break: break-word;
  }
  
  .classmate-id {
    font-size: 10px;
    color: var(--muted);
    font-family: var(--mono);
  }
  
  .classmates-count {
    font-size: 11px;
    color: var(--faint);
    margin-top: 8px;
  }
  
  /* Remove purple gradient on expanded class cards */
  .class-card.expanded {
    background: inherit !important;
  }

  .qr-sidebar.glass {
    box-shadow: none !important;
    border: 1px solid rgba(255,255,255,.12);
  }
</style>

<!-- ══ MY CLASSES ══ -->
<section class="page" id="classes">
  <div class="classes-layout">

    <!-- Left: enrolled classes -->
    <div>
      <div style="font-size:10.5px;font-weight:700;color:var(--muted);letter-spacing:.18em;text-transform:uppercase;margin-bottom:14px">Your Enrolled Classes</div>

      @forelse($classes as $class)
      <div class="class-card glass" onclick="toggleClassExpand(this, event)">
        <div class="class-card-top">
          <div style="flex: 1;">
            <div class="class-card-name">{{ $class->code }} — {{ $class->name }}</div>
            <div class="class-card-code">{{ $class->code }}</div>
          </div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <span class="pill green">Active</span>
            <span class="class-card-expand-icon">▼</span>
          </div>
        </div>
        <div class="class-card-meta">
          @if($class->professors->first())
          <div class="class-meta-item">🎓 Professor: <strong>{{ $class->professors->first()->name }}</strong></div>
          @endif
        </div>
        
        <!-- Expandable Content -->
        <div class="class-card-content">
          <div class="class-card-divider"></div>
          
          <div class="classmates-section">
            <span class="classmates-label">Classmates ({{ $class->students->count() }})</span>
            
            @if($class->students->count() > 0)
            <div class="classmates-grid">
              @foreach($class->students as $student)
              <div class="classmate-item">
                <div class="classmate-name">{{ $student->name }}</div>
                <div class="classmate-id">ID: {{ $student->id }}</div>
              </div>
              @endforeach
            </div>
            <div class="classmates-count">{{ $class->students->count() }} student{{ $class->students->count() !== 1 ? 's' : '' }} enrolled</div>
            @else
            <div style="text-align: center; color: var(--faint); padding: 16px 0; font-size: 12px;">
              No classmates yet
            </div>
            @endif
          </div>
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
          <button class="btn btn-pill" href="#" onclick="downloadQR()">Download</button>
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
  // Expand/collapse class card
  function toggleClassExpand(card, event) {
    // Prevent expansion if clicking on buttons
    if (event.target.tagName === 'BUTTON' || event.target.tagName === 'A') {
      return;
    }
    
    card.classList.toggle('expanded');
  }

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
