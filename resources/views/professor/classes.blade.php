{{-- @var \Illuminate\Support\Collection $classes --}}
{{-- @var int $totalStudents --}}
{{-- @var \Illuminate\Support\Collection $availableStudents --}}
@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<style>
  .search-bar {
    display: none !important;
  }
</style>
<!-- Overview stats -->
<div class="stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px;margin-top:6px">
  <div class="stat glass">
    <div class="stat-icon blue">▤</div>
    <div class="stat-body">
      <strong>{{ $classes->count() }}</strong>
      <span>Classes Overview</span>
      <div style="font-size:12px;color:var(--muted);margin-top:2px">Total classes currently assigned.</div>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon green">🧑‍🎓</div>
    <div class="stat-body">
      <strong>{{ $totalStudents }}</strong>
      <span>Total Students</span>
      <div style="font-size:12px;color:var(--muted);margin-top:2px">Students across all your classes.</div>
    </div>
  </div>
  <div class="stat glass">
    <div class="stat-icon yellow">✓</div>
    <div class="stat-body">
      <strong>{{ $classes->count() }}</strong>
      <span>Active Classes</span>
      <div style="font-size:12px;color:var(--muted);margin-top:2px">Classes marked active right now.</div>
    </div>
  </div>
</div>

<!-- Class cards grid -->
<div class="class-grid">
  @forelse($classes as $class)
    <div class="class-card">
      <div class="class-head">
        <div>
          <h3>{{ $class->display_name ?? 'Class' }}</h3>
          <div class="class-code">{{ $class->code ?? 'N/A' }}</div>
        </div>
        <div class="class-room">{{ $class->schedules->first()?->room ?? 'TBA' }}</div>
      </div>
      <div class="class-meta">
        <div class="class-meta-row">
          <div class="meta-icon">📅</div>
          Days: <strong>{{ $class->schedules->first()?->days ?? 'N/A' }}</strong>
        </div>
        <div class="class-meta-row">
          <div class="meta-icon">🕓</div>
          Time: <strong style="font-family:var(--mono)">{{ $class->schedules->first()?->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $class->schedules->first()->start_time)->format('H:i') : 'N/A' }}</strong>
        </div>
        <div class="class-meta-row">
          <div class="meta-icon">🎓</div>
          Professor: <strong>{{ auth()->user()->name }}</strong>
        </div>
      </div>
      <div class="class-actions">
        <a href="{{ route('professor.class-detail', $class->id) }}" class="view-link">View class →</a>
        <div style="flex:1"></div>
        <button class="btn slim" onclick="window.location.href='{{ route('professor.class-detail', $class->id) }}'">Details</button>
      </div>
    </div>
  @empty
    <div style="grid-column:1/-1;padding:40px;text-align:center;color:var(--muted)">
      <div style="font-size:48px;margin-bottom:12px">📚</div>
      <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:4px">No Classes Yet</div>
      <div style="font-size:13px">You haven't been assigned any classes yet.</div>
    </div>
  @endforelse
</div>

<style>
  .toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  
  .tools {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
  }
  
  .chip {
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.065);
    color: #dde5ff;
    border-radius: 12px;
    padding: 9px 12px;
    font-weight: 700;
    font-size: 12.5px;
    cursor: pointer;
    transition: .2s ease;
    font-family: var(--font);
  }
  
  .chip:hover {
    background: rgba(255,255,255,.1);
  }
  
  .chip.active {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.44));
    border-color: transparent;
  }
  
  .class-grid {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 14px;
    margin-top: 4px;
  }
  
  .class-card {
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: .25s ease;
    border: 1px solid var(--stroke);
    background: linear-gradient(135deg,rgba(255,255,255,.12),rgba(255,255,255,.04) 40%,rgba(255,255,255,.08));
    backdrop-filter: var(--blur);
    -webkit-backdrop-filter: var(--blur);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25), var(--shadow);
    position: relative;
    overflow: hidden;
  }
  
  .class-card::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg,transparent,rgba(255,255,255,.55) 50%,transparent);
    pointer-events: none;
  }
  
  .class-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255,255,255,.3);
  }
  
  .class-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
  }
  
  .class-head h3 {
    font-size: 16px;
    font-weight: 800;
    letter-spacing: -.03em;
    line-height: 1.2;
  }
  
  .class-code {
    font-size: 11px;
    color: var(--muted);
    margin-top: 3px;
    font-family: var(--mono);
  }
  
  .class-room {
    font-size: 22px;
    font-weight: 900;
    font-family: var(--mono);
    color: rgba(139,92,255,.9);
    letter-spacing: -.03em;
  }
  
  .class-meta {
    display: grid;
    gap: 7px;
    margin-bottom: 14px;
  }
  
  .class-meta-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--muted);
  }
  
  .class-meta-row strong {
    color: var(--text);
    font-weight: 600;
  }
  
  .meta-icon {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    display: grid;
    place-items: center;
    font-size: 11px;
    background: rgba(255,255,255,.08);
    flex-shrink: 0;
  }
  
  .class-actions {
    display: flex;
    gap: 8px;
    align-items: center;
  }
  
  .view-link {
    color: rgba(139,92,255,.9);
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
  }
  
  .view-link:hover {
    color: #b9c4ff;
  }
  
  .btn.slim {
    padding: 7px 10px;
    font-size: 12px;
    border-radius: 10px;
  }
  
  @media(max-width:1200px) {
    .class-grid { grid-template-columns: 1fr; }
  }
</style>

<style>
  body.theme-light .glass {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .chip {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .chip:hover {
    background: #f9fafb !important;
  }
  
  body.theme-light .chip.active {
    background: #3b82f6 !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
  }
  
  body.theme-light .class-card {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
  }
  
  body.theme-light .class-card::after {
    background: linear-gradient(90deg,transparent,#e5e7eb 50%,transparent) !important;
  }
  
  body.theme-light .class-card:hover {
    border-color: #d1d5db !important;
  }
  
  body.theme-light .class-code {
    color: #6b7280 !important;
  }
  
  body.theme-light .class-room {
    color: #000000 !important;
  }
  
  body.theme-light .class-meta-row {
    color: #6b7280 !important;
  }
  
  body.theme-light .class-meta-row strong {
    color: #000000 !important;
  }
  
  body.theme-light .meta-icon {
    background: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .view-link {
    color: #3b82f6 !important;
  }
  
  body.theme-light .view-link:hover {
    color: #2563eb !important;
  }
</style>
@endsection
