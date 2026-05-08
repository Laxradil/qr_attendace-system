@extends('layouts.admin-new')

@section('title', 'QR Codes - QR Attendance Admin')
@section('pageTitle', 'Student QR Codes')
@section('pageSubtitle', 'View and manage student attendance QR codes.')

@section('content')
<div class="glass-table glass">
  <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px">
    <div style="display:flex;align-items:center;gap:12px">
      <h3 style="font-size:16px;font-weight:800;margin:0">Student QR Codes</h3>
      <button class="btn primary" onclick="downloadAllQRCodes()">📥 Download All</button>
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
            <button class="btn primary slim" onclick="downloadQRCode('{{ route('admin.students.qr-code', $student) }}', '{{ $student->name }}')">↓ PNG</button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No students found</td>
        </tr>
        @endforelse

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
</script>
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

<script>
function downloadQRCode(url, studentName) {
  const link = document.createElement('a');
  link.href = url;
  const safeFileName = studentName.replace(/[^a-zA-Z0-9-_]/g, '_');
  link.download = safeFileName + '-qr.png';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function downloadAllQRCodes() {
  const rows = document.querySelectorAll('tbody tr');
  let count = 0;
  rows.forEach((row, index) => {
    const nameCell = row.querySelector('.user-cell');
    const openBtn = row.querySelector('a[href*="qr-code"]');
    if (nameCell && openBtn) {
      const studentName = nameCell.textContent.trim();
      const qrUrl = openBtn.href;
      setTimeout(() => {
        downloadQRCode(qrUrl, studentName);
      }, index * 500);
      count++;
    }
  });
  if (count > 0) {
    alert(`Downloading ${count} QR codes...`);
  }
}
</script>
@endsection
