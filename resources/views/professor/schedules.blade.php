@extends('layouts.professor')

@section('title', 'Class Schedules - Professor')
@section('header', 'Class Schedules')
@section('subheader', 'View all your scheduled classes (read-only)')

@section('content')
<style>
  .search-bar {
    display: none !important;
  }
</style>
<!-- Overview stats -->
<div class="stats" style="grid-template-columns:repeat(4,1fr);margin-bottom:22px;margin-top:6px">
  <div class="stat glass">
    <div class="stat-icon blue">📅</div>
    <div class="stat-body">
      <strong>{{ count($schedules ?? []) }}</strong>
      <span>Total Schedules</span>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon green">👨‍🏫</div>
    <div class="stat-body">
      <strong>{{ collect($schedules ?? [])->pluck('professor')->unique()->count() }}</strong>
      <span>Professors</span>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon yellow">📚</div>
    <div class="stat-body">
      <strong>{{ count($schedules ?? []) }}</strong>
      <span>Subjects</span>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon purple">✓</div>
    <div class="stat-body">
      <strong>{{ count($schedules ?? []) }}</strong>
      <span>Active Rooms</span>
    </div>
  </div>
</div>

<!-- Schedule cards grid -->
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px">
  @forelse($schedules ?? [] as $schedule)
    <div class="glass" style="border-radius:var(--radius-lg);padding:20px;transition:.3s ease">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px">
        <div>
          <h3 style="font-size:15px;font-weight:800;letter-spacing:-.03em;margin-bottom:4px">
            {{ $schedule->subject_name ?? 'Class' }}
          </h3>
        </div>
        <div style="font-size:26px;font-weight:900;font-family:var(--mono);color:rgba(139,92,255,.9);letter-spacing:-.03em">
          {{ $schedule->room ?? 'F-107' }}
        </div>
      </div>
      
      <div style="display:grid;gap:8px;margin-bottom:14px">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px">
          <div style="width:22px;height:22px;border-radius:6px;display:grid;place-items:center;font-size:11px;background:rgba(255,255,255,.08)">📅</div>
          <span style="color:var(--muted)">Days:</span> <strong style="color:var(--text)">{{ $schedule->days ?? 'N/A' }}</strong>
        </div>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px">
          <div style="width:22px;height:22px;border-radius:6px;display:grid;place-items:center;font-size:11px;background:rgba(255,255,255,.08)">🕓</div>
          <span style="color:var(--muted)">Time:</span> <strong style="color:var(--text);font-family:var(--mono)">{{ $schedule->start_time ?? 'N/A' }} - {{ $schedule->end_time ?? 'N/A' }}</strong>
        </div>
        @if($schedule->professor)
          <div style="display:flex;align-items:center;gap:8px;font-size:13px">
            <div style="width:22px;height:22px;border-radius:6px;display:grid;place-items:center;font-size:11px;background:rgba(255,255,255,.08)">👨‍🏫</div>
            <span style="color:var(--muted)">Professor:</span> <strong style="color:var(--text)">{{ $schedule->professor }}</strong>
          </div>
        @endif
      </div>

      <a href="#" style="color:rgba(139,92,255,.9);font-size:12.5px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
       
      </a>
    </div>
  @empty
    <div style="grid-column:1/-1;padding:40px;text-align:center;color:var(--muted)">
      <div style="font-size:48px;margin-bottom:12px">📚</div>
      <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:4px">No Schedules Yet</div>
      <div style="font-size:13px">Your schedule will appear here when it's set up.</div>
    </div>
  @endforelse
</div>

<style>
  .stat {
    border-radius: var(--radius-lg);
    padding: 16px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    transition: .3s ease;
  }
  
  .stat:hover {
    transform: translateY(-3px);
    border-color: rgba(255,255,255,.3);
  }
  
  .stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  
  .stat-icon.blue {
    background: rgba(67,166,255,.18);
    border: 1px solid rgba(67,166,255,.25);
  }
  
  .stat-icon.green {
    background: rgba(24,240,139,.15);
    border: 1px solid rgba(24,240,139,.2);
  }
  
  .stat-icon.yellow {
    background: rgba(255,199,90,.15);
    border: 1px solid rgba(255,199,90,.2);
  }
  
  .stat-icon.purple {
    background: rgba(139,92,255,.18);
    border: 1px solid rgba(139,92,255,.22);
  }
  
  .stat-body strong {
    font-size: 26px;
    font-weight: 900;
    letter-spacing: -.05em;
    display: block;
    line-height: 1;
  }
  
  .stat-body span {
    color: var(--muted);
    font-size: 12px;
    font-weight: 500;
    margin-top: 3px;
    display: block;
  }
</style>

<style>
  /* Light theme solid overrides */
  body.theme-light .stat-icon.blue {
    background: #dbeafe;
    border: 1px solid #3b82f6;
  }
  
  body.theme-light .stat-icon.green {
    background: #dcfce7;
    border: 1px solid #10b981;
  }
  
  body.theme-light .stat-icon.yellow {
    background: #fef3c7;
    border: 1px solid #f59e0b;
  }
  
  body.theme-light .stat-icon.purple {
    background: #ede9fe;
    border: 1px solid #8b5cff;
  }
  
  body.theme-light .stat:hover {
    border-color: #e5e7eb;
  }
  
  /* Solid backgrounds for small icons in cards */
  body.theme-light div[style*="background:rgba(255,255,255,.08)"] {
    background: #f1f5f9 !important;
  }
  
  /* Solid colors for room and link */
  body.theme-light div[style*="color:rgba(139,92,255,.9)"] {
    color: #8b5cff !important;
  }
</style>
@endsection
