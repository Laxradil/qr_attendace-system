@extends('layouts.professor')

@section('title', 'Activity Logs - Professor')
@section('header', 'Activity Logs')
@section('subheader', 'Monitor your recent system activities and events.')

@section('content')
<style>
    .search-bar{display:none !important;}
    
    .logs-toolbar{display:flex;gap:12px;margin-bottom:18px;flex-wrap:wrap;align-items:center}
    .filter-chips{display:flex;gap:10px;flex-wrap:wrap}
    .chip{
        display:inline-flex;
        align-items:center;
        gap:8px;
        border:1px solid rgba(139,92,255,.45);
        background:rgba(139,92,255,.08);
        color:#0f172a;
        border-radius:999px;
        padding:10px 18px;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
        transition:transform .2s ease,background .2s ease,border-color .2s ease,box-shadow .2s ease,color .2s ease;
        font-family:var(--font);
        backdrop-filter: blur(10px);
    }
    .chip:hover{
        transform:translateY(-1px);
        background:rgba(139,92,255,.16);
        border-color:rgba(139,92,255,.7);
    }
    .chip.active{
        background:linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.9));
        border-color:transparent;
        box-shadow:0 18px 40px rgba(80,94,255,.2);
        color:#ffffff;
    }
    .logs-actions{display:flex;gap:9px;margin-left:auto;flex-wrap:wrap}
    .action-btn{
        display:inline-flex;align-items:center;gap:6px;border:1px solid rgba(255,255,255,.15);
        background:rgba(255,255,255,.08);color:#fff;border-radius:13px;padding:9px 14px;
        font-weight:700;cursor:pointer;transition:.2s ease;font-size:13px;font-family:var(--font);
    }
    .action-btn:hover{transform:translateY(-2px);background:rgba(255,255,255,.13);border-color:rgba(255,255,255,.24)}
    .search-bar{display:flex !important;}
    
    .logs-table-wrap{
        background:rgba(255,255,255,.055);border:1px solid rgba(255,255,255,.10);
        border-radius:22px;overflow:hidden;margin-bottom:14px;
    }
    .logs-table-wrap table{width:100%;border-collapse:collapse}
    .logs-table-wrap th{
        background:rgba(255,255,255,.05);color:var(--faint);font-size:11px;letter-spacing:.12em;
        text-transform:uppercase;font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px);
        padding:14px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle;
    }
    .logs-table-wrap td{
        padding:14px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);
        vertical-align:middle;color:#e8eeff;font-size:13.5px;
    }
    .logs-table-wrap tbody tr:hover td{background:rgba(255,255,255,.028)}
    .logs-table-wrap tr:last-child td{border-bottom:0}
    
    .log-user-cell{display:flex;align-items:center;gap:10px;font-weight:700}
    .log-avatar{
        width:34px;height:34px;border-radius:11px;display:grid;place-items:center;
        background:linear-gradient(145deg,rgba(139,92,255,.36),rgba(67,166,255,.2));
        font-size:11px;font-weight:900;border:1px solid rgba(139,92,255,.3);flex-shrink:0;
    }
    .log-action-badge{
        display:inline-flex;align-items:center;gap:6px;border-radius:8px;padding:6px 10px;
        font-size:11px;font-weight:700;white-space:nowrap;letter-spacing:.01em;border:1px solid transparent;
    }
    .log-action-badge.add-student{background:rgba(24,240,139,.12);color:#4dffa0;border-color:rgba(24,240,139,.2)}
    .log-action-badge.update-user{background:rgba(255,199,90,.12);color:#ffc75a;border-color:rgba(255,199,90,.22)}
    .log-action-badge.scan-qr{background:rgba(67,166,255,.12);color:#43a6ff;border-color:rgba(67,166,255,.2)}
    .log-action-badge.attendance-record{background:rgba(139,92,255,.12);color:#b9c4ff;border-color:rgba(139,92,255,.2)}
    .log-action-badge.other{background:rgba(255,255,255,.055);color:var(--muted);border-color:rgba(255,255,255,.1)}
    
    .logs-footer{display:flex;justify-content:space-between;align-items:center;color:var(--muted);font-size:12.5px}
    .pager{display:flex;gap:6px}
    .pager button{
        width:34px;height:34px;border:1px solid rgba(255,255,255,.14);border-radius:11px;
        background:rgba(255,255,255,.07);color:#fff;cursor:pointer;font-family:var(--mono);
        font-weight:700;transition:.2s ease;font-size:12px;
    }
    .pager button:hover{background:rgba(255,255,255,.13)}
    .pager .current{background:linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.55));border-color:transparent}
    
    .td-mono{font-family:var(--mono);font-size:12px;color:var(--muted)}
    .log-desc{font-size:13px;color:#e8eeff}
    .log-desc.muted{color:var(--muted)}
</style>

<style>
  /* Light mode styles */
  body.theme-light .chip {
    background: rgba(139,92,255,.08);
    border-color: rgba(139,92,255,.3);
    color: #0f172a;
  }
  
  body.theme-light .chip:hover {
    background: rgba(139,92,255,.14);
    border-color: rgba(139,92,255,.5);
  }
  
  body.theme-light .chip.active {
    background: linear-gradient(135deg,#7c3aed 0%,#2563eb 100%) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 18px 40px rgba(67,46,255,.18) !important;
  }
  
  body.theme-light .action-btn {
    background: rgba(15,23,42,.08);
    border-color: rgba(15,23,42,.15);
    color: #0f172a;
  }
  
  body.theme-light .action-btn:hover {
    background: #f1f5f9;
  }
  
  body.theme-light .logs-table-wrap {
    background: rgba(15,23,42,.04);
    border-color: rgba(15,23,42,.08);
  }
  
  body.theme-light .logs-table-wrap th {
    background: rgba(15,23,42,.08);
    color: #475569;
    border-bottom-color: rgba(15,23,42,.12);
  }
  
  body.theme-light .logs-table-wrap td {
    color: #0f172a;
    border-bottom-color: rgba(15,23,42,.08);
  }
  
  body.theme-light .logs-table-wrap tbody tr:hover td {
    background: rgba(15,23,42,.04);
  }
  
  body.theme-light .log-desc {
    color: #0f172a;
  }
  
  body.theme-light .log-desc.muted {
    color: #475569;
  }
  
  body.theme-light .pager button {
    background: rgba(15,23,42,.08);
    border-color: rgba(15,23,42,.15);
    color: #0f172a;
  }
  
  body.theme-light .pager button:hover {
    background: rgba(15,23,42,.12);
  }
  
  body.theme-light .pager .current {
    background: linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.55));
    border-color: transparent;
    color: #ffffff;
  }
  
  body.theme-light .search-bar {
    background: rgba(15,23,42,.08) !important;
    border-color: rgba(15,23,42,.15) !important;
    color: #0f172a !important;
  }
  
  body.theme-light .search-bar::placeholder {
    color: #64748b !important;
  }
</style>

<div class="logs-toolbar">
    <div class="filter-chips">
        <div class="chip active" onclick="filterLogs(this, 'all')">⊙ All Actions</div>
        <div class="chip" onclick="filterLogs(this, 'add_student')">➕ Add Student</div>
        <div class="chip" onclick="filterLogs(this, 'update_user')">🔄 Update User</div>
        <div class="chip" onclick="filterLogs(this, 'scan_qr')">▦ Scan QR</div>
    </div>
    <div style="display:flex;gap:9px;margin-left:auto;flex-wrap:wrap;align-items:center;flex:1;justify-content:flex-end">
        <input type="text" id="logsSearchInput" placeholder="Search logs..." onkeyup="searchLogs()" style="padding:9px 14px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:#fff;border-radius:13px;font-size:13px;font-family:var(--font);width:200px;outline:none;transition:.2s ease;" class="search-bar">
        <div class="logs-actions">
            <button class="action-btn" onclick="location.reload()">🔄 Refresh</button>
            <button class="action-btn" onclick="exportLogs()">⬇ Export</button>
        </div>
    </div>
</div>

<div class="logs-table-wrap">
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
        <tbody id="logsTableBody">
            @forelse($logs as $log)
                <tr data-action="{{ strtolower($log->action ?? '') }}">
                    <td class="td-mono">{{ $log->created_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y h:i:s A') }}</td>
                    <td>
                        <div class="log-user-cell">
                            <div class="log-avatar">{{ strtoupper(substr($log->user->name ?? 'SY', 0, 2)) }}</div>
                            <div>
                                <div style="font-size:13px;font-weight:700;">{{ $log->user->name ?? 'System' }}</div>
                                <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ $log->user->role ?? 'system' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $actionLower = strtolower($log->action ?? 'event');
                            if (str_contains($actionLower, 'student')) $badgeClass = 'add-student';
                            elseif (str_contains($actionLower, 'update')) $badgeClass = 'update-user';
                            elseif (str_contains($actionLower, 'scan')) $badgeClass = 'scan-qr';
                            elseif (str_contains($actionLower, 'attendance')) $badgeClass = 'attendance-record';
                            else $badgeClass = 'other';
                        @endphp
                        <span class="log-action-badge {{ $badgeClass }}">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td class="log-desc">{{ $log->description ?: '-' }}</td>
                    <td class="td-mono">{{ $log->ip_address ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--muted);">
                        <div style="font-size:14px;font-weight:500;">No activity logs yet</div>
                        <div style="font-size:12px;margin-top:4px;">Your activities will appear here</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="logs-footer">
    <span>Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs</span>
    <div class="pager">
        {{ $logs->links() }}
    </div>
</div>

<script>
    let currentFilter = 'all';
    
    function filterLogs(chip, action) {
        currentFilter = action;
        const chips = document.querySelectorAll('.chip');
        chips.forEach(ch => ch.classList.remove('active'));
        chip.classList.add('active');
        
        applyFiltersAndSearch();
    }
    
    function searchLogs() {
        applyFiltersAndSearch();
    }
    
    function applyFiltersAndSearch() {
        const searchInput = document.getElementById('logsSearchInput');
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const rows = document.querySelectorAll('#logsTableBody tr');
        
        rows.forEach(row => {
            const rowAction = row.dataset.action;
            const rowText = row.textContent.toLowerCase();
            
            // Apply action filter
            let actionMatch = true;
            if (currentFilter !== 'all') {
                // Use same logic as PHP badge determination
                if (currentFilter === 'add_student') {
                    actionMatch = rowAction.includes('student');
                } else if (currentFilter === 'update_user') {
                    actionMatch = rowAction.includes('update');
                } else if (currentFilter === 'scan_qr') {
                    actionMatch = rowAction.includes('scan');
                } else {
                    actionMatch = rowAction.includes(currentFilter.toLowerCase());
                }
            }
            
            // Apply search filter
            let searchMatch = true;
            if (searchTerm.length > 0) {
                searchMatch = rowText.includes(searchTerm);
            }
            
            row.style.display = (actionMatch && searchMatch) ? '' : 'none';
        });
    }
    
    function exportLogs() {
        const rows = document.querySelectorAll('#logsTableBody tr:not([style*="display: none"])');
        let csv = 'Date & Time,User,Action,Description,IP Address\n';
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length === 5) {
                csv += `"${cells[0].textContent.trim()}","${cells[1].textContent.trim()}","${cells[2].textContent.trim()}","${cells[3].textContent.trim()}","${cells[4].textContent.trim()}"\n`;
            }
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `activity-logs-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }
</script>

@endsection
