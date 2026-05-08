@extends('layouts.admin')

@section('title', 'Student QR Codes')
@section('header', 'Student QR Codes')
@section('subheader', 'View and manage student attendance QR codes.')

@section('content')
<div class="toolbar">
    <h3 style="font-size:16px;font-weight:800">Student QR Codes</h3>
    <button class="btn primary" onclick="showToast('Bulk QR download started','📥','#80cbff')">📥 Download All</button>
</div>

<div class="table-wrap glass">
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Email</th>
                <th>Classes</th>
                <th>QR Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>
                        <div class="user-cell">
                            <span class="small-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                            <span>{{ $student->name }}</span>
                        </div>
                    </td>
                    <td class="muted">{{ $student->email }}</td>
                    <td>
                        {{ $student->classes?->count() ?? 0 }} classes
                    </td>
                    <td>
                        @if($student->qr_code)
                            <div style="width:64px;height:64px;border-radius:10px;background:#f8f8f8;border:2px solid #f0f0f0;display:inline-block;"></div>
                        @else
                            <div style="width:64px;height:64px;border-radius:10px;background:rgba(139,92,255,.07);border:1.5px dashed rgba(139,92,255,.45);display:grid;place-items:center;color:rgba(139,92,255,.6);font-size:22px;">▦</div>
                        @endif
                    </td>
                    <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                        <button class="btn slim" onclick="showToast('Opening QR for {{ $student->name }}','🔍','#80cbff')">Open</button>
                        <button class="btn primary slim" onclick="showToast('Downloading QR for {{ $student->name }}','📥','#4dffa0')">↓ PNG</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer-bar">
        <span>Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</span>
        <div class="pager">{{ $students->links() }}</div>
    </div>
</div>
@endsection
