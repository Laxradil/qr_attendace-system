@extends('layouts.professor')

@section('title', 'Students - Professor')
@section('header', 'My Students')
@section('subheader', 'View all students in your assigned classes')

@section('content')
<div class="content">
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Classes</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="td-mono">{{ $student->student_id ?? 'N/A' }}</td>
                        <td style="font-weight:500;">{{ $student->name }}</td>
                        <td style="color:var(--text2);">{{ $student->email }}</td>
                        <td style="text-align:center;color:var(--text2);">{{ $student->enrolledClasses->count() }}</td>
                        <td><span class="badge {{ $student->is_active ? 'bg' : 'br' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--text2);padding:20px;">No students found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
