@extends('layouts.professor')

@section('title', 'Class Schedules - Professor')
@section('header', 'Class Schedules')
@section('subheader', 'View all your scheduled classes (read-only)')

@section('content')
<style>
  .search-bar {
    display: none !important;
  }
  .schedule-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 22px;
    margin-top: 12px;
  }
  .schedule-card {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border-radius: 32px;
    padding: 28px;
    box-shadow: 0 24px 60px rgba(15,23,42,.12);
    transition: transform .3s ease, box-shadow .3s ease;
  }
  body.theme-ash .glass.schedule-card,
  body.theme-light .glass.schedule-card,
  body.theme-dark .glass.schedule-card,
  body.theme-onyx .glass.schedule-card {
    background: linear-gradient(180deg, #1f2937 0%, #111827 100%) !important;
    background-image: linear-gradient(180deg, #1f2937 0%, #111827 100%) !important;
    background-color: #111827 !important;
    border-color: rgba(255,255,255,.08) !important;
    box-shadow: 0 24px 60px rgba(0,0,0,.24) !important;
    backdrop-filter: blur(18px) !important;
    -webkit-backdrop-filter: blur(18px) !important;
  }
  .schedule-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 28px 72px rgba(15,23,42,.16);
  }
  .schedule-card h3 {
    font-size: 16px;
    font-weight: 800;
    margin: 0 0 4px;
    color: #ffffff;
  }
  .schedule-card .room-code {
    font-size: 14px;
    font-weight: 900;
    font-family: var(--mono);
    color: rgba(255,255,255,.9);
  }
  .schedule-card .schedule-meta {
    display: grid;
    gap: 10px;
    margin-bottom: 18px;
  }
  .schedule-card .schedule-meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #ffffff;
  }
  .schedule-card .schedule-meta-item span,
  .schedule-card .schedule-meta-item strong {
    color: #ffffff;
  }
  .schedule-card .schedule-meta-item .icon {
    width: 26px;
    height: 26px;
    display: grid;
    place-items: center;
    border-radius: 10px;
    background: rgba(255,255,255,.08);
    color: inherit;
    font-size: 12px;
  }
  body.theme-ash .schedule-card {
    background: linear-gradient(135deg, rgba(113,100,242,.95), rgba(59,130,246,.4));
    border-color: rgba(255,255,255,.14);
  }
  body.theme-light .schedule-card {
    background: linear-gradient(135deg, rgba(139,92,255,.12), rgba(67,166,255,.1));
    border-color: rgba(148,163,184,.2);
    box-shadow: 0 16px 40px rgba(15,23,42,.08);
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
<div class="schedule-grid">
  @forelse($schedules ?? [] as $schedule)
    <div class="glass schedule-card" data-class-id="{{ $schedule->class_id ?? '' }}" id="schedule-{{ $schedule->id }}">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px">
        <div>
          <h3>{{ $schedule->subject_name ?? 'Class' }}</h3>
          <div style="font-size:13px;color:var(--muted);letter-spacing:.02em">{{ $schedule->subject_code ?? 'N/A' }}</div>
        </div>
        <div class="room-code">{{ $schedule->room ?? 'F-107' }}</div>
      </div>
      
      <div class="schedule-meta">
        <div class="schedule-meta-item">
          <div class="icon">📅</div>
          <span>Days:</span> <strong>{{ $schedule->days ?? 'N/A' }}</strong>
        </div>
        <div class="schedule-meta-item">
          <div class="icon">🕓</div>
          <span>Time:</span>
          <strong style="font-family:var(--mono)">
            {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') : 'N/A' }}
            -
            {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') : 'N/A' }}
          </strong>
        </div>
        @if($schedule->professor)
          <div class="schedule-meta-item">
            <div class="icon">👨‍🏫</div>
            <span>Professor:</span> <strong>{{ $schedule->professor }}</strong>
          </div>
        @endif
      </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  const params = new URLSearchParams(window.location.search);
  const classId = params.get('class_id');
  if (!classId) return;
  const el = document.querySelector('.schedule-card[data-class-id="' + classId + '"]');
  if (!el) return;
  el.classList.add('selected-highlight');
  el.scrollIntoView({ behavior: 'smooth', block: 'center' });
});
</script>

<style>
  .selected-highlight {
    border-color: #6b73ff !important;
    box-shadow: 0 10px 40px rgba(107,115,255,.12) !important;
    transform: translateY(-6px) !important;
  }
</style>

@endsection
