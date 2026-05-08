<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'QR Attendance System — Professor')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#020510;
      --glass:rgba(255,255,255,.072);
      --glass-strong:rgba(255,255,255,.11);
      --stroke:rgba(255,255,255,.22);
      --stroke-soft:rgba(255,255,255,.10);
      --text:#f0f4ff;
      --muted:#9ba8cc;
      --faint:#626d96;
      --purple:#8b5cff;
      --blue:#43a6ff;
      --green:#18f08b;
      --red:#ff3d72;
      --yellow:#ffc75a;
      --cyan:#00e5ff;
      --shadow:0 32px 90px rgba(0,0,0,.42);
      --blur:blur(32px) saturate(200%);
      --radius-lg:28px;
      --radius-md:18px;
      --radius-sm:12px;
      --font:'DM Sans',system-ui,sans-serif;
      --mono:'Space Mono',monospace;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;overflow:hidden}
    body{
      height:100vh;
      font-family:var(--font);
      color:var(--text);
      overflow:hidden;
      background:
        radial-gradient(ellipse at 14% 12%, rgba(102,75,255,.26) 0%, transparent 38%),
        radial-gradient(ellipse at 85% 8%, rgba(39,103,214,.18) 0%, transparent 32%),
        radial-gradient(ellipse at 72% 90%, rgba(136,30,82,.16) 0%, transparent 34%),
        radial-gradient(ellipse at 50% 50%, rgba(0,229,255,.03) 0%, transparent 60%),
        linear-gradient(135deg, #020510 0%, #06091a 40%, #0a0d22 70%, #030713 100%);
    }
    body::before{
      content:"";
      position:fixed;inset:0;
      pointer-events:none;
      background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size:60px 60px;
      mask-image:radial-gradient(ellipse at 50% 0%, black 30%, transparent 80%);
      z-index:0;
    }
    .orb{position:fixed;border-radius:50%;filter:blur(70px);pointer-events:none;z-index:0;animation:orb-float 18s ease-in-out infinite}
    .orb-1{width:520px;height:520px;background:radial-gradient(circle,rgba(84,56,216,.32),transparent 70%);left:-80px;top:-80px;opacity:.7}
    .orb-2{width:400px;height:400px;background:radial-gradient(circle,rgba(20,85,199,.22),transparent 70%);right:-60px;top:100px;animation-delay:-6s;opacity:.6}
    .orb-3{width:360px;height:360px;background:radial-gradient(circle,rgba(138,23,72,.2),transparent 70%);right:15%;bottom:-40px;animation-delay:-12s;opacity:.5}
    .orb-4{width:280px;height:280px;background:radial-gradient(circle,rgba(0,229,255,.08),transparent 70%);left:40%;top:30%;animation-delay:-3s;opacity:.4}
    @keyframes orb-float{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(30px,-40px) scale(1.06)}66%{transform:translate(-20px,25px) scale(.96)}}

    .glass{
      border:1px solid var(--stroke);
      background:linear-gradient(135deg,rgba(255,255,255,.18),rgba(255,255,255,.05) 40%,rgba(255,255,255,.10));
      backdrop-filter:var(--blur);
      -webkit-backdrop-filter:var(--blur);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.32),inset 0 -1px 0 rgba(0,0,0,.18),var(--shadow);
      position:relative;overflow:hidden;
    }
    .glass::after{
      content:"";position:absolute;top:0;left:0;right:0;height:1px;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.55) 50%,transparent);
      pointer-events:none;
    }

    .app{display:grid;grid-template-columns:260px 1fr;height:100vh;overflow:hidden;position:relative;z-index:1}

    .sidebar{
      height:100vh;padding:14px 10px;
      border-right:1px solid rgba(255,255,255,.1);
      background:rgba(2,4,18,.70);
      backdrop-filter:blur(40px) saturate(180%);
      display:flex;flex-direction:column;gap:8px;overflow:hidden;
    }
    .brand{display:flex;align-items:center;gap:11px;padding:4px 8px 12px;border-bottom:1px solid rgba(255,255,255,.09);flex-shrink:0}
    .logo{
      width:40px;height:40px;border-radius:13px;display:grid;place-items:center;font-size:20px;
      background:linear-gradient(145deg,rgba(139,92,255,1),rgba(67,166,255,.85));
      box-shadow:0 10px 30px rgba(93,93,255,.45),inset 0 1px 0 rgba(255,255,255,.5);
      flex-shrink:0;position:relative;overflow:hidden;
    }
    .logo::before{content:"";position:absolute;top:-30%;left:-30%;width:60%;height:60%;background:rgba(255,255,255,.25);border-radius:50%;filter:blur(10px)}
    .brand-text h1{font-size:15px;font-weight:800;letter-spacing:-.02em;line-height:1.1}
    .brand-text span{color:var(--muted);font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;display:block;margin-top:2px}
    .profile-card{
      border-radius:18px;padding:10px 12px;display:flex;align-items:center;gap:10px;
      background:rgba(139,92,255,.12);border:1px solid rgba(139,92,255,.25);flex-shrink:0;
    }
    .avatar{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;
      font-weight:900;font-size:14px;
      background:linear-gradient(145deg,#9a77ff,#4715d1);
      border:2px solid rgba(255,255,255,.3);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.5),0 10px 28px rgba(93,71,255,.38);
      flex-shrink:0;position:relative;
    }
    .avatar-status{position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:var(--green);border:2px solid rgba(2,4,18,.8);box-shadow:0 0 8px rgba(24,240,139,.6)}
    .profile-info h2{font-size:14px;font-weight:700;display:flex;gap:6px;align-items:center}
    .tag{font-size:10px;padding:3px 7px;border-radius:999px;background:rgba(139,92,255,.3);color:#efeaff;border:1px solid rgba(139,92,255,.4)}
    .profile-info p{margin-top:3px;color:var(--muted);font-size:11.5px}
    .online-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--green);margin-top:2px;font-weight:600}
    .dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 6px rgba(24,240,139,.8);animation:pulse-dot 2s infinite}
    @keyframes pulse-dot{0%,100%{opacity:1;box-shadow:0 0 6px rgba(24,240,139,.8)}50%{opacity:.6;box-shadow:0 0 14px rgba(24,240,139,.4)}}

    .nav-label{margin:2px 8px 0;color:var(--faint);font-size:10px;letter-spacing:.18em;text-transform:uppercase;font-weight:700;flex-shrink:0}
    .nav{display:grid;gap:2px;flex-shrink:0;overflow-y:auto}
    .nav a, .nav button{
      border:0;color:rgba(234,240,255,.75);background:transparent;
      padding:8px 10px;border-radius:13px;display:flex;align-items:center;gap:10px;
      font-weight:600;cursor:pointer;transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;font-size:13.5px;font-family:var(--font);width:100%;position:relative;text-decoration:none;
    }
    .nav a .nav-icon, .nav button .nav-icon{
      width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-size:14px;
      background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.09);flex-shrink:0;transition:.2s ease;
    }
    .nav a:hover, .nav button:hover{background:rgba(255,255,255,.08);color:var(--text);transform:translateX(3px)}
    .nav a:hover .nav-icon, .nav button:hover .nav-icon{background:rgba(255,255,255,.12)}
    .nav a.active, .nav button.active{background:linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));color:#fff;box-shadow:0 12px 28px rgba(80,94,255,.26),inset 0 1px 0 rgba(255,255,255,.28)}
    .nav a.active .nav-icon, .nav button.active .nav-icon{background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.25)}

    .logout-wrap{margin-top:auto;border-top:1px solid rgba(255,255,255,.08);padding-top:10px;flex-shrink:0}
    .logout{
      border:1px solid transparent;background:transparent;color:#ff8298;
      padding:11px 14px;border-radius:16px;display:flex;align-items:center;gap:10px;
      font-weight:700;cursor:pointer;transition:.2s ease;font-size:13.5px;font-family:var(--font);width:100%;
    }
    .logout:hover{background:rgba(255,61,114,.12);border-color:rgba(255,61,114,.2);transform:scale(1.02)}
    .logout-icon{width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-size:14px;background:transparent}

    main{padding:18px 24px 18px;height:100vh;display:flex;flex-direction:column;overflow:hidden}
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:14px;flex-shrink:0}
    .page-title h2{
      font-size:26px;font-weight:800;letter-spacing:-.06em;line-height:1;
      background:linear-gradient(135deg,#fff 40%,rgba(200,210,255,.7));
      -webkit-background-clip:text;-webkit-text-fill-color:transparent;
    }
    .page-title p{margin-top:4px;color:var(--muted);font-size:13px;font-weight:500}
    .top-right{display:flex;align-items:center;gap:12px}
    .search-bar{
      height:44px;width:280px;border-radius:999px;padding:0 16px;
      display:flex;align-items:center;gap:9px;color:var(--muted);
      border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.06);
      backdrop-filter:blur(20px);box-shadow:inset 0 1px 0 rgba(255,255,255,.15);
      font-size:13.5px;cursor:text;transition:.2s ease;
    }
    .search-bar:hover{border-color:rgba(255,255,255,.25);background:rgba(255,255,255,.09)}
    .clock-pill{
      display:flex;align-items:center;gap:8px;padding:0 14px;height:44px;
      border-radius:999px;border:1px solid rgba(255,255,255,.13);
      background:rgba(255,255,255,.06);backdrop-filter:blur(16px);
      font-size:13px;font-weight:700;white-space:nowrap;color:var(--text);
    }
    .clock-date{color:var(--muted);font-size:12px}
    .top-avatar{
      width:44px;height:44px;border-radius:50%;
      background:linear-gradient(145deg,#8b5cff,#4915d3);
      border:2px solid rgba(255,255,255,.25);display:grid;place-items:center;
      font-weight:900;cursor:pointer;position:relative;transition:.2s ease;
    }
    .top-avatar:hover{transform:scale(1.06)}
    .top-avatar::after{content:"";position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:var(--green);border:2px solid rgba(2,4,18,.9)}

    .content{flex:1;overflow-y:auto;min-height:0;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent}
    
    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:14px}
    .stat{
      border-radius:22px;padding:16px;display:flex;gap:12px;align-items:flex-start;
      transition:.25s ease;
    }
    .stat:hover{transform:translateY(-3px);border-color:rgba(255,255,255,.3)}
    .stat-icon{
      width:40px;height:40px;border-radius:13px;display:grid;place-items:center;font-size:18px;flex-shrink:0;
    }
    .stat-icon.blue{background:rgba(67,166,255,.18);border:1px solid rgba(67,166,255,.25)}
    .stat-icon.green{background:rgba(24,240,139,.15);border:1px solid rgba(24,240,139,.2)}
    .stat-icon.yellow{background:rgba(255,199,90,.15);border:1px solid rgba(255,199,90,.2)}
    .stat-icon.purple{background:rgba(139,92,255,.18);border:1px solid rgba(139,92,255,.22)}
    .stat-icon.red{background:rgba(255,61,114,.15);border:1px solid rgba(255,61,114,.2)}
    .stat-body strong{font-size:26px;font-weight:900;letter-spacing:-.05em;display:block;line-height:1}
    .stat-body span{color:var(--muted);font-size:12px;font-weight:500;margin-top:3px;display:block}
    .stat-body a{color:rgba(139,92,255,.9);font-size:11.5px;font-weight:700;text-decoration:none;display:block;margin-top:4px;cursor:pointer}
    .stat-body a:hover{color:#b9c4ff}
    .trend{font-size:11.5px;font-weight:700;margin-top:3px}
    .trend.up{color:var(--green)}
    .trend.down{color:var(--red)}

    .dashboard{display:grid;grid-template-columns:1.2fr .8fr;gap:12px;align-items:stretch}
    .dash-left{display:flex;flex-direction:column;gap:12px}
    .dash-right{display:flex;flex-direction:column;gap:12px}
    .card{border-radius:22px;padding:16px}
    .section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-shrink:0}
    .section-head h3{font-size:15px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px}
    .section-head a{color:rgba(139,92,255,.9);text-decoration:none;font-weight:700;font-size:12px;letter-spacing:.02em;cursor:pointer}
    .section-head a:hover{color:#b9c4ff}

    .mini-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:4px;margin-bottom:10px;max-width:400px}
    .mini{
      border-radius:10px;padding:6px;
      border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.065);
      display:flex;flex-direction:column;gap:4px;align-items:center;justify-content:center;transition:.2s ease;aspect-ratio:1/1;
    }
    .mini:hover{background:rgba(255,255,255,.1)}
    .mini b{font-size:12px;display:block;font-weight:900;letter-spacing:-.04em;text-align:center}
    .mini small{color:var(--muted);font-size:8px;margin-top:0px;display:block;text-align:center}
    .mini-icon{width:24px;height:24px;border-radius:6px;display:grid;place-items:center;font-size:10px;flex-shrink:0}

    .report-btn{
      width:100%;height:40px;border:0;border-radius:13px;color:white;font-weight:800;cursor:pointer;
      font-size:13px;letter-spacing:.02em;font-family:var(--font);
      background:linear-gradient(135deg,rgba(139,92,255,.85),rgba(67,166,255,.45));
      box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 8px 24px rgba(80,94,255,.2);transition:.2s ease;
    }
    .report-btn:hover{transform:translateY(-2px);box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 14px 32px rgba(80,94,255,.35)}

    .activity{
      display:grid;grid-template-columns:38px 1fr auto;gap:10px;padding:9px 0;
      border-bottom:1px solid rgba(255,255,255,.06);transition:.2s ease;border-radius:8px;
    }
    .activity:hover{background:rgba(255,255,255,.03);padding-left:6px;padding-right:6px}
    .activity:last-child{border-bottom:0}
    .act-icon{
      width:38px;height:38px;
      border-radius:13px;
      display:grid;place-items:center;
      font-size:15px;
      flex-shrink:0;
    }
    .act-icon.add{background:rgba(24,240,139,.15);border:1px solid rgba(24,240,139,.2)}
    .act-icon.edit{background:rgba(255,199,90,.15);border:1px solid rgba(255,199,90,.2)}
    .act-icon.create{background:rgba(139,92,255,.18);border:1px solid rgba(139,92,255,.22)}
    .act-icon.scan{background:rgba(67,166,255,.15);border:1px solid rgba(67,166,255,.2)}
    .act-icon.drop{background:rgba(255,61,114,.12);border:1px solid rgba(255,61,114,.18)}
    .act-icon.att{background:rgba(139,92,255,.15);border:1px solid rgba(139,92,255,.2)}
    .activity b{font-size:13.5px;font-weight:700}
    .activity p{margin:3px 0 0;color:var(--muted);font-size:12.5px;line-height:1.4}
    .activity time{font-size:11.5px;color:var(--faint);font-variant-numeric:tabular-nums;white-space:nowrap;font-family:var(--mono)}

    .row-item{
      display:flex;align-items:center;justify-content:space-between;
      border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.055);
      border-radius:12px;padding:9px 12px;margin-bottom:6px;font-weight:700;font-size:13px;transition:.2s ease;
    }
    .row-item:hover{background:rgba(255,255,255,.09)}
    .row-item:last-child{margin-bottom:0}
    .row-item span{color:var(--muted);font-weight:500}

    .quick-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:6px;max-width:280px}
    .quick{
      border-radius:12px;padding:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0;
      cursor:pointer;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);transition:.25s ease;aspect-ratio:1/1;
    }
    .quick:hover{background:rgba(255,255,255,.1);transform:translateY(-2px);border-color:rgba(255,255,255,.22)}
    .quick strong{display:block;font-size:11px;font-weight:700;margin-top:4px}
    .quick span{display:block;color:var(--muted);font-size:9px;margin-top:1px}

    .pill{
      display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:6px 11px;
      font-size:11.5px;font-weight:700;border:1px solid rgba(255,255,255,.10);white-space:nowrap;letter-spacing:.01em;
    }
    .pill.green{color:#4dffa0;background:rgba(24,240,139,.11);border-color:rgba(24,240,139,.2)}

    table{width:100%;border-collapse:separate;border-spacing:0}
    th,td{padding:14px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle}
    th{
      background:rgba(255,255,255,.055);color:var(--faint);font-size:11px;letter-spacing:.12em;
      text-transform:uppercase;font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px);
    }
    td{color:#e8eeff;font-size:13.5px}
    tr:last-child td{border-bottom:0}
    tr:hover td{background:rgba(255,255,255,.028)}

    .btn{
      border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:#fff;
      border-radius:13px;padding:10px 14px;font-weight:700;cursor:pointer;transition:.2s ease;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.14);font-size:13px;font-family:var(--font);
    }
    .btn:hover{transform:translateY(-2px);background:rgba(255,255,255,.13);border-color:rgba(255,255,255,.24)}
    .btn-pill{border-radius:999px !important;padding:10px 20px;display:flex;align-items:center;justify-content:center}

    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.22)}

    @media(max-width:1200px){
      .app{grid-template-columns:76px 1fr}
      .brand-text,.profile-info,.nav-label,.nav a span,.nav button span,.logout span{display:none}
      .brand,.profile-card{justify-content:center}
      .profile-card{padding:10px;background:transparent;border:none}
      .nav a,.nav button{justify-content:center;padding:11px}
      .logout{justify-content:center;padding:11px}
      .stats{grid-template-columns:repeat(2,1fr)}
      .dashboard{grid-template-columns:1fr}
    }
  </style>
