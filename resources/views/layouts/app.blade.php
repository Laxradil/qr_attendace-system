<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QR Attendance System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --navy: #0d0c1d;
            --purple: #6c5ce7;
            --blue: #0984e3;
            --green: #00b894;
            --amber: #fdcb6e;
            --red: #d63031;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #0d0c1d;
            color: #fff;
            overflow-x: hidden;
        }

        .layout-shell {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            background-color: #0f1025;
            border-right: 1px solid #23243d;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem;
            display: flex;
            flex-direction: column;
            z-index: 20;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 1rem;
        }

        .main-content {
            margin-left: 16rem;
            width: calc(100% - 16rem);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 0;
        }

        .top-bar {
            flex-shrink: 0;
        }

        .content-area {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #0f1025;
            border-right: 1px solid #23243d;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem;
            display: flex;
            flex-direction: column;
            z-index: 20;
            overflow: hidden;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 1rem;
        }

        .sidebar > .border-t {
            margin-top: auto;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.23, 1, 0.320, 1);
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background-color: #2d2c4a;
            color: #6c5ce7;
            border-left-color: #6c5ce7;
            transform: translateX(6px);
        }

        .sidebar-item svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }

        .logout-button {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 9999px;
            color: #cbd5e1;
            transition: all 0.2s ease;
        }

        .logout-button:hover {
            background: rgba(255,255,255,0.1);
            color: #ff6b6b;
        }

        .content-area {
            animation: fadeIn 0.4s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            pointer-events: none;
        }

        .toast {
            background: #1f2937;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 10px;
            font-size: 11px;
            color: #e5e7eb;
            display: flex;
            gap: 10px;
            align-items: center;
            pointer-events: all;
            animation: slideInRight 0.3s cubic-bezier(0.23,1,0.320,1), slideOutRight 0.3s cubic-bezier(0.23,1,0.320,1) 2.7s;
            max-width: 320px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .toast.success {
            border-color: rgba(34, 197, 94, 0.3);
            background: rgba(34, 197, 94, 0.12);
            color: #86efac;
        }

        .toast.error {
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
        }

        .toast.info {
            border-color: rgba(59, 130, 246, 0.3);
            background: rgba(59, 130, 246, 0.12);
            color: #93c5fd;
        }

        .toast-icon {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-message {
            flex: 1;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(24px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(24px);
            }
        }
    </style>
</head>
<body class="bg-navy">
    <div class="layout-shell">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-800">
                <h1 class="text-2xl font-bold text-purple">QR Attendance</h1>
                <p class="text-gray-500 text-xs mt-1">System</p>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-content">
                @if(auth()->user()->isProfessor())
                    <a href="{{ route('professor.dashboard') }}" class="sidebar-item {{ request()->routeIs('professor.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 3l3 3m0 0l3-3m-3 3V7"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('professor.classes') }}" class="sidebar-item {{ request()->routeIs('professor.classes*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
                        </svg>
                        <span>My Classes</span>
                    </a>
                    <a href="{{ route('professor.scan-qr') }}" class="sidebar-item {{ request()->routeIs('professor.scan-qr') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Scan QR</span>
                    </a>
                    <a href="{{ route('professor.attendance-records') }}" class="sidebar-item {{ request()->routeIs('professor.attendance-records') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('professor.schedules') }}" class="sidebar-item {{ request()->routeIs('professor.schedules') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Schedules</span>
                    </a>
                    <a href="{{ route('professor.reports') }}" class="sidebar-item {{ request()->routeIs('professor.reports') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('professor.students') }}" class="sidebar-item {{ request()->routeIs('professor.students') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM12.93 12H0v7a6 6 0 0015.806-1M16 16a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        <span>Students</span>
                    </a>
                    <a href="{{ route('professor.logs') }}" class="sidebar-item {{ request()->routeIs('professor.logs') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Activity Logs</span>
                    </a>
                @elseif(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 3l3 3m0 0l3-3m-3 3V7"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 4.354L9 8.269M12 4.354l3 3.915M9 12a4 4 0 118 0m-8 4h8m-4-8v8"></path>
                        </svg>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.professors') }}" class="sidebar-item {{ request()->routeIs('admin.professors') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.14 3.14a6 6 0 00-8.488 8.488l-3.14 3.14M9 9a6 6 0 018.488 8.488l3.14 3.14M9 9l3 3m-3-3l-3 3"></path>
                        </svg>
                        <span>Professors</span>
                    </a>
                    <a href="{{ route('admin.students') }}" class="sidebar-item {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM12.93 12H0v7a6 6 0 0015.806-1M16 16a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        <span>Students</span>
                    </a>
                    <a href="{{ route('admin.classes') }}" class="sidebar-item {{ request()->routeIs('admin.classes*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
                        </svg>
                        <span>Classes</span>
                    </a>
                    <a href="{{ route('admin.qr-codes') }}" class="sidebar-item {{ request()->routeIs('admin.qr-codes*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Student QR Codes</span>
                    </a>
                    <a href="{{ route('admin.attendance-records') }}" class="sidebar-item {{ request()->routeIs('admin.attendance-records') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="sidebar-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.logs') }}" class="sidebar-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>System Logs</span>
                    </a>
                @elseif(auth()->user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" class="sidebar-item {{ request()->routeIs('student.dashboard') || request()->is('student/dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 3l3 3m0 0l3-3m-3 3V7"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('student.classes') }}" class="sidebar-item {{ request()->routeIs('student.classes*') || request()->is('student/classes') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
                        </svg>
                        <span>My Classes</span>
                    </a>
                    <a href="{{ route('student.attendance') }}" class="sidebar-item {{ request()->routeIs('student.attendance') || request()->is('student/attendance') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="border-t border-gray-800 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-xs capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                        @csrf
                    </form>
                    <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-button" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar bg-gray-900 border-b border-gray-800 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">@yield('header', '')</h2>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white text-sm font-semibold" id="current-time"></p>
                        <p class="text-gray-400 text-xs" id="current-date"></p>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @if($errors->any())
                    <div class="m-6 bg-red-900/20 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="m-6 bg-green-900/20 border border-green-500/50 text-green-200 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="m-6 bg-red-900/20 border border-red-500/50 text-red-200 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Update current time and date
        function updateDateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }
        updateDateTime();
        setInterval(updateDateTime, 60000);

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container') || createToastContainer();
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = '✓';
            if (type === 'error') icon = '✕';
            if (type === 'info') icon = 'ℹ';
            
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-message">${message}</div>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
            return container;
        }

        // Show success toast from session
        @if(session('success'))
            window.addEventListener('load', () => showToast("{{ session('success') }}", 'success'));
        @endif

        // Show error toast from session
        @if(session('error'))
            window.addEventListener('load', () => showToast("{{ session('error') }}", 'error'));
        @endif

        // Show validation errors as toast
        @if($errors->any())
            window.addEventListener('load', () => showToast("{{ $errors->first() }}", 'error'));
        @endif
    </script>
</body>
</html>
