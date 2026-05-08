@extends('layouts.professor')

@section('title', 'Activity Logs - Professor')
@section('header', 'My Activity Logs')
@section('subheader', 'Track your recent system activities')

@section('content')
<div style="display:grid;gap:12px;max-width:900px">
  @forelse($logs as $log)
    <div class="glass" style="border-radius:var(--radius-lg);padding:16px;transition:.3s ease">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;gap:12px">
        <div>
          <span class="activity-badge" style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase">
            {{ str_replace('_', ' ', $log->action ?? 'Other') }}
          </span>
        </div>
        <span style="font-size:11px;color:var(--muted);white-space:nowrap">
          {{ $log->created_at->diffForHumans() ?? 'N/A' }}
        </span>
      </div>
      
      @if($log->description)
        <div style="font-size:13px;color:var(--text);margin-bottom:8px;line-height:1.5">
          {{ $log->description }}
        </div>
      @endif
      
      <div style="display:flex;gap:12px;font-size:11px;color:var(--muted);padding-top:8px;border-top:1px solid rgba(255,255,255,.07)">
        @if($log->ip_address)
          <span style="font-family:var(--mono)">IP: {{ $log->ip_address }}</span>
        @endif
        <span style="font-family:var(--mono)">{{ $log->created_at->format('M d, Y H:i:s') ?? 'N/A' }}</span>
      </div>
    </div>
  @empty
    <div class="glass" style="border-radius:var(--radius-lg);padding:40px;text-align:center">
      <div style="font-size:48px;margin-bottom:12px">📋</div>
      <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:4px">No Activity Logs Yet</div>
      <div style="font-size:13px;color:var(--muted)">Your activity logs will appear here as you interact with the system.</div>
    </div>
  @endforelse
</div>

<!-- Pagination -->
@if($logs && method_exists($logs, 'hasPages') && $logs->hasPages())
  <div style="display:flex;justify-content:center;margin-top:24px;gap:8px">
    @if($logs->onFirstPage())
      <button class="pag-btn" disabled style="opacity:0.5;cursor:not-allowed">←</button>
    @else
      <a href="{{ $logs->previousPageUrl() }}" class="pag-btn">←</a>
    @endif
    
    @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
      @if($page == $logs->currentPage())
        <button class="pag-btn active">{{ $page }}</button>
      @else
        <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
      @endif
    @endforeach
    
    @if($logs->hasMorePages())
      <a href="{{ $logs->nextPageUrl() }}" class="pag-btn">→</a>
    @else
      <button class="pag-btn" disabled style="opacity:0.5;cursor:not-allowed">→</button>
    @endif
  </div>
@endif

<style>
  .activity-badge {
    padding: 4px 8px;
    border-radius: 6px;
    background: rgba(139,92,255,.15);
    color: rgba(200,180,255,.9);
    border: 1px solid rgba(139,92,255,.25);
    display: inline-block;
  }
  
  .pag-btn {
    padding: 9px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    color: var(--text);
    font-size: 13px;
    cursor: pointer;
    transition: .2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
  }
  
  .pag-btn:hover:not(:disabled) {
    background: rgba(255,255,255,.13);
    border-color: rgba(255,255,255,.24);
    transform: translateY(-2px);
  }
  
  .pag-btn.active {
    background: linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));
    border-color: rgba(139,92,255,.5);
    color: #fff;
  }
</style>
@endsection
