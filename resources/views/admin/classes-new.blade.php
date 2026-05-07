@extends('layouts.admin-new')

@section('title', 'Classes - QR Attendance Admin')
@section('pageTitle', 'Classes')
@section('pageSubtitle', 'Manage class sections and subject assignments.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar">
    <a href="{{ route('admin.classes.create') }}" class="btn primary">＋ Add Class</a>
    <div class="search-bar" style="height:42px;width:260px">🔍 <span style="font-size:13px">Search classes...</span></div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Class Code</th>
          <th>Class Name</th>
          <th>Professor</th>
          <th>Students</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($classes as $class)
        <tr>
          <td><span style="font-family:var(--mono);font-size:13px">{{ $class->code }}</span></td>
          <td><b>{{ $class->name }}</b></td>
          <td>{{ $class->professor->name ?? 'N/A' }}</td>
          <td>{{ $class->students->count() }} students</td>
          <td><span class="pill green">Active</span></td>
          <td>
            <a href="{{ route('admin.classes.edit', $class) }}" class="btn slim">Edit</a>
            <form method="POST" action="{{ route('admin.classes.delete', $class) }}" style="display:inline" onsubmit="return confirm('Delete this class?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn danger slim">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No classes found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