</head>
<body>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  <div class="orb orb-4"></div>

  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">▦</div>
        <div class="brand-text">
          <h1>QR Attendance</h1>
          <span>Professor</span>
        </div>
      </div>

      <div class="profile-card">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
          <div class="avatar-status"></div>
        </div>
        <div class="profile-info">
          <h2>{{ auth()->user()->name }} <span class="tag">Prof</span></h2>
          <p>{{ auth()->user()->email }}</p>
          <div class="online-badge"><span class="dot"></span> Online</div>
        </div>
      </div>

      <div class="nav-label">Menu</div>
      <nav class="nav">
        <a href="{{ route('professor.dashboard') }}" class="{{ request()->routeIs('professor.dashboard') ? 'active' : '' }}">
          <span class="nav-icon">⌂</span>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('professor.classes') }}" class="{{ request()->routeIs('professor.classes*') ? 'active' : '' }}">
          <span class="nav-icon">▤</span>
          <span>My Classes</span>
        </a>
        <a href="{{ route('professor.scan-qr') }}" class="{{ request()->routeIs('professor.scan-qr') ? 'active' : '' }}">
          <span class="nav-icon">▦</span>
          <span>Scan QR</span>
        </a>
        <a href="{{ route('professor.attendance-records') }}" class="{{ request()->routeIs('professor.attendance-records*') ? 'active' : '' }}">
          <span class="nav-icon">📋</span>
          <span>Attendance Records</span>
        </a>
        <a href="{{ route('professor.schedules') }}" class="{{ request()->routeIs('professor.schedules') ? 'active' : '' }}">
          <span class="nav-icon">📅</span>
          <span>Schedules</span>
        </a>
        <a href="{{ route('professor.students') }}" class="{{ request()->routeIs('professor.students') ? 'active' : '' }}">
          <span class="nav-icon">🧑‍🎓</span>
          <span>Students</span>
        </a>
        <a href="{{ route('professor.reports') }}" class="{{ request()->routeIs('professor.reports') ? 'active' : '' }}">
          <span class="nav-icon">📊</span>
          <span>Reports</span>
        </a>
        <a href="{{ route('professor.logs') }}" class="{{ request()->routeIs('professor.logs') ? 'active' : '' }}">
          <span class="nav-icon">☷</span>
          <span>Logs</span>
        </a>
        <a href="{{ route('professor.settings') }}" class="{{ request()->routeIs('professor.settings*') ? 'active' : '' }}">
          <span class="nav-icon">⚙</span>
          <span>Settings</span>
        </a>
      </nav>

      <div class="logout-wrap">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout">
            <span class="logout-icon">↪</span>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </aside>

    <main>
      <header class="topbar">
        <div class="page-title">
          <h2 id="pageTitle">@yield('header', 'Dashboard')</h2>
          <p id="pageSubtitle">@yield('subheader', '')</p>
        </div>
        <div class="top-right">
          <div class="search-bar">🔍 <span id="searchLabel" style="font-size:13.5px">Search...</span></div>
          <div class="clock-pill">
            📅 <span class="clock-date" id="clock-date">May 7, 2026</span>
            &nbsp;·&nbsp;
            <span id="clockTime" style="font-family:var(--mono);font-size:12px">—</span>
          </div>
          <div class="top-avatar" onclick="document.querySelector('a[href*=settings]')?.click()">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</div>
        </div>
      </header>

      <div class="content">
        @if($errors->any())
          <div style="margin-bottom:14px;padding:12px 14px;border-radius:12px;background:rgba(255,61,114,0.1);border:1px solid rgba(255,61,114,0.25);">
            <ul style="padding-left:18px;">
              @foreach($errors->all() as $error)
                <li style="color:var(--text);">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        @if(session('success'))
          <div style="margin-bottom:14px;padding:12px 14px;border-radius:12px;background:rgba(24,240,139,0.1);border:1px solid rgba(24,240,139,0.25);color:var(--text);">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div style="margin-bottom:14px;padding:12px 14px;border-radius:12px;background:rgba(255,61,114,0.1);border:1px solid rgba(255,61,114,0.25);color:var(--text);">{{ session('error') }}</div>
        @endif
        @yield('content')
      </div>
    </main>
  </div>

  <script>
    function updateClock() {
      const now = new Date();
      const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
      const dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      const clockTime = document.getElementById('clockTime');
      const clockDate = document.getElementById('clock-date');
      if (clockTime) clockTime.textContent = time;
      if (clockDate) clockDate.textContent = dateStr;
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>
</body>
</html>