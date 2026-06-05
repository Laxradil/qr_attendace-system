@extends('layouts.admin-new')

@section('title', 'System Logs - QR Attendance Admin')
@section('pageTitle', 'System Logs')
@section('pageSubtitle', 'Monitor all system activities and events.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar">
    <div class="tools">
      <button class="btn active" style="background:rgba(139,92,255,.25);border-color:rgba(139,92,255,.4)" onclick="filterLogs(this, 'all')">☰ All Actions</button>
      <span class="chip" onclick="filterLogs(this, 'add_student')">Add Student</span>
      <span class="chip" onclick="filterLogs(this, 'update_user')">Update User</span>
      <span class="chip" onclick="filterLogs(this, 'scan_qr')">Scan QR</span>
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
        <tr data-action="{{ strtolower($log->action ?? '') }}">
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
  body.theme-dark .glass-table {
    background: rgba(255,255,255,.04) !important;
    border: 1px solid rgba(255,255,255,.08) !important;
    color: #f0f4ff !important;
  }

  body.theme-dark .toolbar .btn,
  body.theme-dark .toolbar .chip {
    background: rgba(255,255,255,.08) !important;
    border: 1px solid rgba(255,255,255,.14) !important;
    color: #f8fafc !important;
    text-shadow: 0 1px 0 rgba(0,0,0,.24);
  }

  body.theme-dark .toolbar .btn *,
  body.theme-dark .toolbar .chip * {
    color: inherit !important;
  }

  body.theme-dark .toolbar .btn.active {
    background: linear-gradient(135deg, rgba(139,92,255,.95), rgba(67,166,255,.6)) !important;
    color: #ffffff !important;
    border-color: transparent !important;
  }

  body.theme-dark th {
    background: rgba(255,255,255,.08) !important;
    color: #f8fafc !important;
    border-bottom: 1px solid rgba(255,255,255,.08) !important;
  }

  body.theme-dark td {
    color: #f8fafc !important;
    border-bottom: 1px solid rgba(255,255,255,.06) !important;
  }

  body.theme-dark .muted {
    color: #bac4e6 !important;
  }

  body.theme-dark .small-avatar {
    background: linear-gradient(145deg, rgba(139,92,255,.36), rgba(67,166,255,.22)) !important;
    border: 1px solid rgba(139,92,255,.35) !important;
    color: #ffffff !important;
  }

  body.theme-dark .pill {
    border: 1px solid rgba(255,255,255,.14) !important;
    color: #f8fafc !important;
  }

  body.theme-dark .user-cell,
  body.theme-dark .user-cell strong,
  body.theme-dark .user-cell div {
    color: #f8fafc !important;
  }

  body.theme-dark .table-wrap tbody td > div,
  body.theme-dark .table-wrap tbody td span,
  body.theme-dark .table-wrap tbody td strong {
    color: inherit !important;
  }

  body.theme-dark .pill.blue { background: rgba(37,99,235,.18) !important; color: #93c5fd !important; }
  body.theme-dark .pill.yellow { background: rgba(202,138,4,.18) !important; color: #fcd34d !important; }
  body.theme-dark .pill.red { background: rgba(220,38,38,.18) !important; color: #fca5a5 !important; }
  body.theme-dark .pill.green { background: rgba(22,163,74,.18) !important; color: #86efac !important; }
  body.theme-dark .pill.purple { background: rgba(124,58,237,.18) !important; color: #c4b5fd !important; }

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

  body.theme-dark .table-wrap tbody tr:hover td {
    background: rgba(255,255,255,.03) !important;
  }
</style>

<script>
  let currentLogFilter = 'all';

  function filterLogs(element, action) {
    currentLogFilter = action;

    const buttons = document.querySelectorAll('.toolbar .tools .btn, .toolbar .tools .chip');
    buttons.forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    document.querySelectorAll('.table-wrap tbody tr').forEach(row => {
      const rowAction = (row.dataset.action || '').toLowerCase();
      if (action === 'all' || (action === 'add_student' && rowAction.includes('student')) ||
          (action === 'update_user' && rowAction.includes('update')) ||
          (action === 'scan_qr' && rowAction.includes('scan'))) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }
</script>
