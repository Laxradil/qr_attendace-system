@extends('layouts.admin')

@section('title', 'Professors')
@section('header', 'Professors')
@section('subheader', 'Manage all professor accounts in the system.')

@section('content')

<div class="toolbar">
    <a href="{{ route('admin.users.create') }}" class="btn primary">+ Add Professor</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Professor</th>
                <th>Email</th>
                <th>Status</th>
                <th>Date Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($professors as $professor)
                <tr>
                    <td class="td-mono">PRF-{{ str_pad((string) $professor->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="user-cell">
                            <span class="small-avatar">{{ strtoupper(substr($professor->name, 0, 2)) }}</span>
                            <span>{{ $professor->name }}</span>
                        </div>
                    </td>
                    <td class="muted">{{ $professor->email }}</td>
                    <td>
                        <span class="pill {{ $professor->is_active ? 'green' : 'red' }}">{{ $professor->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="muted">{{ $professor->created_at?->format('M d, Y') }}</td>
                    <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                        <a href="{{ route('admin.users.edit', $professor) }}" class="btn">Edit</a>
                        <form action="{{ route('admin.users.delete', $professor) }}" method="POST" onsubmit="return confirm('Delete?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn danger" style="margin:0;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">No professors found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer-bar">
        <span>Showing {{ $professors->firstItem() ?? 0 }}–{{ $professors->lastItem() ?? 0 }} of {{ $professors->total() }} professors</span>
        <div class="pager">{{ $professors->links() }}</div>
    </div>
</div>
@endsection
