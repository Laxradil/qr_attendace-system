@extends('layouts.professor')

@section('title', 'Scan QR Code - Professor')
@section('header', 'QR Code Scanner')
@section('subheader', 'Scan QR codes to record student attendance')

@section('content')
<div class="content">
    <div class="g-6-4" style="gap:18px;">
        <!-- Scanner -->
        <div>
            <div class="card" style="margin-bottom:0;padding:0;border-radius:var(--radius-lg);overflow:hidden;">
                <div style="width:100%;padding-top:56.25%;position:relative;background:var(--navy3);">
                    <video id="qr-scanner" style="position:absolute;top:0;left:0;width:100%;height:100%;display:block;object-fit:cover;"></video>
                    <div style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                        <div style="width:250px;height:250px;border:2px solid var(--purple);border-radius:var(--radius-lg);box-shadow:0 0 20px rgba(108,92,231,0.3);"></div>
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px;">
                <button id="start-scan" onclick="startScanner()" class="btn btn-p" style="width:100%;padding:10px 12px;font-size:12px;justify-content:center;">
                    ▶ Start Scanner
                </button>
                <button id="stop-scan" onclick="stopScanner()" class="btn btn-d" style="width:100%;padding:10px 12px;font-size:12px;justify-content:center;opacity:0.5;cursor:not-allowed;" disabled>
                    ⏹ Stop Scanner
                </button>
            </div>
        </div>

        <!-- Attendance Form -->
        <div>
            <div class="card">
                <div class="sh" style="margin-top:0;">Record Attendance</div>
                
                <form id="attendance-form" action="{{ route('professor.attendance.store') }}" method="POST">
                    @csrf
                    
                    <!-- Class Selection -->
                    <div style="margin-bottom:12px;">
                        <label class="fl">Select Class</label>
                        <select name="class_id" required class="fi">
                            <option value="">Choose a class...</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->code }} - {{ $classe->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Selection -->
                    <div style="margin-bottom:12px;">
                        <label class="fl">Student</label>
                        <select name="student_id" required class="fi">
                            <option value="">Select a student...</option>
                        </select>
                    </div>

                    <!-- QR Code Input -->
                    <div style="margin-bottom:12px;">
                        <label class="fl">QR Code</label>
                        <input type="text" name="qr_code" id="qr-input" placeholder="QR code will appear here..." class="fi" required>
                    </div>

                    <button type="submit" class="btn btn-g" style="width:100%;padding:10px 12px;justify-content:center;margin-bottom:12px;">
                        ✓ Record Attendance
                    </button>
                </form>

                <!-- Recent Scans -->
                <div style="padding-top:12px;border-top:1px solid var(--border);margin-top:12px;">
                    <div class="sh" style="margin-top:0;margin-bottom:8px;">Recent Scans</div>
                    <div id="recent-scans" style="font-size:10px;max-height:200px;overflow-y:auto;">
                        <div style="color:var(--text2);padding:8px 0;">No scans yet</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #qr-scanner {
        display: block;
    }
    
    #recent-scans > div {
        background: var(--navy3);
        padding: 8px 10px;
        border-radius: var(--radius);
        margin-bottom: 6px;
        border: 1px solid var(--border);
        color: var(--text);
    }
    
    #recent-scans > div .scan-code {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        word-break: break-all;
        margin-bottom: 3px;
    }
    
    #recent-scans > div .scan-time {
        font-size: 9px;
        color: var(--text2);
    }
</style>

<script>
    const recentScans = [];

    async function startScanner() {
        try {
            const video = document.getElementById('qr-scanner');
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            video.srcObject = stream;
            video.play();
            document.getElementById('start-scan').disabled = true;
            document.getElementById('start-scan').style.opacity = '0.5';
            document.getElementById('start-scan').style.cursor = 'not-allowed';
            document.getElementById('stop-scan').disabled = false;
            document.getElementById('stop-scan').style.opacity = '1';
            document.getElementById('stop-scan').style.cursor = 'pointer';
        } catch (err) {
            alert('Unable to access camera: ' + err.message);
        }
    }

    function stopScanner() {
        const video = document.getElementById('qr-scanner');
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
        document.getElementById('start-scan').disabled = false;
        document.getElementById('start-scan').style.opacity = '1';
        document.getElementById('start-scan').style.cursor = 'pointer';
        document.getElementById('stop-scan').disabled = true;
        document.getElementById('stop-scan').style.opacity = '0.5';
        document.getElementById('stop-scan').style.cursor = 'not-allowed';
    }

    function updateRecentScans() {
        const container = document.getElementById('recent-scans');
        if (recentScans.length === 0) {
            container.innerHTML = '<div style="color:var(--text2);padding:8px 0;">No scans yet</div>';
            return;
        }
        container.innerHTML = recentScans.slice(0, 5).map(scan => 
            `<div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div class="scan-code">${scan.code}</div>
                <div class="scan-time">${scan.time}</div>
            </div>`
        ).join('');
    }

    // Simulate manual QR code input (for testing without QR scanner library)
    document.getElementById('qr-input').addEventListener('change', (e) => {
        if (e.target.value) {
            const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            recentScans.unshift({ code: e.target.value, time });
            updateRecentScans();
            e.target.value = '';
        }
    });
</script>
@endsection
