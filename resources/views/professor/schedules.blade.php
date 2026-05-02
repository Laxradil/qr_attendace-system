@extends('layouts.professor')

@section('title', 'Schedules - Professor')
@section('header', 'Class Schedules')
@section('subheader', 'View all your scheduled classes (read-only)')

@section('content')
<div style="display:flex;flex-direction:column;gap:22px;">
    <div style="display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:14px;">
        <div class="card" style="padding:18px 20px;">
            <div style="font-size:12px;color:var(--text2);text-transform:uppercase;letter-spacing:0.18em;margin-bottom:8px;">Today's Classes</div>
            <div style="font-size:28px;font-weight:700;color:var(--text);">{{ $todayCount }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:6px;">{{ now()->format('M d, Y') }}</div>
        </div>
        <div class="card" style="padding:18px 20px;">
            <div style="font-size:12px;color:var(--text2);text-transform:uppercase;letter-spacing:0.18em;margin-bottom:8px;">Upcoming Classes</div>
            <div style="font-size:28px;font-weight:700;color:var(--text);">{{ $upcomingCount }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:6px;">This week</div>
        </div>
        <div class="card" style="padding:18px 20px;">
            <div style="font-size:12px;color:var(--text2);text-transform:uppercase;letter-spacing:0.18em;margin-bottom:8px;">Total Classes</div>
            <div style="font-size:28px;font-weight:700;color:var(--text);">{{ $totalClasses }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:6px;">This semester</div>
        </div>
        <div class="card" style="padding:18px 20px;">
            <div style="font-size:12px;color:var(--text2);text-transform:uppercase;letter-spacing:0.18em;margin-bottom:8px;">Total Students</div>
            <div style="font-size:28px;font-weight:700;color:var(--text);">{{ $totalStudents }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:6px;">Across all classes</div>
        </div>
    </div>

    <div class="card" style="padding:20px;">
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px;">
            <div style="display:flex;gap:10px;flex-wrap:wrap;min-width:320px;flex:1;">
                <button class="btn btn-sm btn-p" style="padding:10px 16px;">Weekly View</button>
                <button class="btn btn-sm" style="padding:10px 16px;">List View</button>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <select class="fi" style="min-width:180px;">
                    <option>May 22 – May 28, 2024</option>
                </select>
                <select class="fi" style="min-width:180px;">
                    <option>All Classes</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:860px;">
                <thead>
                    <tr style="text-align:left;">
                        <th style="padding:16px 12px 12px;color:var(--text2);font-size:12px;">Date</th>
                        <th style="padding:16px 12px 12px;color:var(--text2);font-size:12px;">Time</th>
                        <th style="padding:16px 12px 12px;color:var(--text2);font-size:12px;">Class</th>
                        <th style="padding:16px 12px 12px;color:var(--text2);font-size:12px;">Status</th>
                        <th style="padding:16px 12px 12px;color:var(--text2);font-size:12px;">Students</th>
                        <th style="padding:16px 12px 12px;color:var(--text2);font-size:12px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr style="border-top:1px solid rgba(255,255,255,0.05);">
                            <td style="padding:16px 12px;vertical-align:middle;">
                                <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $schedule->date_label }}</div>
                                <div style="font-size:11px;color:var(--text2);margin-top:4px;">{{ $schedule->next_date->format('F') }}</div>
                            </td>
                            <td style="padding:16px 12px;vertical-align:middle;">
                                <div style="font-size:13px;color:var(--text);font-weight:600;">{{ $schedule->time }}</div>
                            </td>
                            <td style="padding:16px 12px;vertical-align:middle;min-width:260px;">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#4b57ff,#6f52ff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">{{ strtoupper(substr($schedule->subject_name,0,2)) }}</div>
                                    <div style="min-width:0;">
                                        <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $schedule->subject_name }} ({{ $schedule->subject_code }})</div>
                                        <div style="font-size:11px;color:var(--text2);margin-top:4px;">{{ $schedule->classe->description ?? 'No section info' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:16px 12px;vertical-align:middle;">
                                <span class="badge {{ $schedule->status === 'Ongoing' ? 'bg' : 'btn-p' }}" style="padding:8px 12px;font-size:11px;">{{ $schedule->status }}</span>
                            </td>
                            <td style="padding:16px 12px;vertical-align:middle;color:var(--text2);font-size:13px;">{{ $schedule->students_count }} Students</td>
                            <td style="padding:16px 12px;vertical-align:middle;text-align:right;">
                                <button class="btn btn-sm" style="padding:8px 14px;">•••</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:24px 12px;text-align:center;color:var(--text2);">No schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="display:flex;justify-content:flex-end;align-items:center;margin-top:18px;gap:12px;">
            <span style="font-size:11px;color:var(--text3);">i All times are based on your local time ({{ now()->format('T') }})</span>
            <button class="btn btn-p" style="padding:10px 18px;">+ Add New Schedule</button>
        </div>
    </div>
</div>
@endsection