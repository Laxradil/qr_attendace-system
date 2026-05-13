@extends('layouts.admin')

@section('title', 'Classes')
@section('header', 'Classes')
@section('subheader', 'Manage class sections and subject assignments.')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.classes.create') }}" class="btn primary">+ Add Class</a>
    <div class="search-bar" style="height:42px;width:260px">🔍 <span style="font-size:13px">Search classes...</span></div>
</div>

<div class="table-wrap glass">
    <table>
        <thead>
            <tr>
                <th>Class Code</th>
                <th>Class Name</th>
                <th>Professor</th>
                <th>Students</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classes as $class)
                <tr>
                    <td><span style="font-family:var(--mono);font-size:13px">{{ $class->class_code }}</span></td>
                    <td><b>{{ $class->class_name }}</b></td>
                    <td>{{ $class->professor?->name ?? 'Unassigned' }}</td>
                    <td>{{ $class->students?->count() ?? 0 }} students</td>
                    <td><span class="pill green">Active</span></td>
                    <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                        <a class="btn slim" href="{{ route('admin.classes.edit', $class) }}">Edit</a>
                        <form action="{{ route('admin.classes.delete', $class) }}" method="POST" onsubmit="return confirm('Delete?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn danger slim">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">No classes found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
