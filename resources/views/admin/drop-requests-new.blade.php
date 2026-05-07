@extends('layouts.admin-new')

@section('title', 'Drop Requests - QR Attendance Admin')
@section('pageTitle', 'Drop Requests')
@section('pageSubtitle', 'Review and approve or reject student drop requests.')

@section('content')
<div class="glass-table glass">
  <div class="section-head" style="margin-bottom:16px">
    <h3>📋 Drop Requests</h3>
    <span class="pill yellow">{{ $dropRequests->where('status', 'pending')->count() }} Pending</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Class</th>
          <th>Student</th>
          <th>Professor</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Submitted</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($dropRequests as $drop)
        <tr>
          <td><span style="font-family:var(--mono)">{{ $drop->id }}</span></td>
          <td><b>{{ $drop->classe->code }} — {{ $drop->classe->name }}</b></td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($drop->student->name, 0, 2)) }}</span>
              <div>
                <div>{{ $drop->student->name }}</div>
                <div class="muted" style="font-size:12px">{{ $drop->student->email }}</div>
              </div>
            </div>
          </td>
          <td>{{ $drop->classe->professor->name ?? 'N/A' }}</td>
          <td>{{ $drop->reason ?? 'Not provided' }}</td>
          <td>
            <span class="pill @if($drop->status == 'pending') yellow @elseif($drop->status == 'approved') green @else red @endif">
              {{ ucfirst($drop->status) }}
            </span>
          </td>
          <td>
            <div>{{ $drop->created_at->format('M d, Y') }}</div>
            <div class="muted" style="font-size:12px">{{ $drop->created_at->format('h:i A') }}</div>
          </td>
          <td>
            @if($drop->status == 'pending')
              <form method="POST" action="{{ route('admin.drop-requests.approve', $drop) }}" style="display:inline">
                @csrf
                <button type="submit" class="btn primary slim">Approve</button>
              </form>
              <form method="POST" action="{{ route('admin.drop-requests.reject', $drop) }}" style="display:inline">
                @csrf
                <button type="submit" class="btn danger slim">Reject</button>
              </form>
            @else
              <span class="muted" style="font-size:13px">No action available</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">No drop requests found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
