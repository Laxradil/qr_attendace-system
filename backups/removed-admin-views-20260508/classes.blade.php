@extends('layouts.admin')

@section('title', 'Classes')
@section('header', 'Classes')
@section('subheader', 'Manage all classes and schedules in the system.')

@section('content')

<div class="toolbar">
    <a href="{{ route('admin.classes.create') }}" class="btn primary">+ Add Class</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Professor</th>
                <th>Enrolled</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classes as $classe)
                <tr>
                    <td class="td-mono">{{ $classe->code }}</td>
                    <td>
                        <div style="font-weight:500;font-size:12px;">{{ $classe->name }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $classe->description ?: 'No description' }}</div>
                    </td>
                    <td style="font-size:11px;">
                        @if($classe->professors->isNotEmpty())
                            <div style="display:flex;flex-direction:column;gap:4px;">
                                @foreach($classe->professors as $prof)
                                    <span class="pill purple">{{ $prof->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="muted">N/A</span>
                        @endif
                    </td>
                    <td style="font-size:12px;font-weight:700;">{{ $classe->students->count() }}</td>
                    <td>
                        <span class="pill {{ $classe->is_active ? 'green' : 'red' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                        <a href="{{ route('admin.classes.enroll', $classe) }}" class="btn">Enroll</a>
                        <a href="{{ route('admin.classes.edit', $classe) }}" class="btn">Edit</a>
                        <form action="{{ route('admin.classes.delete', $classe) }}" method="POST" onsubmit="return confirm('Delete?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn danger" style="margin:0;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">No classes found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer-bar">
        <span>Showing {{ $classes->firstItem() ?? 0 }}–{{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }} classes</span>
        <div class="pager">{{ $classes->links() }}</div>
    </div>
</div>
@endsection
