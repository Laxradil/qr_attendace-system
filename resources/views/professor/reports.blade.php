@extends('layouts.professor')

@section('title', 'Reports - Professor')
@section('header', 'Attendance Reports')
@section('subheader', 'View detailed attendance statistics for your classes')

@section('content')
<div class="content">
    <style>
        .search-bar{display:none !important;}
    </style>
    <div class="glass-table glass">
        @php
            $attendanceSummary = isset($attendanceSummary) ? $attendanceSummary : ['total_records' => 0, 'present' => 0, 'late' => 0, 'absent' => 0];
            $attendanceTrend = isset($attendanceTrend) ? $attendanceTrend : collect();
            $trendLabels = isset($trendLabels) ? $trendLabels : collect();

            $totalRecords = $attendanceSummary['total_records'] ?? 0;
            $presentCount = $attendanceSummary['present'] ?? 0;
            $lateCount = $attendanceSummary['late'] ?? 0;
            $absentCount = $attendanceSummary['absent'] ?? 0;
            $presentPct = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;
            $latePct = $totalRecords > 0 ? round(($lateCount / $totalRecords) * 100, 2) : 0;
            $absentPct = $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 2) : 0;

            $trendCount = $attendanceTrend->count();
            $maxPoints = max($trendCount ? $attendanceTrend->pluck('present')->max() : 5, 5);
            $step = $trendCount > 1 ? 100 / ($trendCount - 1) : 100;
            $pointData = $attendanceTrend->map(function ($point, $index) use ($maxPoints, $trendCount, $step) {
                $x = $trendCount > 1 ? $index * $step : 50;
                $y = 100 - ($point['present'] / $maxPoints * 100);
                return ['x' => $x, 'y' => $y];
            });
            $chartPoints = $pointData->map(fn ($point) => "{$point['x']},{$point['y']}")->implode(' ');
            $labelCols = max(1, $trendCount);
            $attendanceRate = $totalRecords > 0 ? round($presentPct) : 0;
            $presentEnd = $presentPct;
            $lateEnd = $presentPct + $latePct;
            $absentEnd = min(100, $presentPct + $latePct + $absentPct);
        @endphp
        <style>
            .glass-table.glass{border-radius:24px;padding:24px;background:rgba(6,11,29,.95);border:1px solid rgba(255,255,255,.08);box-shadow:0 24px 60px rgba(0,0,0,.2);}
            .report-summary-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:24px;align-items:stretch;}
            .report-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:18px;}
            .report-card h3{margin:0;font-size:14px;font-weight:700;color:#f8fbff;letter-spacing:.01em;}
            .report-card-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
            .report-card-head .subtitle{color:rgba(255,255,255,.7);font-size:12px;}
            .report-chart{position:relative;border-radius:20px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.08);min-height:230px;padding:18px;}
            .report-chart::before{content:none;}
            .report-chart svg{width:100%;height:220px;overflow:visible;position:relative;z-index:1;}
            .chart-grid{display:none;}
            .chart-mark{display:none;}
            .chart-line{fill:none;stroke:#7c4dff;stroke-width:1.2;stroke-linecap:round;stroke-linejoin:round;}
            .chart-point{display:none;}
            .chart-labels{display:grid;grid-template-columns:repeat({{ $labelCols }},1fr);gap:8px;margin-top:14px;font-size:11px;color:rgba(255,255,255,.65);}
            .chart-labels span{text-align:center;opacity:.85;}
            .distribution-card{display:grid;grid-template-rows:auto 1fr;gap:18px;}
            .distribution-header{display:flex;align-items:center;justify-content:flex-start;}
            .distribution-header h3{margin:0;font-size:14px;font-weight:700;color:#f8fbff;}
            .distribution-body{display:grid;grid-template-columns:220px minmax(0,1fr);gap:20px;align-items:center;}
            .distribution-pie{position:relative;width:180px;height:180px;display:grid;place-items:center;margin:auto 0;}
            .attendance-ring{position:absolute;inset:0;border-radius:50%;background:conic-gradient(#18f08b 0% {{ $presentEnd }}%, #ffc75a {{ $presentEnd }}% {{ $lateEnd }}%, #ff3d72 {{ $lateEnd }}% {{ $absentEnd }}%, rgba(255,255,255,.08) {{ $absentEnd }}% 100%);}
            .attendance-ring-inner{position:absolute;inset:18px;border-radius:50%;background:rgba(6,11,29,.98);display:grid;place-items:center;border:1px solid rgba(255,255,255,.05);}
            .attendance-ring-inner strong{font-size:34px;font-weight:800;line-height:1;color:#fff;}
            .attendance-ring-inner span{font-size:14px;color:rgba(255,255,255,.65);}
            .attendance-ring-inner small{display:block;margin-top:6px;font-size:11px;color:rgba(255,255,255,.6);letter-spacing:.08em;text-transform:uppercase;}
            .distribution-legend{display:grid;gap:12px;width:100%;}
            .distribution-row{display:grid;grid-template-columns:auto 1fr auto;gap:14px;align-items:center;padding:14px 16px;border-radius:16px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.06);}
            .distribution-dot{width:10px;height:10px;border-radius:50%;display:inline-block;}
            .distribution-dot.green{background:#18f08b;}
            .distribution-dot.yellow{background:#ffc75a;}
            .distribution-dot.red{background:#ff3d72;}
            .distribution-row strong{font-size:13px;color:#f8fbff;}
            .distribution-row .distribution-count{font-size:12px;font-weight:700;color:#f8fbff;}
            .distribution-row span:last-child{font-size:12px;font-weight:700;color:#f8fbff;}
            .distribution-row small{color:rgba(255,255,255,.6);font-size:11px;}
            
            .report-filter-row{display:flex;flex-wrap:wrap;gap:20px;align-items:flex-end;margin-bottom:24px;padding:0 0 4px 0;}
            .report-filter-field{display:flex;flex-direction:column;gap:8px;width:220px;}
            .report-filter-field label{color:rgba(255,255,255,.65);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;}
            .report-filter-field .filter-select,
            .report-filter-field .filter-input{width:100%;min-height:44px;padding:12px 14px;border-radius:14px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#f8fbff;font-size:13px;transition:.2s ease;}
            
            .report-filter-field .filter-select {
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='rgba(255,255,255,0.7)' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 14px center;
                background-size: 16px;
                padding-right: 40px;
            }
            .report-filter-field .filter-select option{background:rgba(10,15,32,.98);color:#f8fbff;}
            .report-filter-field .filter-select:focus,
            .report-filter-field .filter-input:focus{outline:none;border-color:rgba(139,92,255,.6);background:rgba(255,255,255,.08);}
            
            .report-filter-actions{display:flex;align-items:center;height:44px;margin-left:auto;}
            .filter-btn{min-height:44px;padding:0 18px;border-radius:14px;border:1px solid rgba(255,255,255,.08);font-weight:700;cursor:pointer;transition:.2s ease;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;background:rgba(255,255,255,.04);color:#f8fbff;}
            .filter-btn.apply{background:rgba(139,92,255,.12);border-color:rgba(139,92,255,.16);}
            .filter-btn.reset{background:rgba(255,255,255,.02);color:rgba(255,255,255,.85);}
            .filter-btn:hover{transform:translateY(-1px);}
            
            .badge{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;padding:6px 11px;font-size:11px;font-weight:700;letter-spacing:.01em;}
            .badge.bg{background:rgba(24,240,139,.12);color:#b8ffde;}
            .badge.ba{background:rgba(255,199,90,.12);color:#fff0b3;}
            .badge.br{background:rgba(255,61,114,.12);color:#ffb3c4;}
            .table-wrap{overflow-x:auto;border-radius:18px;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.12) transparent;}
            table{width:100%;min-width:700px;border-collapse:separate;border-spacing:0;}
            th, td{padding:13px 14px;text-align:left;border-bottom:1px solid rgba(255,255,255,.06);vertical-align:middle;}
            th{background:rgba(255,255,255,.02);color:rgba(255,255,255,.75);font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px);}
            td{color:rgba(255,255,255,.92);font-size:13px;}
            tr:hover td{background:rgba(255,255,255,.03);}
            .muted{color:rgba(255,255,255,.65);font-weight:400;}
        </style>

        <style>
          body.theme-light .glass-table.glass {
            background: #ffffff !important;
            border-color: #e5e7eb !important;
            box-shadow: 0 18px 40px rgba(15,23,42,.08) !important;
          }

          body.theme-light .report-card,
          body.theme-light .report-chart,
          body.theme-light .distribution-row,
          body.theme-light .report-filter-field .filter-select,
          body.theme-light .filter-btn,
          body.theme-light .table-wrap,
          body.theme-light th,
          body.theme-light td {
            background: #ffffff !important;
            color: #0f172a !important;
            border-color: #e5e7eb !important;
          }

          /* Explicitly stripping opacity and gray tint overrides from Webkit sub-elements */
          body.theme-light .report-filter-field .filter-input {
            background: #ffffff !important;
            color: #000000 !important;
            border-color: #e5e7eb !important;
            opacity: 1 !important;
            -webkit-text-fill-color: #000000 !important;
          }
          body.theme-light .report-filter-field .filter-input::-webkit-datetime-edit,
          body.theme-light .report-filter-field .filter-input::-webkit-datetime-edit-fields-wrapper,
          body.theme-light .report-filter-field .filter-input::-webkit-datetime-edit-text,
          body.theme-light .report-filter-field .filter-input::-webkit-datetime-edit-month-field,
          body.theme-light .report-filter-field .filter-input::-webkit-datetime-edit-day-field,
          body.theme-light .report-filter-field .filter-input::-webkit-datetime-edit-year-field {
            color: #000000 !important;
            opacity: 1 !important;
            -webkit-text-fill-color: #000000 !important;
          }

          body.theme-light .attendance-ring-inner {
            background: #ffffff !important;
            border-color: #e5e7eb !important;
          }

          body.theme-light .attendance-ring-inner strong {
            color: #0f172a !important;
          }

          body.theme-light .attendance-ring-inner strong span,
          body.theme-light .attendance-ring-inner span,
          body.theme-light .attendance-ring-inner small {
            color: #475569 !important;
          }

          body.theme-light .report-card h3,
          body.theme-light .distribution-row strong,
          body.theme-light .distribution-row .distribution-count,
          body.theme-light .distribution-row span:last-child,
          body.theme-light .distribution-row small {
            color: #0f172a !important;
          }

          body.theme-light .report-filter-field label {
            color: #475569 !important;
          }

          body.theme-light .report-filter-field .filter-select option {
            background: #ffffff !important;
            color: #0f172a !important;
          }

          body.theme-light .filter-btn.apply {
            background: rgba(139,92,255,.12) !important;
            color: #0f172a !important;
            border-color: rgba(139,92,255,.16) !important;
          }

          body.theme-light .filter-btn.reset {
            background: rgba(241,245,249,.7) !important;
            color: #475569 !important;
          }

          body.theme-light .table-wrap {
            box-shadow: 0 14px 24px rgba(15,23,42,.06) !important;
          }

          body.theme-light tr:hover td {
            background: rgba(15,23,42,.04) !important;
          }
        </style>

        <form method="GET" action="{{ route('professor.reports') }}">
            <div class="report-filter-row">
                <div class="report-filter-field">
                    <label>Class</label>
                    <select name="class_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ request('class_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->display_name ?? $classe->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="report-filter-field">
                    <label>Date</label>
                    <input type="date" name="date" class="filter-input" value="{{ request('date') }}" onchange="this.form.submit()">
                </div>
                <div class="report-filter-actions">
                    <a href="{{ route('professor.reports') }}" class="filter-btn reset">Reset</a>
                </div>
            </div>

            <div class="report-summary-grid">
                <section class="report-card glass">
                    <div><h3>Attendance Overview (Present Students)</h3></div>
                    <div class="report-chart">
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                            <polyline points="{{ $chartPoints }}" class="chart-line" />
                        </svg>
                        <div class="chart-labels">
                            @foreach($attendanceTrend as $point)
                                <span>{{ $point['label'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </section>
                <section class="report-card glass distribution-card">
                    <div class="distribution-header"><h3>Attendance Distribution</h3></div>
                    <div class="distribution-body">
                        <div class="distribution-pie">
                            <div class="attendance-ring"></div>
                            <div class="attendance-ring-inner">
                                <div style="text-align:center;">
                                    <strong>{{ $attendanceRate }}<span style="font-size:16px;color:var(--muted);">%</span></strong>
                                    <small>Average</small>
                                </div>
                            </div>
                        </div>
                        <div class="distribution-legend">
                            <div class="distribution-row">
                                <span class="distribution-dot green"></span>
                                <div>
                                    <div style="font-size:12px;color:var(--muted);">Present</div>
                                    <div class="distribution-count">{{ $presentCount }}</div>
                                </div>
                                <div style="font-size:13px;font-weight:700;color:#18f08b;">{{ $totalRecords > 0 ? round($presentPct) : 0 }}%</div>
                            </div>
                            <div class="distribution-row">
                                <span class="distribution-dot yellow"></span>
                                <div>
                                    <div style="font-size:12px;color:var(--muted);">Late</div>
                                    <div class="distribution-count">{{ $lateCount }}</div>
                                </div>
                                <div style="font-size:13px;font-weight:700;color:#ffc75a;">{{ $totalRecords > 0 ? round($latePct) : 0 }}%</div>
                            </div>
                            <div class="distribution-row">
                                <span class="distribution-dot red"></span>
                                <div>
                                    <div style="font-size:12px;color:var(--muted);">Absent</div>
                                    <div class="distribution-count">{{ $absentCount }}</div>
                                </div>
                                <div style="font-size:13px;font-weight:700;color:#ff3d72;">{{ $totalRecords > 0 ? round($absentPct) : 0 }}%</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:180px;">Student</th>
                            <th style="text-align:center;">Student ID</th>
                            <th style="text-align:center;">Total</th>
                            <th style="text-align:center;">Present</th>
                            <th style="text-align:center;">Late</th>
                            <th style="text-align:center;">Absent</th>
                            <th style="text-align:center;">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $data)
                            <tr>
                                <td>
                                    <div style="display:flex;flex-direction:column;gap:4px;">
                                        <strong>{{ $data['student']->name }}</strong>
                                        <div class="muted" style="font-size:12px;">{{ $data['student']->email ?? 'No email' }}</div>
                                    </div>
                                </td>
                                <td style="text-align:center;color:var(--text2);font-family:var(--mono);">{{ $data['student']->student_id ?? 'N/A' }}</td>
                                <td style="text-align:center;color:var(--text2);">{{ $data['total'] }}</td>
                                <td style="text-align:center;color:var(--green);">{{ $data['present'] }}</td>
                                <td style="text-align:center;color:var(--amber);">{{ $data['late'] ?? 0 }}</td>
                                <td style="text-align:center;color:var(--red);">{{ $data['absent'] ?? 0 }}</td>
                                <td style="text-align:center;">
                                    <span class="badge {{ $data['percentage'] >= 80 ? 'bg' : ($data['percentage'] >= 60 ? 'ba' : 'br') }}" style="font-size:11px;padding:7px 10px;">
                                        {{ $data['percentage'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;padding:40px 20px;color:var(--muted);">
                                    No report data available for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding-top:16px;text-align:right;color:var(--muted);font-size:12px;">
                Showing {{ count($attendanceData) }} records
            </div>
        </form>
    </div>
</div>
@endsection