@extends(request()->query('modal') ? 'layouts.blank' : 'layouts.professor')

@section('title', 'Scan QR Code - Professor')
@section('header', 'QR Code Scanner')
@section('subheader', 'Web Scanner - Scan QR codes to record student attendance')

@section('content')
<div class="content">
    <div id="scan-qr-config" data-auto-start-camera="{{ request()->boolean('modal') ? 'true' : 'false' }}" hidden></div>

    <!-- Mode Toggle -->
    <div class="mode-toggle-group">
        <button id="mode-camera" onclick="setMode('camera')" class="btn btn-p mode-toggle-btn">📷 Camera Mode</button>
        <button id="mode-hardware" onclick="setMode('hardware')" class="btn mode-toggle-btn">🔧 Hardware Scanner</button>
    </div>

    <!-- Main Layout -->
    <div class="scanner-layout">
        <!-- Scanner Section -->
        <div class="scanner-section">
            <!-- Camera Mode -->
            <div id="camera-section">
                <div class="scanner-header">
                    <div class="sh">QR Scanner</div>
                    <div id="scanner-status-badge" class="status-badge ready">Ready to Scan</div>
                </div>
                <div class="scanner-card">
                    <div class="scanner-preview">
                        <video id="qr-scanner"></video>
                        <div class="scanner-reticle"></div>
                    </div>
                </div>
                <div class="scanner-controls">
                    <button id="start-scan" onclick="startScanner()" class="btn btn-p btn-full">▶ Start Camera</button>
                    <button id="stop-scan" onclick="stopScanner()" class="btn btn-d btn-full btn-disabled" disabled>⏹ Stop Camera</button>
                </div>
            </div>

            <!-- Hardware Scanner Mode -->
            <div id="hardware-section" style="display:none;">
                <div class="card">
                    <div class="scanner-header">
                        <div class="sh">Hardware Scanner</div>
                        <div id="hardware-status-badge" class="status-badge ready">Ready</div>
                    </div>
                    <div id="scanner-detection-status" class="info" style="margin-bottom:12px;">Scanner detection is ready. Connect a HID scanner or use a keyboard-wedge scanner.</div>
                    <div class="scanner-buttons">
                        <button type="button" id="connect-scanner" class="btn btn-p">📡 Detect Scanner</button>
                        <button type="button" id="disconnect-scanner" class="btn btn-d" disabled>✕ Disconnect</button>
                    </div>
                    <div class="form-field-group">
                        <label class="fl">Scan QR Code</label>
                        <input type="text" id="hardware-input" placeholder="Point scanner at QR code..." class="fi">
                        <div id="scanner-device-name" class="device-status">📌 Field is auto-focused for hardware scanner input</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <!-- Attendance Form Card -->
            <div class="card form-card">
                <div class="sh" style="margin-top:0;">Attendance Recording</div>

                <form id="attendance-form" action="{{ route('professor.attendance.store') }}" method="POST">
                    @csrf

                    <div class="form-field-group">
                        <label class="fl">
                            Select Class <span class="required-indicator">*</span>
                        </label>
                        <select name="class_id" required class="fi" id="class-select" {{ $selectedClassId ? 'disabled' : '' }}>
                            <option value="">Choose a class...</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ old('class_id', $selectedClassId) == $classe->id ? 'selected' : '' }}>{{ $classe->display_name }}</option>
                            @endforeach
                        </select>
                        @if($selectedClassId)
                            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                        @endif
                    </div>

                    <div class="form-field-group">
                        <label class="fl">
                            Student <span class="required-indicator">*</span>
                        </label>
                        <select name="student_id" required class="fi">
                            <option value="">Select a student...</option>
                        </select>
                    </div>

                    <div class="form-field-group">
                        <label class="fl">
                            QR Code <span class="required-indicator">*</span>
                        </label>
                        <input type="text" name="qr_code" id="qr-input" placeholder="QR code will appear here..." class="fi" required>
                    </div>

                    <div id="scan-feedback" class="scan-feedback"></div>
                </form>
            </div>

            <!-- Recent Scans Card -->
            <div class="card recent-scans-card">
                <div class="sh" style="margin-top:0;margin-bottom:12px;">Recent Scans</div>
                <div id="recent-scans" class="recent-scans-list">
                    <div class="empty-state">No scans yet</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== Mode Toggle ===== */
    .mode-toggle-group {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .mode-toggle-btn {
        flex: 1;
        padding: 10px 14px;
        font-size: 12px;
        justify-content: center;
        font-weight: 600;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .mode-toggle-btn:hover {
        background-color: var(--navy3);
    }

    .mode-toggle-btn.btn-p {
        background: var(--purple);
        border-color: var(--purple);
        color: white;
    }

    .mode-toggle-btn.btn-p:hover {
        background: var(--purple2);
        border-color: var(--purple2);
    }

    /* ===== Main Layout ===== */
    .scanner-layout {
        display: flex;
        gap: 18px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .scanner-section {
        flex: 0 0 420px;
        min-width: 320px;
    }

    .form-section {
        flex: 1;
        min-width: 320px;
    }

    /* ===== Scanner Header ===== */
    .scanner-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 12px;
    }

    .scanner-header .sh {
        margin: 0;
        flex: 1;
    }

    /* ===== Status Badge ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .status-badge.ready {
        background: var(--green-bg);
        color: var(--green);
        border: 1px solid rgba(0, 184, 148, 0.3);
    }

    .status-badge.scanning {
        background: var(--blue-bg);
        color: var(--blue);
        border: 1px solid rgba(9, 132, 227, 0.3);
    }

    .status-badge.scanned {
        background: var(--green-bg);
        color: var(--green);
        border: 1px solid rgba(0, 184, 148, 0.3);
    }

    /* ===== Scanner Cards ===== */
    .scanner-card {
        padding: 0;
        margin-bottom: 0;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .scanner-preview {
        width: 100%;
        height: 300px;
        position: relative;
        background: var(--navy3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #qr-scanner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    .scanner-reticle {
        position: absolute;
        width: 200px;
        height: 200px;
        border: 2px solid var(--purple);
        border-radius: var(--radius-lg);
        box-shadow: 0 0 20px rgba(108, 92, 231, 0.4), inset 0 0 20px rgba(108, 92, 231, 0.15);
        pointer-events: none;
        z-index: 10;
    }

    .scanner-controls {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 12px;
    }

    .btn-full {
        width: 100%;
        padding: 10px 12px;
        font-size: 12px;
        justify-content: center;
        font-weight: 600;
    }

    .btn-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .scanner-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .scanner-buttons .btn {
        flex: 1;
        min-width: 120px;
        padding: 10px 12px;
        font-size: 12px;
        justify-content: center;
    }

    /* ===== Form Styling ===== */
    .form-card {
        padding: 18px;
    }

    .form-field-group {
        margin-bottom: 16px;
    }

    .form-field-group:last-of-type {
        margin-bottom: 12px;
    }

    .required-indicator {
        color: var(--red);
        font-weight: 700;
    }

    /* ===== Feedback Messages ===== */
    .scan-feedback {
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 12px;
        margin-bottom: 12px;
        display: none;
        animation: slideIn 0.2s ease-out;
    }

    .scan-feedback.show {
        display: block;
    }

    .scan-feedback.success {
        background: var(--green-bg);
        color: var(--green);
        border: 1px solid rgba(0, 184, 148, 0.3);
    }

    .scan-feedback.error {
        background: var(--red-bg);
        color: var(--red);
        border: 1px solid rgba(214, 48, 49, 0.3);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== Recent Scans ===== */
    .recent-scans-card {
        margin-top: 16px;
        padding: 18px;
    }

    .recent-scans-list {
        font-size: 10px;
        max-height: 220px;
        overflow-y: auto;
    }

    .recent-scans-list .empty-state {
        color: var(--text2);
        padding: 12px 0;
        text-align: center;
        font-style: italic;
    }

    .recent-scans-list > div:not(.empty-state) {
        background: var(--navy3);
        padding: 10px 12px;
        border-radius: var(--radius);
        margin-bottom: 8px;
        border: 1px solid var(--border);
        color: var(--text);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        transition: background-color 0.15s ease;
    }

    .recent-scans-list > div:not(.empty-state):hover {
        background: var(--navy4);
    }

    .scan-code {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        word-break: break-all;
        flex: 1;
        color: var(--purple-light);
    }

    .scan-time {
        font-size: 9px;
        color: var(--text2);
        flex-shrink: 0;
    }

    /* ===== Device Status ===== */
    .device-status {
        font-size: 9px;
        color: var(--text3);
        margin-top: 6px;
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 900px) {
        .scanner-layout {
            flex-direction: column;
        }

        .scanner-section {
            flex: 1;
            width: 100%;
        }

        .form-section {
            width: 100%;
        }

        .scanner-preview {
            height: 280px;
        }

        .scanner-reticle {
            width: 180px;
            height: 180px;
        }
    }

    @media (max-width: 640px) {
        .mode-toggle-group {
            gap: 8px;
            margin-bottom: 16px;
        }

        .mode-toggle-btn {
            padding: 8px 10px;
            font-size: 11px;
        }

        .scanner-controls {
            gap: 8px;
        }

        .scanner-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .status-badge {
            align-self: flex-start;
        }

        .form-field-group {
            margin-bottom: 14px;
        }

        .scanner-buttons {
            gap: 8px;
        }

        .scanner-buttons .btn {
            min-width: 100px;
            padding: 8px 10px;
            font-size: 11px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    // State
    const recentScans = [];
    let currentMode = 'camera';
    let scanAnimationFrame = null;
    let hardwareScanTimer = null;
    let detectedHidDevice = null;
    let scannedStudentId = null;
    let isAutoSubmitting = false;

    // DOM elements
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
    const attendanceForm = document.getElementById('attendance-form');

    // HID Support
    const isHidSupported = () => typeof navigator !== 'undefined' && 'hid' in navigator;

    // Scanner Status
    function updateScannerStatus(message, type = 'info') {
        if (!scannerDetectionStatus) return;
        scannerDetectionStatus.textContent = message;
        scannerDetectionStatus.className = `info ${type === 'error' ? 'error-state' : ''}`;
    }

    function setDetectedDeviceName(name) {
        if (!scannerDeviceName) return;
        scannerDeviceName.textContent = name ? `Connected scanner: ${name}` : '📌 Field is auto-focused for hardware scanner input';
    }

    function setScannerConnectionControls(connected) {
        if (connectScannerButton) connectScannerButton.disabled = connected;
        if (disconnectScannerButton) disconnectScannerButton.disabled = !connected;
    }

    // Hardware Scanner
    function clearHardwareScanTimer() {
        if (hardwareScanTimer) {
            clearTimeout(hardwareScanTimer);
            hardwareScanTimer = null;
        }
    }

    function processHardwareScan() {
        if (currentMode !== 'hardware') return;
        const scannedCode = hardwareInput.value.trim();
        if (!scannedCode) return;
        clearHardwareScanTimer();
        handleScannedCode(scannedCode);
        hardwareInput.value = '';
        hardwareInput.focus();
    }

    function scheduleHardwareScanProcessing() {
        if (currentMode !== 'hardware') return;
        clearHardwareScanTimer();
        hardwareScanTimer = setTimeout(processHardwareScan, 150);
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
            detectedHidDevice = grantedDevices.length > 0 ? grantedDevices[0] : (await navigator.hid.requestDevice({ filters: [] }))[0] || null;

            if (!detectedHidDevice) {
                updateScannerStatus('No HID scanner was selected. If your scanner behaves like a keyboard, the input field fallback still works.', 'error');
                return;
            }
            if (!detectedHidDevice.opened) await detectedHidDevice.open();
            setDetectedDeviceName(detectedHidDevice.productName || 'HID device');
            setScannerConnectionControls(true);
            updateScannerStatus('Scanner connected. HID-supported scanners can now be detected by the browser.');
            hardwareInput.focus();
        } catch (err) {
            console.error('Scanner detection failed:', err);
            updateScannerStatus(`Unable to detect scanner: ${err.message}`, 'error');
        }
    }

    // Mode Management
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

    // Camera Scanner
    async function startScanner() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            video.srcObject = stream;
            await video.play();
            updateScannerStatusBadge('scanning', '● Scanning');
            document.getElementById('start-scan').classList.add('btn-disabled');
            document.getElementById('start-scan').disabled = true;
            document.getElementById('stop-scan').classList.remove('btn-disabled');
            document.getElementById('stop-scan').disabled = false;
            scanAnimationFrame = requestAnimationFrame(scanFrame);
        } catch (err) {
            alert('Unable to access camera: ' + err.message);
            updateScannerStatusBadge('ready', 'Ready to Scan');
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
        updateScannerStatusBadge('ready', 'Ready to Scan');
        document.getElementById('start-scan').classList.remove('btn-disabled');
        document.getElementById('start-scan').disabled = false;
        document.getElementById('stop-scan').classList.add('btn-disabled');
        document.getElementById('stop-scan').disabled = true;
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

    // Feedback
    function setScanFeedback(message, type = 'info') {
        const feedback = document.getElementById('scan-feedback');
        if (!feedback) return;
        if (!message) {
            feedback.classList.remove('show', 'success', 'error');
            feedback.textContent = '';
            return;
        }
        feedback.textContent = message;
        feedback.classList.remove('success', 'error');
        feedback.classList.add('show', type === 'error' ? 'error' : 'success');
    }

    function updateScannerStatusBadge(status, message) {
        const badge = document.getElementById('scanner-status-badge');
        if (!badge) return;
        badge.textContent = message;
        badge.className = `status-badge ${status}`;
    }

    function updateRecentScans() {
        const container = document.getElementById('recent-scans');
        if (recentScans.length === 0) {
            container.innerHTML = '<div class="empty-state">No scans yet</div>';
            return;
        }
        container.innerHTML = recentScans.slice(0, 5).map(scan =>
            `<div><div class="scan-code">${scan.name || scan.code}</div><div class="scan-time">${scan.time}</div></div>`
        ).join('');
    }

    // Attendance Handling
    async function autoSubmitAttendance() {
        if (!scannedStudentId || !classSelect.value || !studentSelect.value || Number(studentSelect.value) !== Number(scannedStudentId)) return;
        if (isAutoSubmitting) return;
        isAutoSubmitting = true;
        setScanFeedback('Recording attendance...', 'info');
        updateScannerStatusBadge('scanning', '⏳ Recording');
        try {
            const response = await fetch(attendanceForm.action, {
                method: 'POST',
                body: new FormData(attendanceForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || data.message || 'Unable to record attendance');
            setScanFeedback('✓ Attendance recorded successfully!', 'info');
            updateScannerStatusBadge('ready', 'Ready to Scan');
            qrInput.value = '';
            scannedStudentId = null;
            studentSelect.value = '';
            setTimeout(() => setScanFeedback('', 'info'), 2000);
        } catch (err) {
            console.error('Auto-submit failed:', err);
            setScanFeedback('✕ ' + (err.message || 'Failed to record attendance.'), 'error');
            updateScannerStatusBadge('ready', 'Ready to Scan');
        } finally {
            isAutoSubmitting = false;
        }
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
                if (selectedStudentId && selectedStudentId == student.id) opt.selected = true;
                studentSelect.appendChild(opt);
            });
            await autoSubmitAttendance();
        } catch (err) {
            studentSelect.innerHTML = '<option value="">Unable to load students</option>';
            console.error('Failed to load students:', err);
        }
    }

    async function handleScannedCode(scannedCode) {
        const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const scanEntry = { code: scannedCode, name: scannedCode, time };
        qrInput.value = scannedCode;
        updateScannerStatusBadge('scanned', '✓ Scanned');
        try {
            const data = JSON.parse(scannedCode);
            if (data.type === 'student_attendance') {
                scannedStudentId = data.student_id;
                scanEntry.name = `${data.student_name} (${data.student_email})`;
                setScanFeedback('✓ Student QR detected. Recording attendance automatically once class is selected...', 'info');
                if (classSelect.value) await populateStudents(classSelect.value, scannedStudentId);
                recentScans.unshift(scanEntry);
                updateRecentScans();
                return;
            }
            setScanFeedback('QR scanned. Please confirm the class and student, then submit.', 'info');
        } catch (_err) {
            recentScans.unshift(scanEntry);
            updateRecentScans();
            setScanFeedback('Raw QR text scanned. Please select class and student before submitting.', 'error');
        }
    }

    // Event Listeners
    qrInput.addEventListener('change', async (e) => {
        if (e.target.value && currentMode === 'camera') {
            await handleScannedCode(e.target.value.trim());
            e.target.value = '';
        }
    });

    hardwareInput.addEventListener('input', scheduleHardwareScanProcessing);
    hardwareInput.addEventListener('paste', scheduleHardwareScanProcessing);
    hardwareInput.addEventListener('keydown', (e) => {
        if (currentMode !== 'hardware') return;
        if (e.key === 'Enter' || e.key === 'Tab') {
            e.preventDefault();
            processHardwareScan();
        }
    });

    attendanceForm.addEventListener('submit', (e) => {
        e.preventDefault();
        setScanFeedback('Attendance is recorded automatically when a valid student QR is scanned.', 'info');
    });

    connectScannerButton?.addEventListener('click', connectScanner);
    disconnectScannerButton?.addEventListener('click', disconnectScanner);

    if (isHidSupported()) {
        navigator.hid.addEventListener('connect', (event) => {
            detectedHidDevice = event.device;
            setDetectedDeviceName(event.device.productName || 'Connected HID device');
            setScannerConnectionControls(true);
            updateScannerStatus(`Scanner connected: ${event.device.productName || 'HID device'}`);
        });
        navigator.hid.addEventListener('disconnect', disconnectScanner);
    }

    classSelect.addEventListener('change', () => populateStudents(classSelect.value, scannedStudentId));

    // Initialize
    setMode('camera');
    updateScannerStatus(isHidSupported()
        ? 'Scanner detection is ready. Click Detect Scanner to choose a plugged-in HID scanner.'
        : 'This browser cannot detect HID scanners. Use Chrome or Edge on HTTPS, or use the keyboard-wedge input fallback.',
        isHidSupported() ? 'info' : 'error'
    );

    if (classSelect.value) populateStudents(classSelect.value, scannedStudentId);
    if (document.getElementById('scan-qr-config')?.dataset.autoStartCamera === 'true') startScanner();
</script>
@endsection
