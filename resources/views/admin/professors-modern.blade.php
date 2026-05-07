@extends('layouts.admin')

@section('title', 'Professors')
@section('header', 'Professors')
@section('subheader', 'Manage all professor accounts.')

@section('content')
<div class="card glass" style="margin-bottom:16px">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
        <div>
            <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;">{{ $professors->total() }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:2px;">Total Professors</div>
        </div>
        <div>
            <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;color:#4dffa0;">{{ $professors->where('is_active', 1)->count() }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:2px;">Active</div>
        </div>
        <div>
            <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;">{{ $professors->where('is_verified', 1)->count() }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:2px;">Verified Emails</div>
        </div>
        <div>
            <div style="font-size:20px;font-weight:900;letter-spacing:-.06em;">{{ $professors->max('created_at')?->format('M d') ?? 'N/A' }}</div>
            <div style="font-size:11px;color:var(--muted);margin-top:2px;">Latest Joined</div>
        </div>
    </div>
</div>

<div class="toolbar">
    <a href="{{ route('admin.professors.create') }}" class="btn primary">+ Add Professor</a>
    <div class="tools">
        <div class="search-bar" style="width:220px;height:40px">🔍 <span style="font-size:13px">Search professors...</span></div>
        <button class="btn">☰ Filter</button>
    </div>
</div>

<div class="table-wrap glass">
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
            @forelse($professors as $prof)
                <tr>
                    <td class="td-mono">PRF-{{ str_pad((string) $prof->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="user-cell">
                            <span class="small-avatar">{{ strtoupper(substr($prof->name, 0, 2)) }}</span>
                            <span>{{ $prof->name }}</span>
                        </div>
                    </td>
                    <td class="muted">{{ $prof->email }}</td>
                    <td><span class="pill green">Active</span></td>
                    <td class="td-mono">{{ $prof->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                    <td style="display:flex;gap:6px;align-items:center;white-space:nowrap;">
                        <a class="btn slim" href="{{ route('admin.professors.edit', $prof) }}">Edit</a>
                        <form action="{{ route('admin.professors.delete', $prof) }}" method="POST" onsubmit="return confirm('Delete?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn danger slim">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);">No professors found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
