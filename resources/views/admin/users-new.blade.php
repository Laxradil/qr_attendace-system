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
    <div class="stat glass">
      <div class="stat-icon blue">👥</div>
      <div class="stat-body">
        <strong>{{ $stats['total'] }}</strong>
        <span>Total Users</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon red">🛡</div>
      <div class="stat-body">
        <strong>{{ $stats['admins'] }}</strong>
        <span>Administrators</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon yellow">🎓</div>
      <div class="stat-body">
        <strong>{{ $stats['professors'] }}</strong>
        <span>Professors</span>
      </div>
    </div>
    <div class="stat glass">
      <div class="stat-icon purple">🧑‍🎓</div>
      <div class="stat-body">
        <strong>{{ $stats['students'] }}</strong>
        <span>Students</span>
      </div>
    </div>
</div>

<div class="toolbar">
    <div class="tools">
        <a href="{{ route('admin.users.create') }}" class="btn primary">+ Add User</a>
        <span class="chip {{ !$roleFilter ? 'active' : '' }}"><a href="{{ route('admin.users') }}" style="text-decoration:none;color:inherit;">All Roles</a></span>
        <span class="chip {{ $roleFilter === 'admin' ? 'active' : '' }}"><a href="{{ route('admin.users', ['role' => 'admin']) }}" style="text-decoration:none;color:inherit;">Admin</a></span>
        <span class="chip {{ $roleFilter === 'professor' ? 'active' : '' }}"><a href="{{ route('admin.users', ['role' => 'professor']) }}" style="text-decoration:none;color:inherit;">Professor</a></span>
        <span class="chip {{ $roleFilter === 'student' ? 'active' : '' }}"><a href="{{ route('admin.users', ['role' => 'student']) }}" style="text-decoration:none;color:inherit;">Student</a></span>
    </div>
    <div class="tools">
        <div class="search-bar" style="width:220px;height:40px">🔍 <span style="font-size:13px">Search users...</span></div>
        <button class="btn">☰ Filter</button>
    </div>
</div>

<div class="table-wrap glass">
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
    <div class="footer-bar">
        <span>Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
        <div class="pager">{{ $users->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
