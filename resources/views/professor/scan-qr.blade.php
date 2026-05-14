{{-- @var \Illuminate\Support\Collection $classes --}}
{{-- @var string|null $selectedClassId --}}
@extends('layouts.professor')

@section('title', 'Scan QR - Professor')
@section('header', 'QR Code Scanner')
@section('subheader', 'Web Scanner — Scan QR codes to record student attendance')

@section('content')
<style>
  .search-bar {
    display: none !important;
  }
</style>
<div class="scanner-layout">
  <!-- Left: Scanner Box -->
  <div style="display:flex;flex-direction:column;height:100%">
    <div class="scanner-box glass" style="flex:1">
      <div class="section-head" style="margin-bottom:14px">
        <h3>▦ QR Scanner</h3>
        <div class="scan-status"><span class="dot"></span> Ready to Scan</div>
      </div>
      <div class="scanner-tabs">
        <button type="button" class="scanner-tab active" data-mode="camera">📷 Camera Mode</button>
        <button type="button" class="scanner-tab" data-mode="hardware">⌨ Hardware Scanner</button>
      </div>
      <div class="cam-viewport" style="flex:1;aspect-ratio:unset">
        <div class="cam-inner"></div>
      </div>
      <div class="cam-btns" style="margin-top:12px">
        <button type="button" class="cam-btn start">▶ Start Camera</button>
        <button type="button" class="cam-btn stop" disabled>⏹ Stop Camera</button>
      </div>
    </div>
  </div>

  <!-- Right: Attendance Recording Form -->
  <div>
    <form method="POST" action="{{ route('professor.attendance.store') }}" class="att-recording glass" style="border-radius:var(--radius-lg);padding:20px;margin-bottom:14px">
      @csrf
      <div class="section-head" style="margin-bottom:14px">
        <h3>📋 Attendance Recording</h3>
      </div>
      <div class="att-field">
        <label>Select Class <span style="color:var(--red)">*</span></label>
        <select class="att-select" name="class_id" required>
          <option value="">Choose a class...</option>
          @forelse($classes as $class)
            <option value="{{ $class->id }}">{{ $class->code }} — {{ $class->name }}</option>
          @empty
            <option value="" disabled>No classes available</option>
          @endforelse
        </select>
      </div>
      <div class="att-field">
        <label>Student <span style="color:var(--red)">*</span></label>
        <select class="att-select" name="student_id" required>
          <option value="">Select a student...</option>
          @php
            $allStudents = collect();
            foreach($classes as $class) {
              $allStudents = $allStudents->merge($class->students);
            }
            $allStudents = $allStudents->unique('id')->values();
          @endphp
          @forelse($allStudents as $student)
            <option value="{{ $student->id }}">{{ $student->name }}</option>
          @empty
            <option value="" disabled>No students available</option>
          @endforelse
        </select>
      </div>
      <div class="att-field">
        <label>QR Code <span style="color:var(--red)">*</span></label>
        <input class="att-input" type="text" name="qr_code" placeholder="QR code will appear here..." required autofocus>
      </div>
      <button type="submit" class="report-btn" style="margin-top:10px">Record Attendance →</button>
    </form>

    <div class="recent-scans-box glass" style="border-radius:var(--radius-lg);padding:20px">
      <div class="section-head">
        <h3>⚡ Recent Scans</h3>
      </div>
      <div class="no-scans">No scans yet</div>
    </div>
  </div>
</div>

