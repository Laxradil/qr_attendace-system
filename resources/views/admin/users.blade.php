@extends('layouts.admin')

@section('title', 'Users')
@section('header', 'Users')
@section('subheader', 'Manage all system users and their access.')

@section('content')
@php
    $roleFilter = $filters['role'] ?? null;
    $statusFilter = $filters['status'] ?? null;
@endphp

<div class="stats stats-4" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val">{{ $stats['total'] }}</div><div class="stat-label">Total Users</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val">{{ $stats['admins'] }}</div><div class="stat-label">Administrators</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val">{{ $stats['professors'] }}</div><div class="stat-label">Professors</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val">{{ $stats['students'] }}</div><div class="stat-label">Students</div></div></div>
</div>

<div style="display:flex;gap:12px;margin-bottom:16px;align-items:center;flex-wrap:wrap;">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-p">+ Add User</a>
    
    <!-- Filter Buttons -->
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <span style="font-size:11px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Filter by Role:</span>
        <a href="{{ route('admin.users') }}" class="filter-btn {{ !$roleFilter ? 'active role-all' : '' }}">All Roles</a>
        <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="filter-btn {{ $roleFilter === 'admin' ? 'active role-admin' : '' }}">Admin</a>
        <a href="{{ route('admin.users', ['role' => 'professor']) }}" class="filter-btn {{ $roleFilter === 'professor' ? 'active role-professor' : '' }}">Professor</a>
        <a href="{{ route('admin.users', ['role' => 'student']) }}" class="filter-btn {{ $roleFilter === 'student' ? 'active role-student' : '' }}">Student</a>
    </div>

    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <span style="font-size:11px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Filter by Status:</span>
        <a href="{{ route('admin.users', array_filter(['role' => $roleFilter])) }}" class="filter-btn {{ $statusFilter === null ? 'active status-all' : '' }}">All</a>
        <a href="{{ route('admin.users', array_filter(array_merge(['status' => '1'], ['role' => $roleFilter]))) }}" class="filter-btn {{ $statusFilter === '1' ? 'active status-active' : '' }}">Active</a>
        <a href="{{ route('admin.users', array_filter(array_merge(['status' => '0'], ['role' => $roleFilter]))) }}" class="filter-btn {{ $statusFilter === '0' ? 'active status-inactive' : '' }}">Inactive</a>
    </div>
</div>

<style>
    .filter-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .filter-btn:hover {
        opacity: 0.9;
    }
    .filter-btn.active {
        color: #fff;
    }
    .filter-btn.active.role-all,
    .filter-btn.active.role-professor,
    .filter-btn.active.status-all {
        background: var(--purple);
    }
    .filter-btn.active.role-admin {
        background: var(--red);
    }
    .filter-btn.active.role-student,
    .filter-btn.active.status-active {
        background: var(--green);
    }
    .filter-btn.active.status-inactive {
        background: var(--amber);
        color: #000;
    }
</style>

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
                    <td class="td-mono">{{ $user->created_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                    <td style="vertical-align:middle;">
                        <div style="display:inline-flex;gap:4px;align-items:center;white-space:nowrap;">
                            <a class="btn btn-sm" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-d">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text2);">No users found.
                @if($filters['role'] || $filters['status'])
                    <br><small>Try adjusting your filters.</small>
                @endif
                </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag">
        <span>
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of 
            @if($filters['role'] || $filters['status'])
                <strong>{{ $users->total() }} filtered users</strong>
                @if($filters['role'])
                    <em>(Role: {{ ucfirst($filters['role']) }})</em>
                @endif
                @if($filters['status'])
                    <em>(Status: {{ $filters['status'] === '1' ? 'Active' : 'Inactive' }})</em>
                @endif
            @else
                {{ $users->total() }} users
            @endif
        </span>
        <div>{{ $users->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
