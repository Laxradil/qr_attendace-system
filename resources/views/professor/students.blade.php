@extends('layouts.professor')

@section('title', 'Students - Professor')
@section('header', 'My Students')
@section('subheader', 'View all students in your assigned classes')

@section('content')
<div class="content">
    @forelse($classes as $classe)
        <div class="card" style="margin-bottom:18px;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:16px;">
                <div>
                    <div style="font-size:14px;font-weight:700;">Students for {{ $classe->code }} - {{ $classe->name }}</div>
                    <div style="font-size:11px;color:var(--text2);">Category: {{ $classe->code }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="badge bg">{{ $classe->students->count() }} students</span>
                </div>
            </div>

            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classe->students as $student)
                            <tr>
                                <td class="td-mono">{{ $student->student_id ?? 'N/A' }}</td>
                                <td style="font-weight:500;">{{ $student->name }}</td>
                                <td style="color:var(--text2);">{{ $student->email }}</td>
                                <td><span class="badge {{ $student->is_active ? 'bg' : 'br' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:var(--text2);padding:20px;">No students assigned to this class yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card" style="padding:24px;text-align:center;color:var(--text2);">
            No students found for your assigned classes.
        </div>
    @endforelse
</div>
@endsection
