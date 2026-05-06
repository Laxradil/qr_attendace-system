<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QR Attendance System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:#0d0c1d;
            --navy2:#13122a;
            --navy3:#1a1938;
            --navy4:#222148;
            --purple:#6c5ce7;
            --purple2:#5a4fcf;
            --purple-light:#a29bfe;
            --purple-glow:rgba(108,92,231,0.15);
            --blue:#0984e3;
            --blue-bg:rgba(9,132,227,0.12);
            --green:#00b894;
            --green-bg:rgba(0,184,148,0.12);
            --amber:#fdcb6e;
            --amber-bg:rgba(253,203,110,0.12);
            --red:#d63031;
            --red-bg:rgba(214,48,49,0.12);
            --text:#e8e6ff;
            --text2:#9b97cc;
            --text3:#5a5880;
            --border:rgba(255,255,255,0.06);
            --border2:rgba(255,255,255,0.03);
            --surface:rgba(255,255,255,0.02);
            --surface2:rgba(255,255,255,0.05);
            --radius:8px;
            --radius-lg:12px;
            --radius-xl:16px;
        }

        body {
            font-family:'Inter', sans-serif;
            background:#090818;
            color:var(--text);
            min-height:100vh;
            font-size:13px;
        }

        .browser {
            width:100%;
            max-width:none;
            margin:0;
            border:none;
            border-radius:0;
            overflow:hidden;
            box-shadow:none;
            min-height:100vh;
        }

        .browser-bar { display:none; }

        .shell { display:flex; height:100vh; overflow:hidden; }

        .sb {
            width:220px;
            flex-shrink:0;
            background:var(--navy2);
            border-right:1px solid var(--border);
            display:flex;
            flex-direction:column;
            padding:14px 10px;
            overflow-y:auto;
        }

        .sb-logo{display:flex;align-items:center;gap:8px;padding:0 6px 14px;border-bottom:1px solid var(--border);margin-bottom:10px;}
        .sb-logo-icon{width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,var(--purple),var(--blue));display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .sb-logo-name{font-size:11px;font-weight:700;line-height:1.2;}
        .sb-logo-sub{font-size:9px;color:var(--purple-light);font-weight:600;letter-spacing:.04em;}
        .sb-user{display:flex;align-items:center;gap:8px;padding:8px;margin-bottom:10px;background:var(--surface2);border-radius:var(--radius);}
        .sb-avatar{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--purple),var(--blue));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;}
        .sb-uname{font-size:11px;font-weight:600;}
        .sb-urole{font-size:9px;color:var(--purple-light);}
        .sb-online{display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--green);margin-right:3px;}
        .sb-sec{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text3);padding:0 8px;margin:8px 0 3px;}
        .sb-item{display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:6px;font-size:11px;font-weight:500;color:var(--text2);cursor:pointer;transition:all .1s;margin-bottom:1px;text-decoration:none;border:none;background:transparent;width:100%;text-align:left;}
        .sb-item:hover{background:var(--surface2);color:var(--text);}
        .sb-item.active{background:var(--purple-glow);color:var(--purple-light);}
        .sb-logout{margin-top:auto;display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:6px;font-size:11px;color:var(--red);cursor:pointer;border:none;background:transparent;width:100%;text-align:left;}
        .sb-logout:hover{background:var(--red-bg);}

        .page-wrap { flex:1; display:flex; flex-direction:column; overflow:hidden; height:100%; }
        .topbar{background:var(--navy2);border-bottom:1px solid var(--border);padding:0 18px;height:52px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
        .topbar-title{font-size:14px;font-weight:700;}
        .topbar-sub{font-size:11px;color:var(--text2);}
        .topbar-right{display:flex;align-items:center;gap:10px;}
        .topbar-date{font-size:10px;color:var(--text3);display:flex;align-items:center;gap:4px;}
        .topbar-clock{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text2);}
        .notif-btn{position:relative;width:30px;height:30px;border-radius:7px;border:1px solid var(--border);background:var(--surface);display:flex;align-items:center;justify-content:center;}
        .notif-dot{position:absolute;top:4px;right:4px;width:7px;height:7px;border-radius:50%;background:var(--purple);border:1.5px solid var(--navy2);}

        .main{flex:1;overflow-y:auto;background:var(--navy);height:calc(100vh - 52px);}
        .main::-webkit-scrollbar{width:4px;}
        .main::-webkit-scrollbar-thumb{background:var(--navy4);border-radius:4px;}
        .content{padding:18px;}

        .stats{display:grid;gap:10px;margin-bottom:16px;}
        .stats-3{grid-template-columns:repeat(3,1fr);}
        .stats-4{grid-template-columns:repeat(4,1fr);}
        .stats-5{grid-template-columns:repeat(5,1fr);}
        .stat{background:var(--navy2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:12px 14px;}
        .stat-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:8px;}
        .stat-val{font-size:20px;font-weight:700;line-height:1;}
        .stat-label{font-size:10px;color:var(--text2);margin-top:3px;}
        .stat-sub{font-size:10px;color:var(--text3);margin-top:1px;}
        .stat-row{display:flex;align-items:center;gap:10px;}
        .stat-row .stat-icon{margin-bottom:0;}

        .g2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;}
        .g-6-4{display:grid;grid-template-columns:1.5fr 1fr;gap:12px;}

        .card{background:var(--navy2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:14px;margin-bottom:10px;}
        .sh{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text3);margin-bottom:8px;margin-top:16px;display:flex;align-items:center;justify-content:space-between;}
        .sh:first-child{margin-top:0;}

        .tbl-wrap{border-radius:var(--radius-lg);border:1px solid var(--border);overflow:hidden;}
        table{width:100%;border-collapse:collapse;font-size:11px;}
        thead{background:var(--navy3);}
        th{text-align:left;padding:9px 12px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text3);border-bottom:1px solid var(--border);white-space:nowrap;}
        td{padding:9px 12px;border-bottom:1px solid var(--border2);color:var(--text);vertical-align:middle;}
        tr:last-child td{border-bottom:none;}
        tbody tr:hover td{background:var(--surface);}

        .badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;}
        .bg{background:var(--green-bg);color:var(--green);} .ba{background:var(--amber-bg);color:var(--amber);} .br{background:var(--red-bg);color:var(--red);} .bb{background:var(--blue-bg);color:var(--blue);} .bp{background:var(--purple-glow);color:var(--purple-light);} .bx{background:var(--surface2);color:var(--text2);}
        .td-mono{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--text2);}

        .btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid var(--border);background:var(--surface2);color:var(--text);cursor:pointer;white-space:nowrap;text-decoration:none;}
        .btn:hover{background:var(--navy3);} .btn-p{background:var(--purple);border-color:var(--purple);color:#fff;} .btn-p:hover{background:var(--purple2);} .btn-sm{padding:3px 9px;font-size:10px;border-radius:5px;} .btn-d{background:var(--red-bg);border-color:rgba(214,48,49,.3);color:var(--red);} .btn-g{background:var(--green-bg);border-color:rgba(0,184,148,.3);color:var(--green);}

        .fi{width:100%;padding:7px 10px;border:1px solid var(--border);border-radius:6px;background:var(--navy3);color:var(--text);font-size:11px;font-family:'Inter',sans-serif;}
        select.fi, input.fi, textarea.fi { appearance: none; }
        .fl{font-size:10px;font-weight:600;color:var(--text2);margin-bottom:4px;display:block;}

        .pag{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-top:1px solid var(--border);font-size:10px;color:var(--text2);}
        .pag-btns{display:flex;gap:3px;} .pb{width:24px;height:24px;display:flex;align-items:center;justify-content:center;border-radius:4px;font-size:10px;border:1px solid var(--border);background:var(--surface);color:var(--text2);} .pb.active{background:var(--purple);border-color:var(--purple);color:#fff;}

        .log-av{width:26px;height:26px;border-radius:50%;background:var(--navy3);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:var(--text2);}

        .alert { border-radius: var(--radius); padding: 10px 12px; margin-bottom: 10px; font-size: 11px; }
        .alert-error { background: var(--red-bg); border: 1px solid rgba(214,48,49,.3); color: #ffcaca; }
        .alert-success { background: var(--green-bg); border: 1px solid rgba(0,184,148,.3); color: #c5ffe9; }
        .info { background: var(--purple-glow); border: 1px solid rgba(108,92,231,.2); border-radius: var(--radius); padding: 8px 12px; font-size: 10px; color: var(--purple-light); margin-bottom: 10px; }

        @media (max-width: 1100px) {
            .stats-5 { grid-template-columns: repeat(2, 1fr); }
            .stats-4 { grid-template-columns: repeat(2, 1fr); }
            .g-6-4, .g3 { grid-template-columns: 1fr; }
        }

        @media (max-width: 840px) {
            .shell { height: auto; min-height: calc(100vh - 60px); }
            .sb { display: none; }
        }
    </style>
</head>
<body>
<div class="browser">
    <div class="shell">
        <aside class="sb">
            <div class="sb-logo">
                <div class="sb-logo-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="4" height="4"/></svg>
                </div>
                <div>
                    <div class="sb-logo-name">QR Attendance</div>
                    <div class="sb-logo-sub">SYSTEM</div>
                </div>
            </div>

            <div class="sb-user">
                <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                <div>
                    <div class="sb-uname">{{ auth()->user()->name }}</div>
                    <div class="sb-urole"><span class="sb-online"></span>Online · {{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>

            <div class="sb-sec">Menu</div>
            @if(auth()->user()->isProfessor())
                <a href="{{ route('professor.dashboard') }}" class="sb-item {{ request()->routeIs('professor.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('professor.classes') }}" class="sb-item {{ request()->routeIs('professor.classes*') ? 'active' : '' }}">My Classes</a>
                <a href="{{ route('professor.scan-qr') }}" class="sb-item {{ request()->routeIs('professor.scan-qr') ? 'active' : '' }}">Scan QR</a>
                <a href="{{ route('professor.attendance-records') }}" class="sb-item {{ request()->routeIs('professor.attendance-records*') ? 'active' : '' }}">Attendance Records</a>
                <a href="{{ route('professor.schedules') }}" class="sb-item {{ request()->routeIs('professor.schedules') ? 'active' : '' }}">Schedules</a>
                <a href="{{ route('professor.students') }}" class="sb-item {{ request()->routeIs('professor.students') ? 'active' : '' }}">Students</a>
                <a href="{{ route('professor.logs') }}" class="sb-item {{ request()->routeIs('professor.logs') ? 'active' : '' }}">Logs</a>
                <a href="{{ route('professor.settings') }}" class="sb-item {{ request()->routeIs('professor.settings*') ? 'active' : '' }}">Settings</a>
            @elseif(auth()->user()->isStudent())
                <a href="{{ route('student.dashboard') }}" class="sb-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('student.classes') }}" class="sb-item {{ request()->routeIs('student.classes') ? 'active' : '' }}">My Classes</a>
                <a href="{{ route('student.attendance') }}" class="sb-item {{ request()->routeIs('student.attendance') ? 'active' : '' }}">Attendance</a>
            @endif

            <form method="POST" action="{{ route('logout') }}" style="margin-top:auto;">
                @csrf
                <button type="submit" class="sb-logout">Logout</button>
            </form>
        </aside>

        <div class="page-wrap">
            <div class="topbar">
                <div>
                    <div class="topbar-title">@yield('header', '')</div>
                    <div class="topbar-sub">@yield('subheader', '')</div>
                </div>
                <div class="topbar-right">
                    <div class="topbar-date">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <span id="current-date"></span>
                    </div>
                    <div class="topbar-clock" id="current-time"></div>
                    <button type="button" class="notif-btn" aria-label="Notifications">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                        <span class="notif-dot"></span>
                    </button>
                </div>
            </div>

            <div class="main">
                @if($errors->any())
                    <div class="alert alert-error" style="margin:18px 18px 0;">
                        <ul style="list-style:disc;padding-left:18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success" style="margin:18px 18px 0;">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error" style="margin:18px 18px 0;">{{ session('error') }}</div>
                @endif

                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const date = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const timeEl = document.getElementById('current-time');
        const dateEl = document.getElementById('current-date');

        if (timeEl) {
            timeEl.textContent = time;
        }

        if (dateEl) {
            dateEl.textContent = date;
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
</body>
</html>