@extends('layouts.admin-new')

@section('title', 'QR Codes - QR Attendance Admin')
@section('pageTitle', 'Student QR Codes')
@section('pageSubtitle', 'View and manage student attendance QR codes.')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
<style>
  .qr-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  .qr-modal.active {
    display: flex;
  }

  .qr-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(1, 4, 18, 0.75);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
  }

  .qr-modal-content {
    position: relative;
    width: min(420px, 92vw);
    border-radius: 24px;
    padding: 20px;
    z-index: 1;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
  }

  .qr-modal-close {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
  }

  .qr-modal-frame {
    background: #fff;
    border-radius: 12px;
    padding: 8px;
    width: 100%;
    max-width: 280px;
    margin: 0 auto;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.25);
  }

  .qr-modal-frame img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
  }
</style>

<div class="glass-table glass">
  <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:12px">
      <h3 style="font-size:16px;font-weight:800;margin:0">Student QR Codes</h3>
      <button class="btn primary" type="button" onclick="downloadAllQRCodes()">📥 Download All</button>
    </div>
    <input type="text" id="tableSearch" placeholder="Search table..." style="flex:1;min-width:200px;max-width:350px;padding:10px 14px;border-radius:var(--radius-md);border:1px solid rgba(255,255,255,.12);background:rgba(8,12,30,.58);color:#fff;font-size:13px" onkeyup="filterTable(this)">
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
            @forelse($student->enrolledClasses as $classe)
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
            <button
              type="button"
              class="btn slim"
              data-url="{{ route('admin.students.qr-code', $student) }}"
              data-student-name="{{ $student->name }}"
              data-student-id="{{ $student->id }}"
              onclick="openQRCodeModal(this.dataset.url, this.dataset.studentName, this.dataset.studentId)"
            >Open</button>
            <button class="btn primary slim" type="button" data-url="{{ route('admin.students.qr-code', $student) }}" data-student-name="{{ $student->name }}" onclick="downloadQRCode(this.dataset.url, this.dataset.studentName)">↓ PNG</button>
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

<div class="qr-modal" id="qrModal">
  <div class="qr-modal-overlay" onclick="closeQRCodeModal()"></div>
  <div class="qr-modal-content glass">
    <button class="qr-modal-close" type="button" onclick="closeQRCodeModal()">×</button>
    <div style="text-align:center">
      <div style="font-size:16px;font-weight:700;margin-bottom:14px;color:#fff">Student QR Code</div>
      <div class="qr-modal-frame">
        <img id="qrModalImage" src="" alt="Student QR code">
      </div>
      <div style="margin-top:14px">
        <div id="qrModalStudentName" style="font-size:18px;font-weight:800;color:#fff"></div>
        <div id="qrModalStudentId" style="font-size:13px;color:rgba(255,255,255,.7);font-family:var(--mono);margin-top:4px"></div>
        <div style="font-size:12px;color:rgba(255,255,255,.6);margin-top:8px">Show to professor for attendance</div>
      </div>
    </div>
  </div>
</div>

<script>
function filterTable(input) {
  const searchValue = input.value.toLowerCase();
  const table = input.closest('.glass-table').querySelector('table');
  const rows = table.querySelectorAll('tbody tr');

  rows.forEach(row => {
    if (row.querySelector('td[colspan]')) return;
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
  });
}

async function svgUrlToPngDataUrl(url) {
  const response = await fetch(url, { credentials: 'same-origin' });
  if (!response.ok) {
    throw new Error('Failed to load QR image');
  }

  const svgText = await response.text();
  const svgBlob = new Blob([svgText], { type: 'image/svg+xml;charset=utf-8' });
  const svgUrl = URL.createObjectURL(svgBlob);

  try {
    const image = new Image();
    const imageLoaded = new Promise((resolve, reject) => {
      image.onload = resolve;
      image.onerror = reject;
    });

    image.src = svgUrl;
    await imageLoaded;

    const canvas = document.createElement('canvas');
    canvas.width = 180;
    canvas.height = 180;

    const context = canvas.getContext('2d');
    context.fillStyle = '#ffffff';
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.drawImage(image, 0, 0, canvas.width, canvas.height);

    return canvas.toDataURL('image/png');
  } finally {
    URL.revokeObjectURL(svgUrl);
  }
}

async function downloadQRCode(url, studentName) {
  const safeFileName = studentName.replace(/[^a-zA-Z0-9-_]/g, '_');

  try {
    const dataUrl = await svgUrlToPngDataUrl(url);
    const link = document.createElement('a');
    link.href = dataUrl;
    link.download = safeFileName + '-qr.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error(error);
    alert('Could not generate the PNG download. Please try again.');
  }
}

async function downloadAllQRCodes() {
  if (!window.JSZip) {
    alert('QR ZIP download is not available right now. Please refresh the page and try again.');
    return;
  }

  const rows = document.querySelectorAll('tbody tr');
  const zip = new JSZip();
  let count = 0;

  for (const row of rows) {
    if (row.querySelector('td[colspan]')) continue;

    const payloadBtn = row.querySelector('button[data-url]');
    const nameCell = row.querySelector('.user-cell');
    if (!payloadBtn || !nameCell) continue;

    const studentName = payloadBtn.dataset.studentName || nameCell.textContent.trim();
    const safeFileName = studentName.replace(/[^a-zA-Z0-9-_]/g, '_');
    const url = payloadBtn.dataset.url;

    const dataUrl = await svgUrlToPngDataUrl(url);

    const base64 = dataUrl.split(',')[1];
    zip.file(`${safeFileName}-qr.png`, base64, { base64: true });
    count++;
  }

  if (count === 0) return;

  const blob = await zip.generateAsync({ type: 'blob' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'student-qr-codes.zip';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  setTimeout(() => URL.revokeObjectURL(link.href), 1000);
}

function openQRCodeModal(url, studentName, studentId) {
  const modal = document.getElementById('qrModal');
  document.getElementById('qrModalImage').src = url;
  document.getElementById('qrModalImage').alt = 'QR code for ' + studentName;
  document.getElementById('qrModalStudentName').textContent = studentName;
  document.getElementById('qrModalStudentId').textContent = 'Student ID: ' + studentId;
  modal.classList.add('active');
}

function closeQRCodeModal() {
  const modal = document.getElementById('qrModal');
  modal.classList.remove('active');
}

document.addEventListener('keydown', function (event) {
  const modal = document.getElementById('qrModal');
  if (event.key === 'Escape' && modal.classList.contains('active')) {
    closeQRCodeModal();
  }
});
</script>

<style>
  body.theme-light .glass-table {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .qr-modal-overlay {
    background: rgba(0,0,0,0.75) !important;
  }
  
  body.theme-light .qr-modal-content {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .qr-modal-close {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light #tableSearch {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light th {
    background: #f9fafb !important;
    color: #374151 !important;
    border-bottom: 1px solid #e5e7eb !important;
  }
  
  body.theme-light td {
    color: #000000 !important;
    border-bottom: 1px solid #e5e7eb !important;
  }
  
  body.theme-light .muted {
    color: #6b7280 !important;
  }
  
  body.theme-light .small-avatar {
    background: #e5e7eb !important;
    border: 1px solid #d1d5db !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill {
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .pill.purple {
    background: #faf5ff !important;
    border-color: #f3e8ff !important;
    color: #7c3aed !important;
  }
  
  body.theme-light .btn {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #000000 !important;
  }
  
  body.theme-light .btn.primary {
    background: #3b82f6 !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
  }
</style>

@endsection
