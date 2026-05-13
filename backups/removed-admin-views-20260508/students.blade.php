@extends('layouts.admin')

@section('title', 'Students')
@section('header', 'Students')
@section('subheader', 'Manage all student accounts in the system.')

@section('content')

<div class="toolbar">
    <a href="{{ route('admin.users.create') }}" class="btn primary">+ Add Student</a>
    <span style="font-size:12px;color:var(--muted);">Students are grouped per subject. Expand each subject to view enrolled students.</span>
</div>

@if($classes->count())
    <div style="display:grid;gap:14px;">
        @foreach($classes as $classe)
            <details class="card" style="cursor:pointer;">
                <summary style="list-style:none;display:flex;justify-content:space-between;align-items:center;gap:12px;width:100%;padding:0;">
                    <div>
                        <div style="font-weight:700;">{{ $classe->display_name }}</div>
                        <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                            {{ $classe->students->count() }} student{{ $classe->students->count() === 1 ? '' : 's' }} · Assigned: {{ $classe->professors->pluck('name')->join(', ') ?: 'Unassigned' }}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);">
                        <span class="pill {{ $classe->is_active ? 'green' : 'red' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </summary>

                <div style="padding:14px 0 0;border-top:1px solid var(--line);margin-top:12px;">
                    @if($classe->students->count())
                        <div class="table-wrap" style="margin:0;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Date Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classe->students as $student)
                                        <tr>
                                            <td class="td-mono">{{ $student->student_id ?: 'N/A' }}</td>
                                            <td>
                                                <div class="user-cell">
                                                    <span class="small-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                                                    <span>{{ $student->name }}</span>
                                                </div>
                                            </td>
                                            <td class="muted">{{ $student->email }}</td>
                                            <td class="muted" style="font-size:10px;">{{ $student->username }}</td>
                                            <td>
                                                <span class="pill {{ $student->is_active ? 'green' : 'red' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span>
                                            </td>
                                            <td class="muted">{{ $student->created_at?->format('M d, Y') }}</td>
                                            <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                                                <a class="btn" href="{{ route('admin.users.edit', $student) }}">Edit</a>
                                                <form action="{{ route('admin.users.delete', $student) }}" method="POST" onsubmit="return confirm('Delete?')" style="margin:0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn danger" style="margin:0;">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding:18px 0;color:var(--muted);font-size:13px;">No students enrolled in this subject yet.</div>
                    @endif
                </div>
            </details>
        @endforeach
    </div>

    <div class="footer-bar" style="margin-top:16px;">
        <span>Showing {{ $classes->firstItem() ?? 0 }}–{{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }} subjects</span>
        <div class="pager">{{ $classes->links() }}</div>
    </div>
@else
    <div style="padding:32px;text-align:center;color:var(--muted);">No classes or students found.</div>
@endif

@endsection
