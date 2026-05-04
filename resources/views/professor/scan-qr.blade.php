@extends('layouts.professor')

@section('title', 'Scan QR Code - Professor')
@section('header', 'QR Code Scanner')
@section('subheader', 'Scan QR codes to record student attendance')

@section('content')
<div class="content">
    <!-- Mode Toggle -->
    <div style="display:flex;gap:8px;margin-bottom:16px;">
        <button id="mode-camera" onclick="setMode('camera')" class="btn btn-p" style="flex:1;padding:10px 12px;font-size:12px;justify-content:center;">
            📷 Camera Mode
        </button>
        <button id="mode-hardware" onclick="setMode('hardware')" class="btn" style="flex:1;padding:10px 12px;font-size:12px;justify-content:center;">
            🔧 Hardware Scanner
        </button>
    </div>

    <div class="g-6-4" style="gap:18px;">
        <!-- Scanner -->
        <div>
            <!-- Camera Mode -->
            <div id="camera-section">
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
                        ▶ Start Camera
                    </button>
                    <button id="stop-scan" onclick="stopScanner()" class="btn btn-d" style="width:100%;padding:10px 12px;font-size:12px;justify-content:center;opacity:0.5;cursor:not-allowed;" disabled>
                        ⏹ Stop Camera
                    </button>
                </div>
            </div>

            <!-- Hardware Scanner Mode -->
            <div id="hardware-section" style="display:none;">
                <div class="card">
                    <div class="sh" style="margin-top:0;">🔧 Hardware Scanner</div>
                    <div id="scanner-detection-status" class="info" style="margin-bottom:12px;">Scanner detection is ready. Connect a HID scanner or use a keyboard-wedge scanner.</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                        <button type="button" id="connect-scanner" class="btn btn-p" style="padding:10px 12px;font-size:12px;justify-content:center;">Detect Scanner</button>
                        <button type="button" id="disconnect-scanner" class="btn btn-d" style="padding:10px 12px;font-size:12px;justify-content:center;" disabled>Disconnect</button>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label class="fl">Scan QR Code</label>
                        <input type="text" id="hardware-input" placeholder="Point scanner at QR code..." class="fi" style="font-size:14px;padding:12px 10px;">
                        <div id="scanner-device-name" style="font-size:9px;color:var(--text3);margin-top:4px;">📌 Field is auto-focused for hardware scanner input</div>
                    </div>
                </div>
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
                    <div id="scan-feedback" style="display:none;margin-bottom:12px;padding:10px 12px;border-radius:8px;font-size:12px;"></div>
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

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    const recentScans = [];
    let currentMode = 'camera';
    let scanAnimationFrame = null;
    let hardwareScanTimer = null;
    let detectedHidDevice = null;
    const scannerCanvas = document.createElement('canvas');
    const scannerContext = scannerCanvas.getContext('2d');
    const video = document.getElementById('qr-scanner');
    const qrInput = document.getElementById('qr-input');
    const hardwareInput = document.getElementById('hardware-input');
    const connectScannerButton = document.getElementById('connect-scanner');
    const disconnectScannerButton = document.getElementById('disconnect-scanner');
    const scannerDetectionStatus = document.getElementById('scanner-detection-status');
    const scannerDeviceName = document.getElementById('scanner-device-name');
    const classSelect = document.querySelector('select[name="class_id"]');
    const studentSelect = document.querySelector('select[name="student_id"]');

    function isHidSupported() {
        return typeof navigator !== 'undefined' && 'hid' in navigator;
    }

    function updateScannerStatus(message, type = 'info') {
        if (!scannerDetectionStatus) {
            return;
        }

        scannerDetectionStatus.textContent = message;
        scannerDetectionStatus.style.color = type === 'error' ? '#ffdddd' : 'var(--text)';
        scannerDetectionStatus.style.background = type === 'error' ? 'rgba(255, 0, 0, 0.12)' : 'transparent';
        scannerDetectionStatus.style.border = type === 'error' ? '1px solid rgba(255, 0, 0, 0.25)' : 'none';
    }

    function setDetectedDeviceName(name) {
        if (!scannerDeviceName) {
            return;
        }

        scannerDeviceName.textContent = name ? `Connected scanner: ${name}` : '📌 Field is auto-focused for hardware scanner input';
    }

    function setScannerConnectionControls(connected) {
        if (connectScannerButton) {
            connectScannerButton.disabled = connected;
        }
        if (disconnectScannerButton) {
            disconnectScannerButton.disabled = !connected;
        }
    }

    async function disconnectScanner() {
        clearHardwareScanTimer();

        if (detectedHidDevice && detectedHidDevice.opened) {
            try {
                await detectedHidDevice.close();
            } catch (err) {
                console.error('Failed to close HID device:', err);
            }
        }

        detectedHidDevice = null;
        setDetectedDeviceName('');
        setScannerConnectionControls(false);
        updateScannerStatus('Scanner disconnected. You can connect another device or use the text field fallback.');
        hardwareInput.focus();
    }

    async function connectScanner() {
        if (!isHidSupported()) {
            updateScannerStatus('This browser does not support HID scanner detection. Use Chrome or Edge on a secure page.', 'error');
            return;
        }

        try {
            const grantedDevices = await navigator.hid.getDevices();
            if (grantedDevices.length > 0) {
                detectedHidDevice = grantedDevices[0];
            } else {
                const devices = await navigator.hid.requestDevice({ filters: [] });
                detectedHidDevice = devices[0] || null;
            }

            if (!detectedHidDevice) {
                updateScannerStatus('No HID scanner was selected. If your scanner behaves like a keyboard, the input field fallback still works.', 'error');
                return;
            }

            if (!detectedHidDevice.opened) {
                await detectedHidDevice.open();
            }

            setDetectedDeviceName(detectedHidDevice.productName || 'HID device');
            setScannerConnectionControls(true);
            updateScannerStatus('Scanner connected. HID-supported scanners can now be detected by the browser. Keyboard-wedge scanners still work through the input field.');
            hardwareInput.focus();
        } catch (err) {
            console.error('Scanner detection failed:', err);
            updateScannerStatus(`Unable to detect scanner: ${err.message}`, 'error');
        }
    }

    function clearHardwareScanTimer() {
        if (hardwareScanTimer) {
            clearTimeout(hardwareScanTimer);
            hardwareScanTimer = null;
        }
    }

    function processHardwareScan() {
        if (currentMode !== 'hardware') {
            return;
        }

        const scannedCode = hardwareInput.value.trim();
        if (!scannedCode) {
            return;
        }

        clearHardwareScanTimer();
        handleScannedCode(scannedCode);
        hardwareInput.value = '';
        hardwareInput.focus();
        console.log('Hardware scanner detected:', scannedCode);
    }

    function scheduleHardwareScanProcessing() {
        if (currentMode !== 'hardware') {
            return;
        }

        clearHardwareScanTimer();
        hardwareScanTimer = setTimeout(processHardwareScan, 150);
    }

    function setMode(mode) {
        currentMode = mode;
        const cameraBtn = document.getElementById('mode-camera');
        const hardwareBtn = document.getElementById('mode-hardware');
        const cameraSection = document.getElementById('camera-section');
        const hardwareSection = document.getElementById('hardware-section');

        if (mode === 'camera') {
            cameraBtn.classList.add('btn-p');
            cameraBtn.classList.remove('btn');
            hardwareBtn.classList.remove('btn-p');
            hardwareBtn.classList.add('btn');
            cameraSection.style.display = 'block';
            hardwareSection.style.display = 'none';
            clearHardwareScanTimer();
            qrInput.focus();
        } else {
            cameraBtn.classList.remove('btn-p');
            cameraBtn.classList.add('btn');
            hardwareBtn.classList.add('btn-p');
            hardwareBtn.classList.remove('btn');
            cameraSection.style.display = 'none';
            hardwareSection.style.display = 'block';
            if (!detectedHidDevice) {
                updateScannerStatus(isHidSupported()
                    ? 'Scanner detection is ready. Click Detect Scanner to choose a plugged-in HID scanner.'
                    : 'This browser cannot detect HID scanners. Use Chrome or Edge on HTTPS, or use the keyboard-wedge input fallback.',
                    isHidSupported() ? 'info' : 'error'
                );
            }
            hardwareInput.focus();
        }
    }

    // Initialize with camera mode
    setMode('camera');

    async function startScanner() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            video.srcObject = stream;
            await video.play();
            document.getElementById('start-scan').disabled = true;
            document.getElementById('start-scan').style.opacity = '0.5';
            document.getElementById('start-scan').style.cursor = 'not-allowed';
            document.getElementById('stop-scan').disabled = false;
            document.getElementById('stop-scan').style.opacity = '1';
            document.getElementById('stop-scan').style.cursor = 'pointer';
            scanAnimationFrame = requestAnimationFrame(scanFrame);
        } catch (err) {
            alert('Unable to access camera: ' + err.message);
        }
    }

    function stopScanner() {
        if (scanAnimationFrame) {
            cancelAnimationFrame(scanAnimationFrame);
            scanAnimationFrame = null;
        }
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

    function scanFrame() {
        if (!video || !video.videoWidth || !video.videoHeight) {
            scanAnimationFrame = requestAnimationFrame(scanFrame);
            return;
        }

        scannerCanvas.width = video.videoWidth;
        scannerCanvas.height = video.videoHeight;
        scannerContext.drawImage(video, 0, 0, scannerCanvas.width, scannerCanvas.height);

        const imageData = scannerContext.getImageData(0, 0, scannerCanvas.width, scannerCanvas.height);
        const code = jsQR(imageData.data, scannerCanvas.width, scannerCanvas.height, { inversionAttempts: 'attemptBoth' });

        if (code && code.data) {
            handleScannedCode(code.data);
            stopScanner();
            return;
        }

        scanAnimationFrame = requestAnimationFrame(scanFrame);
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

    function setScanFeedback(message, type = 'info') {
        const feedback = document.getElementById('scan-feedback');
        if (!feedback) {
            return;
        }

        if (!message) {
            feedback.style.display = 'none';
            feedback.textContent = '';
            return;
        }

        feedback.style.display = 'block';
        feedback.textContent = message;
        feedback.style.color = type === 'error' ? '#ffdddd' : '#0a0';
        feedback.style.background = type === 'error' ? 'rgba(255, 0, 0, 0.12)' : 'rgba(0, 128, 0, 0.12)';
        feedback.style.border = type === 'error' ? '1px solid rgba(255, 0, 0, 0.25)' : '1px solid rgba(0, 128, 0, 0.25)';
    }

    async function populateStudents(classId, selectedStudentId = null) {
        if (!classId) {
            studentSelect.innerHTML = '<option value="">Select a student...</option>';
            return;
        }
        studentSelect.innerHTML = '<option value="">Loading students...</option>';
        try {
            const response = await fetch(`/professor/class/${classId}/students`);
            const data = await response.json();
            studentSelect.innerHTML = '<option value="">Select a student...</option>';
            data.students.forEach(student => {
                const opt = document.createElement('option');
                opt.value = student.id;
                opt.textContent = `${student.name} (${student.email})`;
                if (selectedStudentId && selectedStudentId == student.id) {
                    opt.selected = true;
                }
                studentSelect.appendChild(opt);
            });
        } catch (err) {
            studentSelect.innerHTML = '<option value="">Unable to load students</option>';
            console.error('Failed to load students:', err);
        }
    }

    function handleScannedCode(scannedCode) {
        const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        recentScans.unshift({ code: scannedCode, time });
        updateRecentScans();
        qrInput.value = scannedCode;

        try {
            const data = JSON.parse(scannedCode);
            if (data.type === 'student_attendance') {
                setScanFeedback('Student QR scanned. Class and student are now selected. Review and submit.', 'info');
                if (data.class_id) {
                    classSelect.value = data.class_id;
                    populateStudents(data.class_id, data.student_id);
                }
                return;
            }

            setScanFeedback('QR scanned. Please confirm the class and student, then submit.', 'info');
        } catch (_err) {
            setScanFeedback('Scanned raw QR text. Please select a class and student before submitting.', 'error');
        }
    }

    document.getElementById('qr-input').addEventListener('change', (e) => {
        if (e.target.value && currentMode === 'camera') {
            handleScannedCode(e.target.value.trim());
            e.target.value = '';
        }
    });

    hardwareInput.addEventListener('input', () => {
        scheduleHardwareScanProcessing();
    });

    hardwareInput.addEventListener('keydown', (e) => {
        if (currentMode !== 'hardware') {
            return;
        }

        if (e.key === 'Enter' || e.key === 'Tab') {
            e.preventDefault();
            processHardwareScan();
        }
    });

    hardwareInput.addEventListener('paste', () => {
        scheduleHardwareScanProcessing();
    });

    if (connectScannerButton) {
        connectScannerButton.addEventListener('click', connectScanner);
    }

    if (disconnectScannerButton) {
        disconnectScannerButton.addEventListener('click', disconnectScanner);
    }

    if (isHidSupported()) {
        navigator.hid.addEventListener('connect', (event) => {
            detectedHidDevice = event.device;
            setDetectedDeviceName(event.device.productName || 'Connected HID device');
            setScannerConnectionControls(true);
            updateScannerStatus(`Scanner connected: ${event.device.productName || 'HID device'}`);
        });

        navigator.hid.addEventListener('disconnect', () => {
            disconnectScanner();
        });
    }

    updateScannerStatus(isHidSupported()
        ? 'Scanner detection is ready. Click Detect Scanner to choose a plugged-in HID scanner.'
        : 'This browser cannot detect HID scanners. Use Chrome or Edge on HTTPS, or use the keyboard-wedge input fallback.',
        isHidSupported() ? 'info' : 'error'
    );

    classSelect.addEventListener('change', () => {
        populateStudents(classSelect.value);
    });
</script>
@endsection
