@extends('layouts.admin-new')

@section('title', 'Users - QR Attendance Admin')
@section('pageTitle', 'Users')
@section('pageSubtitle', 'Manage all system users and their access.')

@section('content')
<<<<<<< HEAD
<style>
    .users-panel{
        border-radius:var(--radius-lg);
        padding:16px 16px 12px;
        border:1px solid rgba(255,255,255,.12);
        background:rgba(8,12,30,.58);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.08), 0 20px 45px rgba(0,0,0,.22);
    }

    .users-toolbar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:14px;
    }

    .users-toolbar .tools{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    .users-search{
        width:250px;
        height:40px;
        padding:0 14px;
        display:flex;
        align-items:center;
        gap:9px;
        border-radius:999px;
        border:1px solid rgba(255,255,255,.14);
        background:rgba(255,255,255,.06);
        color:var(--muted);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
        font-size:13px;
    }

    .users-search span{white-space:nowrap}

    .users-table-shell{
        border-radius:var(--radius-lg);
        overflow:hidden;
        border:1px solid rgba(255,255,255,.12);
        background:rgba(8,12,30,.46);
    }

    .users-table-shell table{
        min-width:860px;
    }

    .users-table-shell th{
        background:rgba(255,255,255,.05);
    }

    .users-table-shell td,
    .users-table-shell th{
        border-bottom:1px solid rgba(255,255,255,.06);
    }

    .users-table-shell tr:hover td{
        background:rgba(255,255,255,.022);
    }

    .users-table-shell .footer-bar{
        margin-top:14px;
        padding-top:2px;
    }

    @media (max-width: 760px){
        .users-panel{padding:14px 12px 10px}
        .users-search{width:100%}
    }
</style>

<div class="stats">
    <div class="stat glass">
      <div class="stat-icon blue">👥</div>
      <div class="stat-body">
            <strong>{{ $users->count() }}</strong>
        <span>Total Users</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">🛡</div>
      <div class="stat-body">
            <strong>{{ App\Models\User::where('role', 'admin')->count() }}</strong>
        <span>Administrators</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">🎓</div>
      <div class="stat-body">
            <strong>{{ App\Models\User::where('role', 'professor')->count() }}</strong>
        <span>Professors</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">🧑‍🎓</div>
      <div class="stat-body">
            <strong>{{ App\Models\User::where('role', 'student')->count() }}</strong>
        <span>Students</span>
      </div>
    </div>
</div>

<div class="users-panel" style="margin-top:16px">
    <div class="users-toolbar">
      <div class="tools">
        <div class="users-search">🔍 <span>Search users...</span></div>
        <button class="btn">☰ Filter</button>
      </div>
    </div>

    <div class="users-table-shell">
      <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="td-mono">USR-{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="user-cell">
                                <span class="small-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="muted">{{ $user->email }}</td>
                        <td>
                            <span class="pill {{ $user->role === 'admin' ? 'red' : ($user->role === 'professor' ? 'purple' : 'blue') }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            <span class="pill {{ $user->is_active ? 'green' : 'yellow' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="td-mono">{{ $user->created_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                        <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                            <a class="btn slim" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger slim">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--muted);">No users found. @if($filters['role'] || $filters['status'])<br><small>Try adjusting your filters.</small>@endif</td></tr>
                @endforelse
            </tbody>
        </table>
      </div>
      <div class="footer-bar">
          <span>Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
          <div class="pager">{{ $users->appends(request()->query())->links() }}</div>
      </div>
    </div>
</div>
=======

<div class="card glass" style="margin-bottom:16px">
  <div class="mini-grid">
    <div class="mini">
      <div class="mini-icon stat-icon blue" style="width:38px;height:38px;border-radius:12px;font-size:16px">👥</div>
      <div><b>{{ $users->count() }}</b><small>Total Users</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon red" style="width:38px;height:38px;border-radius:12px;font-size:16px">🛡</div>
      <div><b>{{ App\Models\User::where('role', 'admin')->count() }}</b><small>Administrators</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon yellow" style="width:38px;height:38px;border-radius:12px;font-size:16px">🎓</div>
      <div><b>{{ App\Models\User::where('role', 'professor')->count() }}</b><small>Professors</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon purple" style="width:38px;height:38px;border-radius:12px;font-size:16px">🧑‍🎓</div>
      <div><b>{{ App\Models\User::where('role', 'student')->count() }}</b><small>Students</small></div>
    </div>
  </div>
</div>

<div class="glass-table glass">
  <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px">
    <a href="{{ route('admin.users.create') }}" class="btn primary">＋ Add User</a>
    <input type="text" id="tableSearch" placeholder="Search table..." style="flex:1;min-width:200px;max-width:350px;padding:10px 14px;border-radius:var(--radius-md);border:1px solid rgba(255,255,255,.12);background:rgba(8,12,30,.58);color:#fff;font-size:13px" onkeyup="filterTable(this)">
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Date Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td><span style="font-family:var(--mono);font-size:12px;color:var(--muted)">USR-{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</span></td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
              {{ $user->name }}
            </div>
          </td>
          <td class="muted">{{ $user->email }}</td>
          <td><span class="pill {{ $user->role === 'admin' ? 'red' : ($user->role === 'professor' ? 'purple' : 'blue') }}">{{ ucfirst($user->role) }}</span></td>
          <td><span class="pill {{ $user->is_active ? 'green' : 'yellow' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
          <td class="muted">{{ $user->created_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
          <td>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn slim">Edit</a>
            <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display:inline" onsubmit="return confirm('Delete this user?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn danger slim">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">No users found</td>
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
  body.theme-light .glass,
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
  
  body.theme-light .small-avatar {
    background: #e5e7eb !important;
    border: 1px solid #d1d5db !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill {
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
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
  
  body.theme-light .pill.yellow {
    background: #fffbeb !important;
    border-color: #fde68a !important;
    color: #92400e !important;
  }
  
  body.theme-light .pill.blue {
    background: #eff6ff !important;
    border-color: #dbeafe !important;
    color: #1d4ed8 !important;
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
>>>>>>> origin/branch-ni-kirb
@endsection
