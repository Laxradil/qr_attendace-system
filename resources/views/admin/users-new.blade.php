@extends('layouts.admin-new')

@section('title', 'Users - QR Attendance Admin')
@section('pageTitle', 'Users')
@section('pageSubtitle', 'Manage all system users and their access.')

@section('content')
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
@endsection
