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
        <thead><tr><th style="width:80px;">QR Image</th><th>QR Code</th><th>Class</th><th>Professor</th><th>Generated On</th><th>Expires On</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($qrCodes as $qr)
                <tr>
                    <td>
                        <object data="{{ route('admin.qr-codes.image', $qr->uuid) }}" type="image/svg+xml" style="width:60px;height:60px;border-radius:4px;border:1px solid var(--border);"></object>
                    </td>
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
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button type="button" class="btn btn-d" style="padding:4px 8px;font-size:10px;" onclick="viewQR('{{ $qr->uuid }}')">View</button>
                            <button type="button" class="btn btn-p" style="padding:4px 8px;font-size:10px;" onclick="downloadQR('{{ $qr->uuid }}')">Download</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text2);">No QR codes generated yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pag"><span>Showing {{ $qrCodes->firstItem() ?? 0 }} to {{ $qrCodes->lastItem() ?? 0 }} of {{ $qrCodes->total() }} QR codes</span><div>{{ $qrCodes->links() }}</div></div>
</div>

<!-- QR Code Modal -->
<div id="qr-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:24px;border-radius:12px;max-width:400px;width:90%;text-align:center;">
        <div style="font-size:18px;font-weight:600;margin-bottom:16px;">QR Code</div>
        <object id="modal-qr-image" data="" type="image/svg+xml" style="width:200px;height:200px;border:1px solid var(--border);border-radius:8px;margin-bottom:16px;"></object>
        <div id="modal-qr-uuid" style="font-size:12px;color:var(--text2);margin-bottom:16px;font-family:monospace;"></div>
        <div style="display:flex;gap:8px;justify-content:center;">
            <button type="button" class="btn btn-p" onclick="downloadQRFromModal()">Download JPG</button>
            <button type="button" class="btn" onclick="closeQRModal()">Close</button>
        </div>
    </div>
</div>

<script>
let currentQRUuid = '';
const qrImageBaseUrl = "{{ url('/admin/qr-codes') }}/";

function viewQR(uuid) {
    currentQRUuid = uuid;
    document.getElementById('modal-qr-image').data = qrImageBaseUrl + uuid + '/image';
    document.getElementById('modal-qr-uuid').textContent = uuid;
    document.getElementById('qr-modal').style.display = 'flex';
}

function closeQRModal() {
    document.getElementById('qr-modal').style.display = 'none';
}

function downloadQRFromModal() {
    downloadQR(currentQRUuid);
}

function downloadQR(uuid) {
    // For SVG, we can fetch it directly and download as SVG
    // Include credentials to send authentication cookies
    fetch(qrImageBaseUrl + uuid + '/image', { credentials: 'include' })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(svgText => {
            // Check if response is actually SVG (starts with <?xml or <svg)
            if (!svgText.includes('<svg') && !svgText.includes('<?xml')) {
                alert('Error: Invalid response from server');
                return;
            }
            const blob = new Blob([svgText], { type: 'image/svg+xml' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'qr-code-' + uuid + '.svg';
            link.click();
            URL.revokeObjectURL(url);
        })
        .catch(error => {
            console.error('Download failed:', error);
            alert('Failed to download QR code: ' + error.message);
        });
}

// Close modal on outside click
document.getElementById('qr-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});
</script>
@endsection
