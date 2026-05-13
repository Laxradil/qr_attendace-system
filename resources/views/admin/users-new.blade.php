@extends('layouts.admin-new')

@section('title', 'Users - QR Attendance Admin')
@section('pageTitle', 'Users')
@section('pageSubtitle', 'Manage all system users and their access.')

@section('content')

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
@endsection
