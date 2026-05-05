@extends('layouts.professor')

@section('title', 'Students - Professor')
@section('header', 'My Students')
@section('subheader', 'View all students in your assigned classes')

@section('content')
<div class="content">
    @if($classes->count())
        <div style="display:grid;gap:14px;">
            @foreach($classes as $classe)
                <details class="class-panel" {{ $loop->index === 0 ? 'open' : '' }}>
                    <summary>
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;width:100%;">
                            <div>
                                <div style="font-weight:700;">{{ $classe->code }} - {{ $classe->name }}</div>
                                <div style="font-size:11px;color:var(--text3);margin-top:4px;">
                                    {{ $classe->students->count() }} student{{ $classe->students->count() === 1 ? '' : 's' }} enrolled
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text2);">
                                <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                                <span>{{ $loop->index === 0 ? 'collapse' : 'expand' }}</span>
                            </div>
                        </div>
                    </summary>

                    <div style="padding-top:14px;">
                        @if($classe->students->count())
                            <div class="tbl-wrap" style="margin:0;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classe->students as $student)
                                            @php
                                                $requestKey = $classe->id . '_' . $student->id;
                                                $pending = $pendingRequests[$requestKey] ?? null;
                                            @endphp
                                            <tr>
                                                <td class="td-mono">{{ $student->student_id ?? 'N/A' }}</td>
                                                <td style="font-weight:500;">{{ $student->name }}</td>
                                                <td style="color:var(--text2);">{{ $student->email }}</td>
                                                <td>
                                                    <span class="badge {{ $student->is_active ? 'bg' : 'br' }}">
                                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($pending)
                                                        <span class="badge br" style="background:rgba(255, 165, 0, 0.16);color:var(--text);border:none;">Request Pending</span>
                                                    @else
                                                        <form method="POST" action="{{ route('professor.drop-request') }}" style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="class_id" value="{{ $classe->id }}">
                                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                            <button type="submit" class="btn btn-sm btn-d" style="padding:6px 10px;">Drop</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div style="padding:18px 0;color:var(--text2);font-size:13px;">No students enrolled in this class yet.</div>
                        @endif
                    </div>
                </details>
            @endforeach
        </div>
    @else
        <div style="padding:32px;text-align:center;color:var(--text2);">No students found in your assigned classes.</div>
    @endif
</div>

<style>
    .class-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 14px;
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
</style>
@endsection
