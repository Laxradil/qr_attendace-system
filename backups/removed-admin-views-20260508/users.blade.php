@extends('layouts.admin')

@section('title', 'Users')
@section('header', 'Users')
@section('subheader', 'Manage all system users and their access.')

@section('content')
@php
    $roleFilter = $filters['role'] ?? null;
    $statusFilter = $filters['status'] ?? null;
@endphp

<div class="stats stats-4">
    <div class="stat">
        <div class="stat-icon blue">👥</div>
        <div class="stat-body">
            <strong>{{ $stats['total'] }}</strong>
            <span>Total Users</span>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon red">🔐</div>
        <div class="stat-body">
            <strong>{{ $stats['admins'] }}</strong>
            <span>Administrators</span>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon yellow">🎓</div>
        <div class="stat-body">
            <strong>{{ $stats['professors'] }}</strong>
            <span>Professors</span>
        </div>
    </div>
    <div class="stat">
        <div class="stat-icon green">👨</div>
        <div class="stat-body">
            <strong>{{ $stats['students'] }}</strong>
            <span>Students</span>
        </div>
    </div>
</div>

<div class="toolbar">
    <div class="tools">
        <a href="{{ route('admin.users.create') }}" class="btn primary">+ Add User</a>
        <span style="font-size:11px;color:var(--muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em;">Filter by Role:</span>
        <a href="{{ route('admin.users') }}" class="chip {{ !$roleFilter ? 'active' : '' }}">All Roles</a>
        <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="chip {{ $roleFilter === 'admin' ? 'active' : '' }}">Admin</a>
        <a href="{{ route('admin.users', ['role' => 'professor']) }}" class="chip {{ $roleFilter === 'professor' ? 'active' : '' }}">Professor</a>
        <a href="{{ route('admin.users', ['role' => 'student']) }}" class="chip {{ $roleFilter === 'student' ? 'active' : '' }}">Student</a>
    </div>
    <div class="tools">
        <span style="font-size:11px;color:var(--muted);text-transform:uppercase;font-weight:700;letter-spacing:.05em;">Filter by Status:</span>
        <a href="{{ route('admin.users', array_filter(['role' => $roleFilter])) }}" class="chip {{ $statusFilter === null ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.users', array_filter(array_merge(['status' => '1'], ['role' => $roleFilter]))) }}" class="chip {{ $statusFilter === '1' ? 'active' : '' }}">Active</a>
        <a href="{{ route('admin.users', array_filter(array_merge(['status' => '0'], ['role' => $roleFilter]))) }}" class="chip {{ $statusFilter === '0' ? 'active' : '' }}">Inactive</a>
    </div>
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
                    <td class="muted">{{ $user->created_at?->format('M d, Y') }}</td>
                    <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                        <a class="btn" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn danger" style="margin:0;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--muted);">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer-bar">
        <span>Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
        <div class="pager">{{ $users->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
