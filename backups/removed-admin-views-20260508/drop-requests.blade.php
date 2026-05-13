@extends('layouts.admin')

@section('title', 'Drop Requests')
@section('header', 'Drop Requests')
@section('subheader', 'Review and approve or reject student drop requests submitted by professors.')

@section('content')
<div style="margin-bottom:12px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <div class="sh" style="margin:0;">Pending and reviewed drop requests</div>
</div>

<div class="tbl-wrap">
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
            @forelse($requests as $request)
                <tr>
                    <td class="td-mono">{{ $request->id }}</td>
                    <td>{{ $request->classe->code }} - {{ $request->classe->name }}</td>
                    <td>{{ $request->student->name }}<br><span style="font-size:10px;color:var(--text2);">{{ $request->student->email }}</span></td>
                    <td>{{ $request->professor->name }}</td>
                    <td style="max-width:180px;font-size:12px;color:var(--text2);white-space:pre-wrap;">{{ $request->reason ?: 'No reason provided' }}</td>
                    <td>
                        <span class="badge {{ $request->status === 'approved' ? 'bg' : ($request->status === 'rejected' ? 'br' : '') }}" style="background: {{ $request->status === 'pending' ? 'rgba(255, 165, 0, 0.16)' : '' }}; color: {{ $request->status === 'pending' ? 'var(--text)' : '' }}; border: {{ $request->status === 'pending' ? 'none' : '' }};">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td class="td-mono">{{ $request->created_at->tz('UTC')->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                    <td style="vertical-align:middle;">
                        <div style="display:inline-flex;gap:6px;align-items:center;white-space:nowrap;">
                            @if($request->status === 'pending')
                                <form method="POST" action="{{ route('admin.drop-requests.approve', $request) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-p">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.drop-requests.reject', $request) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-d">Reject</button>
                                </form>
                            @else
                                <span style="font-size:12px;color:var(--text2);">No actions available</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text2);">No drop requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $requests->firstItem() ?? 0 }} to {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} requests</span><div>{{ $requests->links() }}</div></div>
</div>
@endsection
