@extends('layouts.professor')

@section('title', 'Schedules - Professor')
@section('header', 'Class Schedules')
@section('subheader', 'View all your scheduled classes (read-only)')

@section('content')
<div class="content">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:12px;">
        @forelse($schedules as $schedule)
            <div class="card" style="margin-bottom:0;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                    <div>
                        <div style="font-size:13px;font-weight:700;">{{ $schedule->subject_name }}</div>
                        <div style="font-size:10px;color:var(--text2);margin-top:2px;font-family:'JetBrains Mono',monospace;">{{ $schedule->subject_code }}</div>
                    </div>
                    <div style="font-size:18px;font-weight:700;color:var(--blue);">{{ $schedule->room }}</div>
                </div>
                
                <div style="display:grid;gap:8px;padding-top:12px;border-top:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:10px;">
                        <span style="color:var(--text2);">📅 Days:</span>
                        <span style="font-weight:600;color:var(--text);">{{ $schedule->days }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:10px;">
                        <span style="color:var(--text2);">⏰ Time:</span>
                        <span style="font-weight:600;color:var(--text);">{{ $schedule->time }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:10px;">
                        <span style="color:var(--text2);">👨‍🏫 Professor:</span>
                        <span style="font-weight:600;color:var(--text);">{{ $schedule->professor }}</span>
                    </div>
                </div>

                <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:9px;color:var(--text3);">View-only</span>
                </div>
            </div>
        @empty
            <div style="grid-column:1 / -1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px;text-align:center;">
                <div style="width:60px;height:60px;border-radius:50%;background:var(--navy3);display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:24px;">📅</div>
                <div style="font-size:13px;color:var(--text2);">No schedules available</div>
                <div style="font-size:10px;color:var(--text3);margin-top:4px;">Your class schedules will appear here</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
