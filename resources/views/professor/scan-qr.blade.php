{{-- @var \Illuminate\Support\Collection $classes --}}
{{-- @var string|null $selectedClassId --}}
@extends('layouts.professor')

@section('title', 'Scan QR - Professor')
@section('header', 'QR Code Scanner')
@section('subheader', 'Web Scanner — Scan QR codes to record student attendance')

@section('content')
<div class="scanner-layout">
  <!-- Left: Scanner Box -->
  <div style="display:flex;flex-direction:column;height:100%">
    <div class="scanner-box glass" style="flex:1">
      <div class="section-head" style="margin-bottom:14px">
        <h3>▦ QR Scanner</h3>
        <div class="scan-status"><span class="dot"></span> Ready to Scan</div>
      </div>
      <div class="scanner-tabs">
        <button class="scanner-tab active">📷 Camera Mode</button>
        <button class="scanner-tab">⌨ Hardware Scanner</button>
      </div>
      <div class="cam-viewport" style="flex:1;aspect-ratio:unset">
        <div class="cam-inner"></div>
      </div>
      <div class="cam-btns" style="margin-top:12px">
        <button class="cam-btn start">▶ Start Camera</button>
        <button class="cam-btn stop">⏹ Stop Camera</button>
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
            <option value="{{ $class->id }}">{{ $class->subject_code }} — {{ $class->subject_name }}</option>
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
    background: rgba(24,240,139,.12);
    border: 1px solid rgba(24,240,139,.25);
    color: #4dffa0;
    margin-bottom: 12px;
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
  
  .cam-btn.start {
    background: linear-gradient(135deg,rgba(139,92,255,.9),rgba(67,166,255,.55));
    color: #fff;
    box-shadow: 0 8px 24px rgba(80,94,255,.25);
  }
  
  .cam-btn.start:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(80,94,255,.4);
  }
  
  .cam-btn.stop {
    background: rgba(255,61,114,.12);
    border: 1px solid rgba(255,61,114,.25);
    color: #ff8298;
  }
  
  .cam-btn.stop:hover {
    background: rgba(255,61,114,.2);
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
@endsection
