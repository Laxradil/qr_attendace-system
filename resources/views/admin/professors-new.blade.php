@extends('layouts.admin-new')

@section('title', 'Professors - QR Attendance Admin')
@section('pageTitle', 'Professors')
@section('pageSubtitle', 'Manage all professor accounts.')

@section('content')
<div class="card glass" style="margin-bottom:16px">
  <div class="mini-grid">
    <div class="mini">
      <div class="mini-icon stat-icon purple" style="width:38px;height:38px;border-radius:12px;font-size:16px">👥</div>
      <div><b>{{ $professors->count() }}</b><small>Total Professors</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon green" style="width:38px;height:38px;border-radius:12px;font-size:16px">✓</div>
      <div><b>{{ $professors->where('is_active', true)->count() }}</b><small>Active</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon blue" style="width:38px;height:38px;border-radius:12px;font-size:16px">✉</div>
      <div><b>{{ $professors->count() }}</b><small>Verified Emails</small></div>
    </div>
    <div class="mini">
      <div class="mini-icon stat-icon yellow" style="width:38px;height:38px;border-radius:12px;font-size:16px">📅</div>
      <div><b>{{ $professors->first()?->created_at?->format('M d') ?? 'N/A' }}</b><small>Latest Joined</small></div>
    </div>
  </div>
</div>

<div class="glass-table glass">
  <div class="toolbar">
    <a href="{{ route('admin.users.create') }}" class="btn primary">＋ Add Professor</a>
    <div class="tools">
      <button class="btn">☰ Filter</button>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Professor</th>
          <th>Email</th>
          <th>Status</th>
          <th>Date Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($professors as $professor)
        <tr>
          <td><span style="font-family:var(--mono);font-size:12px;color:var(--muted)">{{ $professor->id }}</span></td>
          <td>
            <div class="user-cell">
              <span class="small-avatar">{{ strtoupper(substr($professor->name, 0, 2)) }}</span>
              {{ $professor->name }}
            </div>
          </td>
          <td class="muted">{{ $professor->email }}</td>
          <td><span class="pill green">Active</span></td>
          <td class="muted">{{ $professor->created_at->format('M d, Y') }}</td>
          <td>
            <a href="{{ route('admin.users.edit', $professor) }}" class="btn slim">Edit</a>
            <form method="POST" action="{{ route('admin.users.delete', $professor) }}" style="display:inline" onsubmit="return confirm('Delete this professor?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn danger slim">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No professors found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
