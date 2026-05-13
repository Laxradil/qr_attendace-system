@extends('layouts.admin-new')

@section('title', 'System Logs - QR Attendance Admin')
@section('pageTitle', 'System Logs')
@section('pageSubtitle', 'Monitor all system activities and events.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar">
    <div class="tools">
      <button class="btn active" style="background:rgba(139,92,255,.25);border-color:rgba(139,92,255,.4)">☰ All Actions</button>
      <span class="chip">Add Student</span>
      <span class="chip">Update User</span>
      <span class="chip">Scan QR</span>
    </div>
    <div class="tools">
      <button class="btn" onclick="location.reload()">⟳ Refresh</button>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date & Time</th>
          <th>User</th>
          <th>Action</th>
          <th>Description</th>
          <th>IP Address</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
        <tr>
          <td>
            <div style="font-family:var(--mono);font-size:12px">{{ $log->created_at->format('M d, Y') }}</div>
            <div class="muted" style="font-size:12px">{{ $log->created_at->format('h:i:s A') }}</div>
          </td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($log->user->name, 0, 2)) }}</span>
              <div>
                <div>{{ $log->user->name }}</div>
                <div class="muted" style="font-size:12px">{{ $log->user->role }}</div>
              </div>
            </div>
          </td>
          <td>
            <span class="pill @if(strpos($log->action, 'create') !== false) blue @elseif(strpos($log->action, 'update') !== false) yellow @elseif(strpos($log->action, 'delete') !== false) red @elseif(strpos($log->action, 'scan') !== false) green @else purple @endif" style="font-family:var(--mono);font-size:11px">
              {{ strtoupper(str_replace('_', ' ', $log->action)) }}
            </span>
          </td>
          <td style="max-width:320px;color:var(--muted)">{{ $log->description }}</td>
          <td><span style="font-family:var(--mono);font-size:12px;color:var(--faint)">{{ $log->ip_address ?? '127.0.0.1' }}</span></td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No logs found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

<style>
  body.theme-light .glass-table {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .btn.active {
    background: #e0e7ff !important;
    border-color: #c7d2fe !important;
    color: #3730a3 !important;
  }
  
  body.theme-light .chip {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light th {
    background: #f9fafb !important;
    color: #374151 !important;
    border-bottom: 1px solid #e5e7eb !important;
  }
  
  body.theme-light td {
    color: #000000 !important;
    border-bottom: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .muted {
    color: #6b7280 !important;
  }
  
  body.theme-light .small-avatar {
    background: #e5e7eb !important;
    border: 1px solid #d1d5db !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill {
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill.blue {
    background: #eff6ff !important;
    border-color: #dbeafe !important;
    color: #1d4ed8 !important;
  }
  
  body.theme-light .pill.yellow {
    background: #fffbeb !important;
    border-color: #fde68a !important;
    color: #92400e !important;
  }
  
  body.theme-light .pill.red {
    background: #fef2f2 !important;
    border-color: #fecaca !important;
    color: #dc2626 !important;
  }
  
  body.theme-light .pill.green {
    background: #ecfdf5 !important;
    border-color: #d1fae5 !important;
    color: #065f46 !important;
  }
  
  body.theme-light .pill.purple {
    background: #faf5ff !important;
    border-color: #f3e8ff !important;
    color: #7c3aed !important;
  }
  
  body.theme-light .btn {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
</style>
