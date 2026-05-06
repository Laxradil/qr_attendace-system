@extends('layouts.admin')

@section('title', 'Students')
@section('header', 'Students')
@section('subheader', 'Manage all student accounts in the system.')

@section('content')
<div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;align-items:center;">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-p">+ Add Student</a>
    <span style="font-size:12px;color:var(--text2);">Students are grouped per subject. Expand each subject to view enrolled students.</span>
</div>

@if($classes->count())
    <div style="display:grid;gap:14px;">
        @foreach($classes as $classe)
            <details class="class-panel" {{ $loop->index === 0 ? 'open' : '' }}>
                <summary>
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;width:100%;">
                        <div>
                            <div style="font-weight:700;">{{ $classe->display_name }}</div>
                            <div style="font-size:11px;color:var(--text3);margin-top:4px;">
                                {{ $classe->students->count() }} student{{ $classe->students->count() === 1 ? '' : 's' }} &middot;
                                Assigned: {{ $classe->professors->pluck('name')->join(', ') ?: 'No professor assigned' }}
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text2);">
                            <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                            <span style="font-size:11px;">Click to {{ $loop->index === 0 ? 'collapse' : 'expand' }}</span>
                        </div>
                    </div>
                </summary>

                <div style="padding:14px 0 0;">
                    @if($classe->students->count())
                        <div class="tbl-wrap" style="margin:0;">
                            <table>
                                <thead><tr><th>Student ID</th><th>Student</th><th>Email</th><th>Username</th><th>Status</th><th>Date Joined</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @foreach($classe->students as $student)
                                        <tr>
                                            <td class="td-mono">{{ $student->student_id ?: 'N/A' }}</td>
                                            <td><div style="display:flex;align-items:center;gap:7px;"><div class="log-av">{{ strtoupper(substr($student->name, 0, 2)) }}</div><span style="font-weight:500;">{{ $student->name }}</span></div></td>
                                            <td style="font-size:10px;color:var(--text2);">{{ $student->email }}</td>
                                            <td style="font-size:10px;color:var(--text2);">{{ $student->username }}</td>
                                            <td><span class="badge {{ $student->is_active ? 'bg' : 'br' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span></td>
                                            <td class="td-mono">{{ $student->created_at?->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                                            <td style="display:flex;gap:4px;flex-wrap:wrap;"><a class="btn btn-sm" href="{{ route('admin.users.edit', $student) }}">Edit</a><form action="{{ route('admin.users.delete', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-d">Delete</button></form></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding:18px 0;color:var(--text2);font-size:13px;">No students enrolled in this subject yet.</div>
                    @endif
                </div>
            </details>
        @endforeach
    </div>

    <div class="pag" style="margin-top:16px;"><span>Showing {{ $classes->firstItem() ?? 0 }} to {{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }} subjects</span><div>{{ $classes->links() }}</div></div>
@else
    <div style="padding:32px;text-align:center;color:var(--text2);">No classes or students found.</div>
@endif

<style>
    .class-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 12px 14px;
        transition: box-shadow 120ms ease;
    }
    .class-panel[open] {
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.08);
    }
    .class-panel summary {
        list-style: none;
        cursor: pointer;
        padding: 0;
    }
    .class-panel summary::-webkit-details-marker {
        display: none;
    }
    .class-panel summary:focus {
        outline: none;
    }
    .class-panel summary div {
        width: 100%;
    }
</style>
@endsection
