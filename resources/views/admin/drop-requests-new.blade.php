@extends('layouts.admin-new')

@section('title', 'Drop Requests - QR Attendance Admin')
@section('pageTitle', 'Drop Requests')
@section('pageSubtitle', 'Review and approve or reject student drop requests.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:12px">
      <h3 style="font-size:16px;font-weight:800;margin:0">📋 Drop Requests</h3>
      <span class="pill yellow">{{ $dropRequests->where('status', 'pending')->count() }} Pending</span>
    </div>
    <input type="text" id="tableSearch" placeholder="Search table..." style="flex:1;min-width:200px;max-width:350px;padding:10px 14px;border-radius:var(--radius-md);border:1px solid rgba(255,255,255,.12);background:rgba(8,12,30,.58);color:#fff;font-size:13px" onkeyup="filterTable(this)">
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Class</th>
          <th>Student</th>
          <th>Professor</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Submitted</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($dropRequests as $drop)
        <tr>
          <td><span style="font-family:var(--mono)">{{ $drop->id }}</span></td>
          <td><b>{{ $drop->classe->code }} — {{ $drop->classe->name }}</b></td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($drop->student->name, 0, 2)) }}</span>
              <div>
                <div>{{ $drop->student->name }}</div>
                <div class="muted" style="font-size:12px">{{ $drop->student->email }}</div>
              </div>
            </div>
          </td>
          <td>{{ $drop->classe->professor->name ?? 'N/A' }}</td>
          <td>{{ $drop->reason ?? 'Not provided' }}</td>
          <td>
            <span class="pill @if($drop->status == 'pending') yellow @elseif($drop->status == 'approved') green @else red @endif">
              {{ ucfirst($drop->status) }}
            </span>
          </td>
          <td>
            <div>{{ $drop->created_at->format('M d, Y') }}</div>
            <div class="muted" style="font-size:12px">{{ $drop->created_at->format('h:i A') }}</div>
          </td>
          <td>
            @if($drop->status == 'pending')
              <div class="action-button-group">
                <form method="POST" action="{{ route('admin.drop-requests.approve', $drop) }}">
                  @csrf
                  <button type="submit" class="btn primary slim">Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.drop-requests.reject', $drop) }}">
                  @csrf
                  <button type="submit" class="btn danger slim">Reject</button>
                </form>
              </div>
            @else
              <span class="muted" style="font-size:13px">No action available</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">No drop requests found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
function filterTable(input) {
  const searchValue = input.value.toLowerCase();
  const table = input.closest('.glass-table').querySelector('table');
  const rows = table.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    if (row.querySelector('td[colspan]')) return;
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
  });
}
</script>

<style>
  body.theme-light .glass-table {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light #tableSearch {
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

  .action-button-group {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: nowrap;
    white-space: nowrap;
  }

  .action-button-group form {
    display: inline-flex;
    margin: 0;
  }

  .action-button-group .btn {
    min-width: 95px;
    width: auto;
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
  
  body.theme-light .pill.yellow {
    background: #fffbeb !important;
    border-color: #fde68a !important;
    color: #92400e !important;
  }
  
  body.theme-light .pill.green {
    background: #ecfdf5 !important;
    border-color: #d1fae5 !important;
    color: #065f46 !important;
  }
  
  body.theme-light .pill.red {
    background: #fef2f2 !important;
    border-color: #fecaca !important;
    color: #dc2626 !important;
  }
  
  body.theme-light .btn {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .btn.primary {
    background: #3b82f6 !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
  }
  
  body.theme-light .btn.danger {
    background: #ef4444 !important;
    border-color: #dc2626 !important;
    color: #ffffff !important;
  }
</style>

@endsection
