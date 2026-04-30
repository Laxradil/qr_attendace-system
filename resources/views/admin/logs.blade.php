@extends('layouts.admin')

@section('title', 'System Logs')
@section('header', 'System Logs')
@section('subheader', 'View and monitor all system activities and events.')

@section('content')
<div class="tbl-wrap">
    <table>
        <thead><tr><th>Date & Time</th><th>User</th><th>Action</th><th>Description</th><th>IP Address</th></tr></thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="td-mono">{{ $log->created_at?->format('M d, Y h:i:s A') }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <div class="log-av" style="width:22px;height:22px;font-size:9px;">{{ strtoupper(substr($log->user->name ?? 'SY', 0, 2)) }}</div>
                            <div>
                                <div style="font-size:10px;font-weight:500;">{{ $log->user->name ?? 'System' }}</div>
                                <div style="font-size:9px;color:var(--text3);">{{ $log->user->role ?? 'system' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $action = strtolower($log->action ?? 'event');
                            $badge = str_contains($action, 'delete') ? 'br' : (str_contains($action, 'update') ? 'ba' : (str_contains($action, 'create') || str_contains($action, 'generate') ? 'bg' : 'bb'));
                        @endphp
                        <span class="badge {{ $badge }}">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td style="font-size:10px;">{{ $log->description ?: '-' }}</td>
                    <td class="td-mono" style="font-size:9px;">{{ $log->ip_address ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text2);">No activity logs yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs</span><div>{{ $logs->links() }}</div></div>
</div>
@endsection
