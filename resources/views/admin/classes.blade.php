@extends('layouts.admin')

@section('title', 'Classes')
@section('header', 'Classes')
@section('subheader', 'Manage all classes and schedules in the system.')

@section('content')
<div style="display:flex;gap:8px;margin-bottom:12px;">
    <a href="{{ route('admin.classes.create') }}" class="btn btn-sm btn-p">+ Add Class</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Class Code</th><th>Class Name</th><th>Professor</th><th>Enrolled</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($classes as $classe)
                <tr>
                    <td class="td-mono">{{ $classe->code }}</td>
                    <td>
                        <div style="font-weight:500;font-size:11px;">{{ $classe->name }}</div>
                        <div style="font-size:9px;color:var(--text3);">{{ $classe->description ?: 'No description' }}</div>
                    </td>
                    <td style="font-size:10px;">{{ $classe->professor->name ?? 'N/A' }}</td>
                    <td style="font-size:11px;font-weight:600;">{{ $classe->students->count() }}</td>
                    <td><span class="badge {{ $classe->is_active ? 'bg' : 'ba' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="display:flex;gap:4px;">
                        <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-sm">Edit</a>
                        <form action="{{ route('admin.classes.delete', $classe) }}" method="POST" onsubmit="return confirm('Delete this class?')">@csrf @method('DELETE')<button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No classes found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $classes->firstItem() ?? 0 }} to {{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }} classes</span><div>{{ $classes->links() }}</div></div>
</div>
@endsection
