@extends('layouts.admin')

@section('title', 'Professors')
@section('header', 'Professors')
@section('subheader', 'Manage all professor accounts in the system.')

@section('content')
<div style="display:flex;gap:8px;margin-bottom:12px;">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-p">+ Add Professor</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>ID</th><th>Professor</th><th>Email</th><th>Status</th><th>Date Joined</th><th style="width:220px;">Actions</th></tr></thead>
        <tbody>
            @forelse($professors as $professor)
                <tr>
                    <td class="td-mono">PRF-{{ str_pad((string) $professor->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:7px;"><div class="log-av">{{ strtoupper(substr($professor->name, 0, 2)) }}</div><span style="font-weight:500;">{{ $professor->name }}</span></div>
                    </td>
                    <td style="font-size:10px;color:var(--text2);">{{ $professor->email }}</td>
                    <td><span class="badge {{ $professor->is_active ? 'bg' : 'br' }}">{{ $professor->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="td-mono">{{ $professor->created_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                    <td style="vertical-align:middle;">
                        <div style="display:inline-flex;gap:4px;align-items:center;flex-wrap:wrap;white-space:nowrap;">
                            <a href="{{ route('admin.users.edit', $professor) }}" class="btn btn-sm">Edit</a>
                            <form action="{{ route('admin.users.delete', $professor) }}" method="POST" onsubmit="return confirm('Delete this professor?')" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-d" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No professors found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $professors->firstItem() ?? 0 }} to {{ $professors->lastItem() ?? 0 }} of {{ $professors->total() }} professors</span><div>{{ $professors->links() }}</div></div>
</div>
@endsection
