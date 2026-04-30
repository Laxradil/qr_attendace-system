@extends('layouts.admin')

@section('title', 'Students')
@section('header', 'Students')
@section('subheader', 'Manage all student accounts in the system.')

@section('content')
<div style="display:flex;gap:8px;margin-bottom:12px;">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-p">+ Add Student</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Student ID</th><th>Student</th><th>Email</th><th>Username</th><th>Status</th><th>Date Joined</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td class="td-mono">{{ $student->student_id ?: 'N/A' }}</td>
                    <td><div style="display:flex;align-items:center;gap:7px;"><div class="log-av">{{ strtoupper(substr($student->name, 0, 2)) }}</div><span style="font-weight:500;">{{ $student->name }}</span></div></td>
                    <td style="font-size:10px;color:var(--text2);">{{ $student->email }}</td>
                    <td style="font-size:10px;color:var(--text2);">{{ $student->username }}</td>
                    <td><span class="badge {{ $student->is_active ? 'bg' : 'br' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="td-mono">{{ $student->created_at?->format('M d, Y') }}</td>
                    <td style="display:flex;gap:4px;"><a class="btn btn-sm" href="{{ route('admin.users.edit', $student) }}">Edit</a><form action="{{ route('admin.users.delete', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-d">Delete</button></form></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text2);">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</span><div>{{ $students->links() }}</div></div>
</div>
@endsection
