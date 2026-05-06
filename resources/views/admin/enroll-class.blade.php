@extends('layouts.admin')

@section('title', 'Enroll Students')
@section('header', 'Enroll Students')
@section('subheader', 'Add students to the selected class.')

@section('content')
<div class="card" style="max-width:760px;">
    <form action="{{ route('admin.classes.enroll.store', $classe) }}" method="POST">
        @csrf

        <div style="margin-bottom:18px;">
            <div style="font-size:12px;color:var(--text2);">Class Code</div>
            <div style="font-weight:700;font-size:16px;">{{ $classe->code }} &mdash; {{ $classe->name }}</div>
            <div style="font-size:12px;color:var(--text3);margin-top:4px;">
                Assigned professors: {{ $classe->professors->pluck('name')->join(', ') ?: 'None' }}
            </div>
            <div style="font-size:12px;color:var(--text3);margin-top:4px;">
                Currently enrolled students: {{ $classe->students->count() }}
            </div>
        </div>

        <label class="fl">Select Students to Enroll *</label>
        <select class="fi" name="student_ids[]" multiple size="10" required>
            @forelse($availableStudents as $student)
                <option value="{{ $student->id }}">{{ $student->name }} &mdash; {{ $student->email }}</option>
            @empty
                <option disabled>No available students to enroll.</option>
            @endforelse
        </select>
        <div style="font-size:11px;color:var(--text2);margin:8px 0 18px;">Hold Ctrl / Cmd to select multiple students.</div>

        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-p">Enroll Students</button>
            <a href="{{ route('admin.classes') }}" class="btn">Back to Classes</a>
        </div>
    </form>
</div>
@endsection
