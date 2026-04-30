@extends('layouts.admin')

@section('title', 'Users')
@section('header', 'Users')
@section('subheader', 'Manage all system users and their access.')

@section('content')
<div class="stats stats-4" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $users->total() }}</div><div class="stat-label">Total Users</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val">{{ \App\Models\User::where('role', 'admin')->count() }}</div><div class="stat-label">Administrators</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val">{{ \App\Models\User::where('role', 'professor')->count() }}</div><div class="stat-label">Professors</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val">{{ \App\Models\User::where('role', 'student')->count() }}</div><div class="stat-label">Students</div></div></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:12px;align-items:center;">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-p">+ Add User</a>
</div>

<div class="tbl-wrap">
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
                        <div style="display:flex;align-items:center;gap:7px;">
                            <div class="log-av">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <span style="font-weight:500;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:10px;color:var(--text2);">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'br' : ($user->role === 'professor' ? 'bp' : 'bx') }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'bg' : 'ba' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="td-mono">{{ $user->created_at?->format('M d, Y') }}</td>
                    <td style="display:flex;gap:4px;">
                        <a class="btn btn-sm" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-d">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text2);">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag">
        <span>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
        <div>{{ $users->links() }}</div>
    </div>
</div>
@endsection
