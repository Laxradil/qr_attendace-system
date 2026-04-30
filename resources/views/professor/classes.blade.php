@extends('layouts.professor')

@section('title', 'My Classes - Professor')
@section('header', 'My Classes')
@section('subheader', 'View and manage your assigned classes')

@section('content')
<div class="content">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
        @forelse($classes as $classe)
            <div class="card" style="margin-bottom:0;padding:0;overflow:hidden;display:flex;flex-direction:column;">
                <div style="background:linear-gradient(135deg,var(--purple),var(--blue));padding:14px;border-bottom:1px solid var(--border);">
                    <div style="font-size:14px;font-weight:700;">{{ $classe->code }}</div>
                    <div style="font-size:11px;color:var(--purple-light);margin-top:2px;">{{ $classe->name }}</div>
                </div>
                <div style="padding:12px;flex:1;display:flex;flex-direction:column;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-size:11px;">
                        <span style="color:var(--text2);">Students:</span>
                        <span style="font-weight:600;color:var(--text);">{{ $classe->students_count }}</span>
                    </div>
                    @if($classe->description)
                        <div style="font-size:10px;color:var(--text2);margin-bottom:8px;line-height:1.4;">{{ substr($classe->description, 0, 80) }}{{ strlen($classe->description) > 80 ? '...' : '' }}</div>
                    @endif
                    <div style="flex:1;"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid var(--border);margin-top:8px;">
                        <span class="badge {{ $classe->is_active ? 'bg' : 'br' }}" style="font-size:9px;">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                        <a href="{{ route('professor.class-detail', $classe) }}" class="btn btn-sm" style="text-decoration:none;">View →</a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column:1 / -1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px;text-align:center;">
                <div style="width:60px;height:60px;border-radius:50%;background:var(--navy3);display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:24px;">📚</div>
                <div style="font-size:13px;color:var(--text2);">No classes assigned yet</div>
                <div style="font-size:10px;color:var(--text3);margin-top:4px;">Your assigned classes will appear here</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
