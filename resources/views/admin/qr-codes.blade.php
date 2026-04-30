@extends('layouts.admin')

@section('title', 'QR Code Management')
@section('header', 'QR Code Management')
@section('subheader', 'Generate, view and manage QR codes for classes.')

@section('content')
<div class="card" style="margin-bottom:12px;">
    <form action="{{ route('admin.qr-codes.generate') }}" method="POST" style="display:flex;gap:8px;flex-wrap:wrap;">
        @csrf
        <select name="class_id" required class="fi" style="flex:1;min-width:220px;">
            <option value="">Select class...</option>
            @foreach($classes as $classe)
                <option value="{{ $classe->id }}">{{ $classe->code }} - {{ $classe->name }}</option>
            @endforeach
        </select>
        <input class="fi" type="number" name="count" min="1" max="100" value="1" required style="width:120px;" placeholder="Count">
        <input class="fi" type="date" name="expires_at" style="width:180px;">
        <button type="submit" class="btn btn-p">+ Generate QR Code</button>
    </form>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>QR Code</th><th>Class</th><th>Professor</th><th>Generated On</th><th>Expires On</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($qrCodes as $qr)
                <tr>
                    <td>
                        <div class="td-mono" style="color:var(--purple-light);">{{ strtoupper(substr($qr->uuid, 0, 12)) }}</div>
                        <div style="font-size:9px;color:var(--text3);">{{ $qr->uuid }}</div>
                    </td>
                    <td>
                        <div style="font-size:11px;font-weight:500;">{{ $qr->classe->name ?? 'N/A' }}</div>
                        <div style="font-size:9px;color:var(--text2);">{{ $qr->classe->code ?? '-' }}</div>
                    </td>
                    <td style="font-size:10px;">{{ $qr->professor->name ?? 'N/A' }}</td>
                    <td class="td-mono" style="font-size:10px;">{{ $qr->created_at?->format('M d, Y h:i A') }}</td>
                    <td class="td-mono" style="font-size:10px;">{{ $qr->expires_at?->format('M d, Y h:i A') ?: 'No expiry' }}</td>
                    <td>
                        @if($qr->is_used)
                            <span class="badge bb">Used</span>
                        @elseif($qr->isExpired())
                            <span class="badge br">Expired</span>
                        @else
                            <span class="badge bg">Active</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No QR codes generated yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $qrCodes->firstItem() ?? 0 }} to {{ $qrCodes->lastItem() ?? 0 }} of {{ $qrCodes->total() }} QR codes</span><div>{{ $qrCodes->links() }}</div></div>
</div>
@endsection
