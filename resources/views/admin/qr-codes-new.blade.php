@extends('layouts.admin-new')

@section('title', 'QR Codes - QR Attendance Admin')
@section('pageTitle', 'Student QR Codes')
@section('pageSubtitle', 'View and manage student attendance QR codes.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar">
    <h3 style="font-size:16px;font-weight:800">Student QR Codes</h3>
    <button class="btn primary" onclick="alert('Bulk download started')">📥 Download All</button>
  </div>

  <div class="table-wrap">
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
              {{ $student->name }}
            </div>
          </td>
          <td class="muted">{{ $student->email }}</td>
          <td>
            @forelse($student->classes as $classe)
              <span class="pill purple" style="margin:2px">{{ $classe->code }}</span>
            @empty
              <span class="muted">No classes</span>
            @endforelse
          </td>
          <td>
            <div class="qr @if($student->studentQrCode) @else empty @endif">
              @if($student->studentQrCode)
                <img src="{{ route('admin.students.qr-code', $student) }}" alt="QR code for {{ $student->name }}">
              @else
                ▦
              @endif
            </div>
          </td>
          <td>
            <a href="{{ route('admin.students.qr-code', $student) }}" class="btn slim">Open</a>
            <button class="btn primary slim" onclick="alert('Downloading QR for {{ $student->name }}')">↓ PNG</button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No students found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="footer-bar">
    <span>Showing 1–{{ $students->count() }} of {{ App\Models\User::where('role', 'student')->count() }} students</span>
    <div class="pager">
      <button>‹</button>
      <a class="current">1</a>
      <button>›</button>
    </div>
  </div>
</div>
@endsection
