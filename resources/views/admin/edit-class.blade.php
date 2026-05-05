@extends('layouts.admin')

@section('title', 'Edit Class')
@section('header', 'Edit Class')
@section('subheader', 'Update class details and assigned professor.')

@section('content')
<div class="card" style="max-width:760px;">
    <form action="{{ route('admin.classes.update', $classe) }}" method="POST">
        @csrf
        @method('PUT')
        <label class="fl">Class Code *</label>
        <input class="fi" type="text" name="code" value="{{ $classe->code }}" maxlength="20" required>
        <div style="height:8px;"></div>

        <label class="fl">Class Name *</label>
        <input class="fi" type="text" name="name" value="{{ $classe->name }}" required>
        <div style="height:8px;"></div>

        <label class="fl">Description</label>
        <textarea class="fi" name="description" rows="4">{{ $classe->description }}</textarea>
        <div style="height:8px;"></div>

        <label class="fl">Assigned Professors *</label>
        <div class="professor-selector" style="display:flex;gap:10px;align-items:center;">
            <div style="flex:1;">
                <label style="font-size:12px;color:var(--text2);">Available Professors</label>
                <select id="available-professors" multiple size="8" style="width:100%;min-height:120px;background:var(--bg2);color:var(--text);border:1px solid var(--border);border-radius:4px;padding:4px;">
                    @foreach($professors as $professor)
                        @if(!$classe->professors->contains($professor->id))
                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <button type="button" id="add-professor" class="btn" style="font-size:12px;padding:4px 8px;">Add >></button>
                <button type="button" id="remove-professor" class="btn" style="font-size:12px;padding:4px 8px;"><< Remove</button>
            </div>
            <div style="flex:1;">
                <label style="font-size:12px;color:var(--text2);">Selected Professors</label>
                <select id="selected-professors" name="professors[]" multiple size="8" required style="width:100%;min-height:120px;background:var(--bg2);color:var(--text);border:1px solid var(--border);border-radius:4px;padding:4px;">
                    @foreach($classe->professors as $professor)
                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="font-size:10px;color:var(--text2);margin-top:4px;">Select professors from available list and move them to selected</div>

        <input type="hidden" name="is_active" value="0">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text2);margin:10px 0;">
            <input type="checkbox" name="is_active" value="1" {{ $classe->is_active ? 'checked' : '' }}> Active class
        </label>

        <div style="font-size:10px;color:var(--text3);margin-bottom:8px;">Enrolled students: {{ $classe->students->count() }}</div>

        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-p">Update Class</button>
            <a href="{{ route('admin.classes') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>

<style>
.professor-selector select {
    overflow: auto;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.professor-selector select:focus {
    outline: none;
    border-color: var(--amber);
    box-shadow: 0 0 0 1px var(--amber);
}
.professor-selector select::-webkit-scrollbar {
    width: 8px;
}
.professor-selector select::-webkit-scrollbar-track {
    background: rgba(253,203,110,0.08);
    border-radius: 4px;
}
.professor-selector select::-webkit-scrollbar-thumb {
    background: rgba(253,203,110,0.3);
    border-radius: 4px;
}
.professor-selector select::-webkit-scrollbar-thumb:hover {
    background: rgba(253,203,110,0.45);
}
.professor-selector select option:checked,
.professor-selector select option[selected] {
    background: rgba(253,203,110,0.22);
    color: var(--text);
}
</style>

<script>
document.getElementById('add-professor').addEventListener('click', function() {
    moveOptions('available-professors', 'selected-professors');
});
document.getElementById('remove-professor').addEventListener('click', function() {
    moveOptions('selected-professors', 'available-professors');
});

function moveOptions(fromId, toId) {
    const from = document.getElementById(fromId);
    const to = document.getElementById(toId);
    Array.from(from.selectedOptions).forEach(option => {
        to.appendChild(option);
    });
}
</script>
@endsection
