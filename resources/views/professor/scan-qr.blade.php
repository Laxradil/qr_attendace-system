@extends('layouts.app')

@section('title', 'Scan QR Code - Professor')
@section('header', 'QR Code Scanner')

@section('content')
<div class="p-6 space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Scanner -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
                <div class="aspect-video bg-black flex items-center justify-center relative">
                    <video id="qr-scanner" class="w-full h-full object-cover"></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-64 h-64 border-2 border-purple-500 rounded-lg"></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button id="start-scan" onclick="startScanner()" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg font-semibold transition">
                    Start Scanner
                </button>
                <button id="stop-scan" onclick="stopScanner()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-semibold transition" disabled>
                    Stop Scanner
                </button>
            </div>
        </div>

        <!-- Attendance Form -->
        <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 space-y-4">
            <h2 class="text-xl font-bold text-white">Record Attendance</h2>
            
            <form id="attendance-form" action="{{ route('professor.attendance.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Class Selection -->
                <div>
                    <label class="block text-gray-300 text-sm font-semibold mb-2">Select Class</label>
                    <select name="class_id" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none">
                        <option value="">Choose a class...</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->code }} - {{ $classe->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Student Selection -->
                <div>
                    <label class="block text-gray-300 text-sm font-semibold mb-2">Student</label>
                    <select name="student_id" required class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none">
                        <option value="">Select a student...</option>
                    </select>
                </div>

                <!-- QR Code Input -->
                <div>
                    <label class="block text-gray-300 text-sm font-semibold mb-2">QR Code</label>
                    <input type="text" name="qr_code" id="qr-input" placeholder="QR code will appear here..." class="w-full bg-gray-800 border border-gray-700 text-white rounded px-3 py-2 focus:border-purple-500 outline-none" required>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                    Record Attendance
                </button>
            </form>

            <!-- Recent Scans -->
            <div class="pt-4 border-t border-gray-700">
                <h3 class="text-white font-semibold mb-3">Recent Scans</h3>
                <div id="recent-scans" class="space-y-2 max-h-48 overflow-y-auto">
                    <p class="text-gray-400 text-sm">No scans yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let scanner;
    const recentScans = [];

    async function startScanner() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            document.getElementById('qr-scanner').srcObject = stream;
            document.getElementById('start-scan').disabled = true;
            document.getElementById('stop-scan').disabled = false;
        } catch (err) {
            alert('Unable to access camera: ' + err.message);
        }
    }

    function stopScanner() {
        const stream = document.getElementById('qr-scanner').srcObject;
        stream.getTracks().forEach(track => track.stop());
        document.getElementById('start-scan').disabled = false;
        document.getElementById('stop-scan').disabled = true;
    }

    // Simulate QR code scanning
    document.getElementById('qr-scanner').addEventListener('change', (e) => {
        if (e.target.value) {
            document.getElementById('qr-input').value = e.target.value;
            const time = new Date().toLocaleTimeString();
            recentScans.unshift({ code: e.target.value, time });
            updateRecentScans();
        }
    });

    function updateRecentScans() {
        const container = document.getElementById('recent-scans');
        if (recentScans.length === 0) {
            container.innerHTML = '<p class="text-gray-400 text-sm">No scans yet</p>';
            return;
        }
        container.innerHTML = recentScans.slice(0, 5).map(scan => 
            `<div class="bg-gray-800 p-2 rounded text-xs text-gray-300">
                <div class="font-mono truncate">${scan.code}</div>
                <div class="text-gray-500">${scan.time}</div>
            </div>`
        ).join('');
    }
</script>
@endsection