<style>
  .scanner-layout {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 16px;
    flex: 1;
    min-height: 0;
    align-items: stretch;
  }
  
  .scanner-box {
    border-radius: var(--radius-lg);
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  
  .scanner-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
    margin-bottom: 16px;
  }
  
  .scanner-tab {
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: rgba(234,240,255,.7);
    border-radius: 13px;
    padding: 9px;
    font-weight: 700;
    cursor: pointer;
    font-size: 13px;
    font-family: var(--font);
    transition: .2s ease;
  }
  
  .scanner-tab.active {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));
    color: #fff;
    border-color: transparent;
  }

  .scanner-tab:hover {
    transform: translateY(-1px);
    opacity: 0.9;
  }

  .scanner-tab.active:hover {
    background: linear-gradient(135deg,rgba(139,92,255,.9),rgba(67,166,255,.6));
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(80,94,255,.18);
  }

  body.theme-light .scanner-tab {
    background: rgba(15,23,42,.08) !important;
    border: 1px solid rgba(15,23,42,.15) !important;
    color: #0f172a !important;
  }

  body.theme-light .scanner-tab:hover {
    background: rgba(15,23,42,.12) !important;
    border-color: rgba(15,23,42,.2) !important;
    color: #0f172a !important;
  }

  body.theme-light .scanner-tab.active {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5)) !important;
    color: #fff !important;
    border-color: transparent !important;
  }

  body.theme-light .scanner-tab.active:hover {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5)) !important;
    color: #fff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 10px 24px rgba(80,94,255,.18) !important;
  }
  
  .cam-viewport {
    width: 100%;
    border-radius: 18px;
    background: rgba(0,0,0,.4);
    border: 1.5px dashed rgba(139,92,255,.4);
    display: grid;
    place-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 0;
    flex: 1;
    min-height: 120px;
  }
  
  .cam-inner {
    width: 65%;
    aspect-ratio: 1;
    border-radius: 12px;
    border: 2.5px solid rgba(139,92,255,.7);
    box-shadow: 0 0 0 2000px rgba(0,0,0,.2), inset 0 0 20px rgba(139,92,255,.1);
    position: relative;
  }
  
  .cam-inner::before,
  .cam-inner::after {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    border-color: rgba(139,92,255,.9);
    border-style: solid;
  }
  
  .cam-inner::before {
    top: -2px;
    left: -2px;
    border-width: 3px 0 0 3px;
    border-radius: 4px 0 0 0;
  }
  
  .cam-inner::after {
    bottom: -2px;
    right: -2px;
    border-width: 0 3px 3px 0;
    border-radius: 0 0 4px 0;
  }
  
  .scan-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    background: rgba(24,240,139,.18);
    border: 1px solid rgba(24,240,139,.4);
    color: #18f08b;
    margin-bottom: 12px;
  }

  body.theme-light .scan-status {
    background: rgba(24,240,139,.15) !important;
    border-color: rgba(24,240,139,.35) !important;
    color: #059669 !important;
  }
  
  .cam-btns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
  }
  
  .cam-btn {
    border: 0;
    border-radius: 13px;
    padding: 11px;
    font-weight: 800;
    cursor: pointer;
    font-size: 13px;
    font-family: var(--font);
    transition: .2s ease;
  }
  
  .cam-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .cam-btn.start {
    background: linear-gradient(135deg,rgba(139,92,255,.9),rgba(67,166,255,.55));
    color: #fff;
    box-shadow: 0 8px 24px rgba(80,94,255,.25);
  }
  
  .cam-btn.start:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(80,94,255,.4);
  }
  
  .cam-btn.stop {
    background: rgba(255,61,114,.12);
    border: 1px solid rgba(255,61,114,.25);
    color: #ff8298;
  }
  
  .cam-btn.stop:hover:not(:disabled) {
    background: rgba(255,61,114,.2);
  }

  body.theme-light .cam-btn.start {
    background: linear-gradient(135deg,rgba(139,92,255,.9),rgba(67,166,255,.55)) !important;
    color: #fff !important;
    box-shadow: 0 8px 24px rgba(80,94,255,.25) !important;
  }

  body.theme-light .cam-btn.start:hover:not(:disabled) {
    background: linear-gradient(135deg,rgba(122,64,255,.95),rgba(56,142,255,.6)) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 14px 32px rgba(80,94,255,.4) !important;
  }

  body.theme-light .cam-btn.stop {
    background: rgba(255,61,114,.12) !important;
    border: 1px solid rgba(255,61,114,.25) !important;
    color: #ff8298 !important;
  }

  body.theme-light .cam-btn.stop:hover:not(:disabled) {
    background: rgba(255,61,114,.22) !important;
  }

  body.theme-light .report-btn {
    background: linear-gradient(135deg,rgba(139,92,255,.85),rgba(67,166,255,.45)) !important;
    color: #fff !important;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25),0 8px 24px rgba(80,94,255,.2) !important;
  }

  body.theme-light .report-btn:hover {
    background: linear-gradient(135deg,rgba(122,64,255,.95),rgba(56,142,255,.6)) !important;
    transform: translateY(-2px) !important;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25),0 14px 32px rgba(80,94,255,.35) !important;
  }

  body.theme-light .scanner-tab {
    background: rgba(15,23,42,.08) !important;
    border: 1px solid rgba(15,23,42,.15) !important;
    color: #0f172a !important;
  }

  body.theme-light .scanner-tab.active {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5)) !important;
    color: #fff !important;
    border-color: transparent !important;
  }

  body.theme-light .scanner-tab:hover {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5)) !important;
    color: #fff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 10px 24px rgba(80,94,255,.18) !important;
  }

  .att-recording {
    border-radius: var(--radius-lg);
    padding: 20px;
    margin-bottom: 14px;
  }
  
  .att-field {
    margin-bottom: 14px;
  }
  
  .att-field label {
    display: block;
    font-size: 11.5px;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  
  .att-select,
  .att-input {
    width: 100%;
    padding: 11px 14px;
    border-radius: 13px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13.5px;
    font-family: var(--font);
    font-weight: 500;
    outline: none;
    transition: .2s ease;
  }
  
  .att-select:focus,
  .att-input:focus {
    border-color: rgba(139,92,255,.5);
    background: rgba(255,255,255,.1);
    box-shadow: 0 0 0 3px rgba(139,92,255,.12);
  }
  
  .att-select option {
    background: #0a0d22;
  }
  
  .recent-scans-box {
    border-radius: var(--radius-lg);
    padding: 20px;
  }
  
  .no-scans {
    text-align: center;
    padding: 28px 0;
    color: var(--faint);
    font-size: 13px;
  }
  
  @media(max-width:1200px){
    .scanner-layout{grid-template-columns:1fr}
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
@php
  $scanClasses = $classes->map(function($class) {
      return [
          'id' => $class->id,
          'studentIds' => $class->students->pluck('id')->values(),
          'schedules' => $class->schedules->map(function($schedule) {
              return [
                  'days' => $schedule->days,
                  'start_time' => $schedule->start_time,
                  'end_time' => $schedule->end_time,
              ];
          }),
      ];
  });
@endphp
<script>
  let stream = null;
  let currentMode = 'camera';
  const classesData = @json($scanClasses);
  const selectedClassQuery = "{{ $selectedClassId ?? '' }}";
  const camViewport = document.querySelector('.cam-inner');
  const startBtn = document.querySelector('.cam-btn.start');
  const stopBtn = document.querySelector('.cam-btn.stop');
  const qrInput = document.querySelector('input[name="qr_code"]');
  const classSelect = document.querySelector('select[name="class_id"]');
  const studentSelect = document.querySelector('select[name="student_id"]');
  const scannerTabs = document.querySelectorAll('.scanner-tab');
  const cameraControls = document.querySelector('.cam-btns');

  if (selectedClassQuery) {
    classSelect.value = selectedClassQuery;
    classSelect.dispatchEvent(new Event('change'));
  }

  function parseScheduleDays(days) {
    if (!days) return [];
    return days
      .split(/[,;\/\s]+/)
      .map(d => d.trim())
      .filter(Boolean)
      .map(d => {
        const normalized = d.toLowerCase();
        return {
          mon: 'Monday', monday: 'Monday', tue: 'Tuesday', tues: 'Tuesday', tuesday: 'Tuesday',
          wed: 'Wednesday', wednesday: 'Wednesday', thu: 'Thursday', thur: 'Thursday', thurs: 'Thursday', thursday: 'Thursday',
          fri: 'Friday', friday: 'Friday', sat: 'Saturday', saturday: 'Saturday', sun: 'Sunday', sunday: 'Sunday'
        }[normalized];
      })
      .filter(Boolean);
  }

  function scheduleMatchesDay(schedule, day) {
    const normalizedDay = day.toLowerCase();
    return parseScheduleDays(schedule.days).some(d => d.toLowerCase() === normalizedDay);
  }

  function scheduleMatchesNow(schedule) {
    if (!schedule.start_time || !schedule.end_time) return false;
    const now = new Date();
    const today = now.toISOString().slice(0, 10);
    const start = new Date(`${today}T${schedule.start_time}`);
    const end = new Date(`${today}T${schedule.end_time}`);
    if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
      return false;
    }
    return now >= start && now <= end;
  }

  function findBestClassForStudent(studentId) {
    const today = new Date().toLocaleDateString('en-US', { weekday: 'long' });
    const candidates = classesData.filter(cls => cls.studentIds.includes(studentId));
    if (!candidates.length) {
      return null;
    }

    const currentMatches = candidates.filter(cls => cls.schedules.some(schedule => scheduleMatchesDay(schedule, today) && scheduleMatchesNow(schedule)));
    if (currentMatches.length === 1) return currentMatches[0].id;
    if (currentMatches.length > 1) return currentMatches[0].id;

    const todayMatches = candidates.filter(cls => cls.schedules.some(schedule => scheduleMatchesDay(schedule, today)));
    if (todayMatches.length === 1) return todayMatches[0].id;

    return candidates[0].id;
  }

  // Tab switching
  scannerTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      scannerTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentMode = tab.dataset.mode;
      
      if (currentMode === 'hardware') {
        // Stop camera if running
        if (stream) {
          stream.getTracks().forEach(track => track.stop());
          stream = null;
        }
        camViewport.innerHTML = '';
        cameraControls.style.display = 'none';
        qrInput.focus();
        qrInput.placeholder = 'Scan QR code with hardware scanner...';
      } else {
        cameraControls.style.display = 'grid';
        qrInput.placeholder = 'QR code will appear here...';
      }
    });
  });
  
  // Update student list when class changes
  classSelect.addEventListener('change', function() {
    const classId = this.value;
    if (classId) {
      // Get students for selected class
      fetch(`/professor/class/${classId}/students`)
        .then(r => r.json())
        .then(students => {
          studentSelect.innerHTML = '<option value="">Select a student...</option>';
          students.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            studentSelect.appendChild(opt);
          });

          if (studentSelect.dataset.pendingValue) {
            studentSelect.value = studentSelect.dataset.pendingValue;
            studentSelect.dataset.pendingValue = '';
          }
        })
        .catch(e => console.error('Error loading students:', e));
    }
  });
  
  startBtn.addEventListener('click', async () => {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ 
        video: { facingMode: 'environment' },
        audio: false 
      });
      
      const video = document.createElement('video');
      video.srcObject = stream;
      video.autoplay = true;
      video.playsInline = true;
      video.style.width = '100%';
      video.style.height = '100%';
      video.style.borderRadius = '12px';
      video.style.objectFit = 'cover';
      
      camViewport.innerHTML = '';
      camViewport.appendChild(video);
      
      startBtn.disabled = true;
      stopBtn.disabled = false;
      
      // Initialize QR code detection
      initQRDetection(video);
    } catch (err) {
      alert('Error accessing camera: ' + err.message);
    }
  });
  
  stopBtn.addEventListener('click', () => {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      stream = null;
    }
    camViewport.innerHTML = '';
    startBtn.disabled = false;
    stopBtn.disabled = true;
  });
  
  function initQRDetection(video) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    let lastDetectedValue = null;
    
    function detectQR() {
      if (!stream) return;
      
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      ctx.drawImage(video, 0, 0);
      
      try {
        // Try to detect QR code using jsQR if available
        if (typeof jsQR !== 'undefined') {
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, canvas.width, canvas.height);
          if (code && code.data !== lastDetectedValue) {
            lastDetectedValue = code.data;
            qrInput.value = code.data;
            qrInput.dispatchEvent(new Event('input'));

            try {
              const decoded = JSON.parse(code.data);
              if (decoded.type === 'student_attendance' && decoded.student_id) {
                const studentId = decoded.student_id.toString();
                if (studentSelect.querySelector(`option[value="${studentId}"]`)) {
                  studentSelect.value = studentId;
                }

                const inferredClassId = findBestClassForStudent(studentId);
                if (inferredClassId) {
                  studentSelect.dataset.pendingValue = studentId;
                  classSelect.value = inferredClassId;
                  classSelect.dispatchEvent(new Event('change'));
                }
              }
            } catch (e) {
              // not a JSON attendance payload
            }
          }
        }
      } catch (e) {
        // QR library not available
      }
      
      requestAnimationFrame(detectQR);
    }
    
    detectQR();
  }
</script>

@endsection
