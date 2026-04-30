@extends('layouts.professor')

@section('title', 'Activity Logs - Professor')
@section('header', 'My Activity Logs')
@section('subheader', 'Track your recent system activities')

@section('content')
<div class="content">
    <div style="display:grid;gap:8px;">
        @forelse($logs as $log)
            <div class="card" style="margin-bottom:0;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                    <span class="badge bp" style="font-size:9px;">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                    <span style="font-size:9px;color:var(--text3);">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @if($log->description)
                    <div style="font-size:11px;color:var(--text);margin-bottom:8px;line-height:1.4;">{{ $log->description }}</div>
                @endif
                <div style="display:flex;gap:12px;font-size:9px;color:var(--text3);padding-top:8px;border-top:1px solid var(--border2);">
                    @if($log->ip_address)
                        <span class="td-mono">IP: {{ $log->ip_address }}</span>
                    @endif
                    <span class="td-mono">{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:40px;color:var(--text2);">
                <div style="font-size:24px;margin-bottom:8px;">📋</div>
                <div style="font-size:12px;">No activity logs yet</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div style="display:flex;justify-content:center;margin-top:18px;">
            <div class="pag" style="width:100%;max-width:400px;border-top:1px solid var(--border);border-radius:0;padding:12px;">
                <span style="font-size:10px;color:var(--text2);">Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }}</span>
                <div class="pag-btns">
                    @if($logs->onFirstPage())
                        <button class="pb" disabled style="opacity:0.5;cursor:not-allowed;">←</button>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" class="pb">←</a>
                    @endif
                    
                    @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                        @if ($page == $logs->currentPage())
                            <button class="pb active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="pb">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" class="pb">→</a>
                    @else
                        <button class="pb" disabled style="opacity:0.5;cursor:not-allowed;">→</button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
