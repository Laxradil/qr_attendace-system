<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'QR Attendance System — Admin')</title>
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

    body{
      min-height:100vh;
      font-family:var(--font);
      color:var(--text);
      overflow-x:hidden;
      background:
        radial-gradient(ellipse at 14% 12%, rgba(102,75,255,.26) 0%, transparent 38%),
        radial-gradient(ellipse at 85% 8%, rgba(39,103,214,.18) 0%, transparent 32%),
        radial-gradient(ellipse at 72% 90%, rgba(136,30,82,.16) 0%, transparent 34%),
        radial-gradient(ellipse at 50% 50%, rgba(0,229,255,.03) 0%, transparent 60%),
        linear-gradient(135deg, #020510 0%, #06091a 40%, #0a0d22 70%, #030713 100%);
    }

    body::before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size:60px 60px;
      mask-image:radial-gradient(ellipse at 50% 0%, black 30%, transparent 80%);
      z-index:0;
    }

    .orb{
      position:fixed;
      border-radius:50%;
      filter:blur(70px);
      pointer-events:none;
      z-index:0;
      animation:orb-float 18s ease-in-out infinite;
    }
    .orb-1{width:520px;height:520px;background:radial-gradient(circle,rgba(84,56,216,.32),transparent 70%);left:-80px;top:-80px;opacity:.7}
    .orb-2{width:400px;height:400px;background:radial-gradient(circle,rgba(20,85,199,.22),transparent 70%);right:-60px;top:100px;animation-delay:-6s;opacity:.6}
    .orb-3{width:360px;height:360px;background:radial-gradient(circle,rgba(138,23,72,.2),transparent 70%);right:15%;bottom:-40px;animation-delay:-12s;opacity:.5}
    .orb-4{width:280px;height:280px;background:radial-gradient(circle,rgba(0,229,255,.08),transparent 70%);left:40%;top:30%;animation-delay:-3s;opacity:.4}
    @keyframes orb-float{
      0%,100%{transform:translate(0,0) scale(1)}
      33%{transform:translate(30px,-40px) scale(1.06)}
      66%{transform:translate(-20px,25px) scale(.96)}
    }

    .glass{
      border:1px solid var(--stroke);
      background:linear-gradient(135deg,rgba(255,255,255,.18),rgba(255,255,255,.05) 40%,rgba(255,255,255,.10));
      backdrop-filter:var(--blur);
      -webkit-backdrop-filter:var(--blur);
      box-shadow:
        inset 0 1px 0 rgba(255,255,255,.32),
        inset 0 -1px 0 rgba(0,0,0,.18),
        var(--shadow);
      position:relative;
      overflow:hidden;
    }
    .glass::after{
      content:"";
      position:absolute;
      top:0;left:0;right:0;
      height:1px;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.55) 50%,transparent);
      pointer-events:none;
    }

    .app{
      display:grid;
      grid-template-columns:280px 1fr;
      min-height:100vh;
      position:relative;
      z-index:1;
    }

    .sidebar{
      height:100vh;
      position:sticky;
      top:0;
      padding:16px 12px;
      border-right:1px solid rgba(255,255,255,.1);
      background:rgba(2,4,18,.70);
      backdrop-filter:blur(40px) saturate(180%);
      display:flex;
      flex-direction:column;
      gap:14px;
      overflow-y:auto;
      scrollbar-width:none;
    }
    .sidebar::-webkit-scrollbar{display:none}

    .brand{
      display:flex;
      align-items:center;
      gap:11px;
      padding:8px 8px 18px;
      border-bottom:1px solid rgba(255,255,255,.09);
    }
    .logo{
      width:46px;height:46px;
      border-radius:15px;
      display:grid;
      place-items:center;
      font-size:22px;
      background:linear-gradient(145deg,rgba(139,92,255,1),rgba(67,166,255,.85));
      box-shadow:0 10px 30px rgba(93,93,255,.45), inset 0 1px 0 rgba(255,255,255,.5);
      flex-shrink:0;
      position:relative;
      overflow:hidden;
    }
    .logo::before{
      content:"";
      position:absolute;
      top:-30%;left:-30%;
      width:60%;height:60%;
      background:rgba(255,255,255,.25);
      border-radius:50%;
      filter:blur(10px);
    }
    .brand-text h1{font-size:15px;font-weight:800;letter-spacing:-.02em;line-height:1.1}
    .brand-text span{color:var(--muted);font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;display:block;margin-top:2px}

    .profile-card{
      border-radius:22px;
      padding:14px;
      display:flex;
      align-items:center;
      gap:11px;
      background:rgba(139,92,255,.12);
      border:1px solid rgba(139,92,255,.25);
    }
    .avatar{
      width:48px;height:48px;
      border-radius:50%;
      display:grid;
      place-items:center;
      font-weight:900;
      font-size:16px;
      background:linear-gradient(145deg,#9a77ff,#4715d1);
      border:2px solid rgba(255,255,255,.3);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.5), 0 10px 28px rgba(93,71,255,.38);
      flex-shrink:0;
      position:relative;
    }
    .avatar-status{
      position:absolute;
      bottom:1px;right:1px;
      width:11px;height:11px;
      border-radius:50%;
      background:var(--green);
      border:2px solid rgba(2,4,18,.8);
      box-shadow:0 0 8px rgba(24,240,139,.6);
    }
    .profile-info h2{font-size:14px;font-weight:700;display:flex;gap:6px;align-items:center}
    .tag{font-size:10px;padding:3px 7px;border-radius:999px;background:rgba(139,92,255,.3);color:#efeaff;border:1px solid rgba(139,92,255,.4)}
    .profile-info p{margin-top:3px;color:var(--muted);font-size:11.5px}
    .online-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--green);margin-top:2px;font-weight:600}
    .dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 6px rgba(24,240,139,.8);animation:pulse-dot 2s infinite}
    @keyframes pulse-dot{0%,100%{opacity:1;box-shadow:0 0 6px rgba(24,240,139,.8)}50%{opacity:.6;box-shadow:0 0 14px rgba(24,240,139,.4)}}

    .nav-label{
      margin:4px 8px 2px;
      color:var(--faint);
      font-size:10.5px;
      letter-spacing:.18em;
      text-transform:uppercase;
      font-weight:700;
    }

    .nav{display:grid;gap:4px}
    .nav a, .nav form button{
      border:0;
      color:rgba(234,240,255,.75);
      background:transparent;
      padding:11px 12px;
      border-radius:15px;
      display:flex;
      align-items:center;
      gap:11px;
      font-weight:600;
      cursor:pointer;
      transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;
      font-size:14px;
      font-family:var(--font);
      width:100%;
      position:relative;
      text-decoration:none;
    }
    .nav a .nav-icon, .nav form button .nav-icon{
      width:34px;height:34px;
      border-radius:11px;
      display:grid;
      place-items:center;
      font-size:16px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.09);
      flex-shrink:0;
      transition:.2s ease;
    }
    .nav a:hover, .nav form button:hover{
      background:rgba(255,255,255,.08);
      color:var(--text);
      transform:translateX(3px);
    }
    .nav a:hover .nav-icon, .nav form button:hover .nav-icon{
      background:rgba(255,255,255,.12);
    }
    .nav a.active{
      background:linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));
      color:#fff;
      box-shadow:0 12px 28px rgba(80,94,255,.26),inset 0 1px 0 rgba(255,255,255,.28);
    }
    .nav a.active .nav-icon{
      background:rgba(255,255,255,.2);
      border-color:rgba(255,255,255,.25);
    }
    .nav-badge{
      margin-left:auto;
      padding:2px 7px;
      border-radius:999px;
      background:rgba(255,61,114,.85);
      color:#fff;
      font-size:10px;
      font-weight:900;
    }

    .logout-wrap{margin-top:auto;border-top:1px solid rgba(255,255,255,.08);padding-top:12px}
    .logout{
      border:1px solid transparent;
      background:transparent;
      color:#ff8298;
      padding:11px 12px;
      border-radius:15px;
      display:flex;align-items:center;gap:11px;
      font-weight:700;cursor:pointer;
      transition:.2s ease;
      font-size:14px;
      font-family:var(--font);
      width:100%;
    }
    .logout:hover{
      border-color:rgba(255,61,114,.2);
      background:rgba(255,61,114,.14);
      transform:translateX(3px)
    }
    .logout-icon{
      width:34px;height:34px;border-radius:11px;
      display:grid;place-items:center;font-size:16px;
      background:rgba(255,61,114,.15);
    }

    main{
      padding:24px 30px 48px;
      overflow-y:auto;
      min-height:100vh;
    }

    .topbar{
      max-width:1480px;
      margin:0 auto 24px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:18px;
    }
    .page-title h2{
      font-size:32px;
      font-weight:800;
      letter-spacing:-.06em;
      line-height:1;
      background:linear-gradient(135deg,#fff 40%,rgba(200,210,255,.7));
      background-clip:text;
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }
    .page-title p{
      margin-top:7px;
      color:var(--muted);
      font-size:14px;
      font-weight:500;
    }
    .top-right{display:flex;align-items:center;gap:12px}

    .search-bar{
      height:44px;
      width:300px;
      border-radius:999px;
      padding:0 16px;
      display:flex;align-items:center;gap:9px;
      color:var(--muted);
      border:1px solid rgba(255,255,255,.15);
      background:rgba(255,255,255,.06);
      backdrop-filter:blur(20px);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.15);
      font-size:13.5px;
      cursor:text;
      transition:.2s ease;
    }
    .search-bar:hover{border-color:rgba(255,255,255,.25);background:rgba(255,255,255,.09)}

    .clock-pill{
      display:flex;align-items:center;gap:8px;
      padding:0 14px;
      height:44px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.13);
      background:rgba(255,255,255,.06);
      backdrop-filter:blur(16px);
      font-size:13px;
      font-weight:700;
      white-space:nowrap;
      color:var(--text);
    }
    .clock-date{color:var(--muted);font-size:12px}

    .notif-btn{
      width:44px;height:44px;
      border-radius:50%;
      border:1px solid rgba(255,255,255,.15);
      background:rgba(255,255,255,.07);
      display:grid;place-items:center;
      cursor:pointer;
      font-size:18px;
      position:relative;
      transition:.2s ease;
    }
    .notif-btn:hover{background:rgba(255,255,255,.12);transform:scale(1.06)}
    .notif-dot{
      position:absolute;
      top:8px;right:9px;
      width:8px;height:8px;
      border-radius:50%;
      background:var(--red);
      border:2px solid rgba(2,4,18,.9);
      box-shadow:0 0 8px rgba(255,61,114,.7);
      animation:pulse-dot 2s infinite;
    }

    .top-avatar{
      width:44px;height:44px;
      border-radius:50%;
      background:linear-gradient(145deg,#8b5cff,#4915d3);
      border:2px solid rgba(255,255,255,.25);
      display:grid;place-items:center;
      font-weight:900;
      cursor:pointer;
      position:relative;
      transition:.2s ease;
      text-decoration:none;
      color:#fff;
    }
    .top-avatar:hover{transform:scale(1.06);color:#fff}
    .top-avatar:focus{outline:none}
    .top-avatar::after{
      content:"";
      position:absolute;
      bottom:1px;right:1px;
      width:11px;height:11px;
      border-radius:50%;
      background:var(--green);
      border:2px solid rgba(2,4,18,.9);
    }

    .content{max-width:1480px;margin:0 auto;animation:fadein .3s cubic-bezier(.4,0,.2,1)}
    @keyframes fadein{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}

    .stats{
      display:grid;
      grid-template-columns:repeat(4,minmax(0,1fr));
      gap:16px;
      margin-bottom:20px;
    }
    .stat{
      border-radius:var(--radius-lg);
      padding:22px;
      display:flex;
      align-items:center;
      gap:16px;
      transition:.3s cubic-bezier(.4,0,.2,1);
      cursor:default;
    }
    .stat:hover{transform:translateY(-5px);border-color:rgba(255,255,255,.32)}
    .stat-icon{
      width:56px;height:56px;
      border-radius:18px;
      display:grid;place-items:center;
      font-size:22px;
      border:1px solid rgba(255,255,255,.15);
      flex-shrink:0;
    }
    .stat-icon.blue{background:linear-gradient(145deg,rgba(67,166,255,.55),rgba(139,92,255,.28))}
    .stat-icon.green{background:linear-gradient(145deg,rgba(24,240,139,.42),rgba(67,166,255,.12))}
    .stat-icon.yellow{background:linear-gradient(145deg,rgba(255,199,90,.45),rgba(255,100,50,.15))}
    .stat-icon.purple{background:linear-gradient(145deg,rgba(139,92,255,.62),rgba(67,166,255,.22))}
    .stat-icon.red{background:linear-gradient(145deg,rgba(255,61,114,.55),rgba(255,100,50,.15))}
    .stat-body strong{
      display:block;
      font-size:30px;
      font-weight:900;
      letter-spacing:-.06em;
      font-variant-numeric:tabular-nums;
    }
    .stat-body span{display:block;color:#d0d8f0;font-weight:600;font-size:13px;margin-top:3px}
    .stat-body a{display:block;margin-top:10px;color:rgba(139,92,255,.9);text-decoration:none;font-size:12px;font-weight:700;letter-spacing:.02em}
    .stat-body a:hover{color:#b9c4ff}

    .trend{
      display:inline-flex;align-items:center;gap:3px;
      font-size:11px;font-weight:700;padding:2px 7px;
      border-radius:999px;margin-top:6px;
    }
    .trend.up{color:#18f08b;background:rgba(24,240,139,.12)}
    .trend.down{color:#ff3d72;background:rgba(255,61,114,.12)}

    .dashboard{
      display:grid;
      grid-template-columns:1.2fr .8fr;
      gap:18px;
      align-items:start;
    }
    .card{border-radius:var(--radius-lg);padding:22px}
    .section-head{
      display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;
    }
    .section-head h3{font-size:18px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px}
    .section-head a{color:rgba(139,92,255,.9);text-decoration:none;font-weight:700;font-size:12.5px;letter-spacing:.02em}
    .section-head a:hover{color:#b9c4ff}

    .mini-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px}
    .mini{
      border-radius:18px;
      padding:14px;
      border:1px solid rgba(255,255,255,.12);
      background:rgba(255,255,255,.065);
      display:flex;gap:10px;align-items:center;
      transition:.2s ease;
    }
    .mini:hover{background:rgba(255,255,255,.1)}
    .mini b{font-size:22px;display:block;font-weight:900;letter-spacing:-.04em}
    .mini small{color:var(--muted);font-size:11.5px;margin-top:2px;display:block}
    .mini-icon{
      width:38px;height:38px;
      border-radius:12px;
      display:grid;place-items:center;
      font-size:16px;
      flex-shrink:0;
    }

    .report-btn{
      width:100%;height:50px;
      border:0;border-radius:16px;
      color:white;font-weight:800;
      cursor:pointer;
      font-size:14px;
      letter-spacing:.02em;
      font-family:var(--font);
      background:linear-gradient(135deg,rgba(139,92,255,.85),rgba(67,166,255,.45));
      box-shadow:inset 0 1px 0 rgba(255,255,255,.25), 0 8px 24px rgba(80,94,255,.2);
      transition:.2s ease;
    }
    .report-btn:hover{transform:translateY(-2px);box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 14px 32px rgba(80,94,255,.35)}

    .activity{
      display:grid;
      grid-template-columns:42px 1fr auto;
      gap:12px;
      padding:12px 0;
      border-bottom:1px solid rgba(255,255,255,.07);
      transition:.2s ease;
      border-radius:8px;
    }
    .activity:hover{background:rgba(255,255,255,.03);padding-left:8px;padding-right:8px}
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
    .activity b{font-size:13.5px;font-weight:700}
    .activity p{margin:3px 0 0;color:var(--muted);font-size:12.5px;line-height:1.4}
    .activity time{font-size:11.5px;color:var(--faint);font-variant-numeric:tabular-nums;white-space:nowrap;font-family:var(--mono)}

    .right-stack{display:grid;gap:12px}
    .system-overview-card{padding:16px 16px 14px}
    .system-overview-card .section-head{margin-bottom:12px}
    .system-overview-card .section-head h3{font-size:16px}
    .system-overview-card .row-item{
      border-radius:12px;
      padding:9px 12px;
      margin-bottom:6px;
      font-size:12.5px;
    }
    .system-overview-card .pill{padding:4px 9px;font-size:10.5px}
    .row-item{
      display:flex;align-items:center;justify-content:space-between;
      border:1px solid rgba(255,255,255,.10);
      background:rgba(255,255,255,.055);
      border-radius:15px;padding:13px 15px;margin-bottom:8px;
      font-weight:700;font-size:13.5px;
      transition:.2s ease;
    }
    .row-item:hover{background:rgba(255,255,255,.09)}
    .row-item:last-child{margin-bottom:0}
    .row-item span{color:var(--muted);font-weight:500}

    .quick-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
    .quick{
      border-radius:20px;
      padding:14px;
      display:flex;align-items:center;gap:11px;
      cursor:pointer;
      border:1px solid rgba(255,255,255,.12);
      background:rgba(255,255,255,.06);
      transition:.25s ease;
      text-decoration:none;
    }
    .quick:hover{background:rgba(255,255,255,.1);transform:translateY(-3px);border-color:rgba(255,255,255,.22)}
    .quick strong{display:block;font-size:13px;font-weight:700}
    .quick span{display:block;color:var(--muted);font-size:11.5px;margin-top:2px}

    .pill{
      display:inline-flex;align-items:center;gap:5px;
      border-radius:999px;padding:6px 11px;
      font-size:11.5px;font-weight:700;
      border:1px solid rgba(255,255,255,.10);
      white-space:nowrap;letter-spacing:.01em;
    }
    .pill.green{color:#4dffa0;background:rgba(24,240,139,.11);border-color:rgba(24,240,139,.2)}
    .pill.red{color:#ff7f96;background:rgba(255,61,114,.12);border-color:rgba(255,61,114,.22)}
    .pill.yellow{color:#ffd584;background:rgba(255,199,90,.12);border-color:rgba(255,199,90,.22)}
    .pill.blue{color:#80cbff;background:rgba(67,166,255,.12);border-color:rgba(67,166,255,.22)}
    .pill.purple{color:#d8cdff;background:rgba(139,92,255,.15);border-color:rgba(139,92,255,.25)}

    .status-dot{
      width:7px;height:7px;border-radius:50%;display:inline-block;
      box-shadow:0 0 6px currentColor;
    }

    .toolbar{
      display:flex;justify-content:space-between;align-items:center;
      gap:12px;flex-wrap:wrap;margin-bottom:18px;
    }
    .tools{display:flex;align-items:center;gap:9px;flex-wrap:wrap}
    .glass-table{border-radius:var(--radius-lg);padding:20px;transition:.3s ease}
    .glass-table:hover{transform:translateY(-3px);border-color:rgba(255,255,255,.3)}
    .table-wrap{overflow-x:auto;border-radius:var(--radius-md);scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.12) transparent}
    table{
      width:100%;
      min-width:860px;
      border-collapse:separate;
      border-spacing:0;
    }
    th,td{
      padding:14px 15px;
      text-align:left;
      border-bottom:1px solid rgba(255,255,255,.07);
      vertical-align:middle;
    }
    th{
      background:rgba(255,255,255,.055);
      color:#fff;
      font-size:11px;
      letter-spacing:.12em;
      text-transform:uppercase;
      font-weight:700;
      position:sticky;top:0;
      backdrop-filter:blur(8px);
    }
    th:first-child{border-radius:var(--radius-md) 0 0 0}
    th:last-child{border-radius:0 var(--radius-md) 0 0}
    td{color:#fff;font-size:13.5px}
    tr:last-child td{border-bottom:0}
    tr:hover td{background:rgba(255,255,255,.028)}
    .user-cell{display:flex;align-items:center;gap:10px;font-weight:700}
    .small-avatar{
      width:34px;height:34px;
      border-radius:11px;
      display:grid;place-items:center;
      background:linear-gradient(145deg,rgba(139,92,255,.36),rgba(67,166,255,.2));
      font-size:11px;font-weight:900;
      border:1px solid rgba(139,92,255,.3);
      flex-shrink:0;
    }
    .muted{color:var(--muted);font-weight:400}
    .footer-bar{
      display:flex;justify-content:space-between;align-items:center;
      margin-top:16px;
      color:var(--muted);
      font-size:12.5px;
    }
    .pager{display:flex;gap:6px}
    .pager button, .pager a{
      width:34px;height:34px;
      border:1px solid rgba(255,255,255,.14);
      border-radius:11px;
      background:rgba(255,255,255,.07);
      color:white;cursor:pointer;
      font-family:var(--font);
      font-weight:700;
      transition:.2s ease;
      text-decoration:none;
      display:inline-grid;
      place-items:center;
    }
    .pager button:hover, .pager a:hover{background:rgba(255,255,255,.13)}
    .pager .current, .pager a.current{background:linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.55));border-color:transparent}

    .btn{
      border:1px solid rgba(255,255,255,.15);
      background:rgba(255,255,255,.08);
      color:#fff;
      border-radius:13px;
      padding:10px 14px;
      font-weight:700;
      cursor:pointer;
      transition:.2s ease;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.14);
      font-size:13px;
      font-family:var(--font);
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
    }
    .btn:hover{transform:translateY(-2px);background:rgba(255,255,255,.13);border-color:rgba(255,255,255,.24)}
    .btn.primary{
      background:linear-gradient(135deg,rgba(139,92,255,.96),rgba(67,166,255,.6));
      border-color:transparent;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.25), 0 6px 18px rgba(80,94,255,.22);
    }
    .btn.primary:hover{box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 10px 28px rgba(80,94,255,.38)}
    .btn.danger{color:#ff8298;background:rgba(255,61,114,.1);border-color:rgba(255,61,114,.25)}
    .btn.danger:hover{background:rgba(255,61,114,.18)}
    .btn.slim{padding:7px 10px;font-size:12px;border-radius:10px}

    .chip{
      border:1px solid rgba(255,255,255,.12);
      background:rgba(255,255,255,.065);
      color:#dde5ff;
      border-radius:12px;
      padding:9px 12px;
      font-weight:700;
      font-size:12.5px;
      cursor:pointer;
      transition:.2s ease;
      font-family:var(--font);
    }
    .chip:hover{background:rgba(255,255,255,.1)}
    .chip.active{background:linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.44));border-color:transparent}

    .info-strip{
      border-radius:20px;
      padding:14px 18px;
      color:var(--muted);
      font-weight:500;
      font-size:13.5px;
      display:flex;align-items:center;gap:10px;
    }

    .class-list{display:grid;gap:14px;margin-top:16px}
    .class-card{border-radius:var(--radius-lg);padding:20px}
    .class-head{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:14px}
    .class-head h3{margin:0;font-size:18px;font-weight:800;letter-spacing:-.03em}
    .class-meta{display:flex;gap:14px;align-items:center;color:var(--muted);flex-wrap:wrap;font-size:13px}

    .qr{
      width:64px;height:64px;border-radius:10px;
      background:#fff;
      border:4px solid #f0f0f0;
      overflow:hidden;
      display:block;
      padding:0;
    }
    .qr.empty{
      background:rgba(139,92,255,.07);
      border:1.5px dashed rgba(139,92,255,.45);
      display:grid;place-items:center;
      color:rgba(139,92,255,.6);
      font-size:22px;
    }
    .qr img{
      width:100%;
      height:100%;
      display:block;
      object-fit:contain;
    }

    .toast-container{
      position:fixed;
      bottom:24px;right:24px;
      display:flex;flex-direction:column;gap:10px;
      z-index:1000;
    }
    .toast{
      padding:13px 18px;
      border-radius:16px;
      backdrop-filter:blur(24px);
      background:rgba(20,24,54,.92);
      border:1px solid rgba(255,255,255,.15);
      box-shadow:0 16px 40px rgba(0,0,0,.35);
      display:flex;align-items:center;gap:10px;
      font-size:13.5px;
      font-weight:600;
      animation:toast-in .3s ease, toast-out .3s ease 2.7s forwards;
      max-width:320px;
    }
    .toast.success{border-color:rgba(24,240,139,.3)}
    .toast.error{border-color:rgba(255,61,114,.3)}
    @keyframes toast-in{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:none}}
    @keyframes toast-out{from{opacity:1;transform:none}to{opacity:0;transform:translateX(20px)}}

    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.22)}

    hr.divider{border:none;border-top:1px solid rgba(255,255,255,.08);margin:16px 0}

    @media(max-width:1200px){
      .app{grid-template-columns:76px 1fr}
      .brand-text,.profile-info,.nav-label,.nav a span{display:none}
      .brand,.profile-card{justify-content:center}
      .profile-card{padding:10px;background:transparent;border:none}
      .nav a{justify-content:center;padding:11px}
      .nav-icon{margin:0 auto}
      .logout{justify-content:center;padding:11px}
      .logout-icon{margin:0}
      .stats{grid-template-columns:repeat(2,1fr)}
      .dashboard{grid-template-columns:1fr}
      .mini-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media(max-width:760px){
      .app{grid-template-columns:1fr}
      .sidebar{position:relative;height:auto;flex-direction:row;padding:10px;overflow-x:auto}
      main{padding:16px 14px}
      .stats{grid-template-columns:1fr 1fr}
      .topbar{flex-direction:column;align-items:stretch}
      .page-title h2{font-size:26px}
      .top-right{flex-wrap:wrap}
      .search-bar{width:100%}
    }
  </style>
</head>
<body>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  <div class="orb orb-4"></div>

  <div id="flashSuccess" data-message="{{ session('success') ?? '' }}" hidden></div>
  <div id="flashError" data-message="{{ session('error') ?? '' }}" hidden></div>

  <div class="toast-container" id="toastContainer"></div>

  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">▦</div>
        <div class="brand-text">
          <h1>QR Attendance</h1>
          <span>Admin System</span>
        </div>
      </div>

      <div class="profile-card">
        <div class="avatar">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          <div class="avatar-status"></div>
        </div>
        <div class="profile-info">
          <h2>{{ Auth::user()->name }} <span class="tag">Admin</span></h2>
          <p>System Administrator</p>
          <div class="online-badge"><span class="dot"></span> Online</div>
        </div>
      </div>

      <div class="nav-label">Main Menu</div>
      <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="@if(Route::currentRouteName() === 'admin.dashboard') active @endif">
          <span class="nav-icon">⌂</span>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.users') }}" class="@if(Route::currentRouteName() === 'admin.users') active @endif">
          <span class="nav-icon">👥</span>
          <span>Users</span>
        </a>
        <a href="{{ route('admin.professors') }}" class="@if(Route::currentRouteName() === 'admin.professors') active @endif">
          <span class="nav-icon">🎓</span>
          <span>Professors</span>
        </a>
        <a href="{{ route('admin.students') }}" class="@if(Route::currentRouteName() === 'admin.students') active @endif">
          <span class="nav-icon">🧑‍🎓</span>
          <span>Students</span>
        </a>
        <a href="{{ route('admin.classes') }}" class="@if(Route::currentRouteName() === 'admin.classes') active @endif">
          <span class="nav-icon">▤</span>
          <span>Classes</span>
        </a>
        <a href="{{ route('admin.qr-codes') }}" class="@if(Route::currentRouteName() === 'admin.qr-codes') active @endif">
          <span class="nav-icon">▦</span>
          <span>QR Codes</span>
        </a>
        <a href="{{ route('admin.attendance-records') }}" class="@if(Route::currentRouteName() === 'admin.attendance-records') active @endif">
          <span class="nav-icon">📋</span>
          <span>Attendance</span>
        </a>
        <a href="{{ route('admin.drop-requests') }}" class="@if(Route::currentRouteName() === 'admin.drop-requests') active @endif">
          <span class="nav-icon">⇩</span>
          <span>Drop Requests</span>
          <span class="nav-badge">{{ App\Models\DropRequest::where('status', 'pending')->count() }}</span>
        </a>
        <a href="{{ route('admin.logs') }}" class="@if(Route::currentRouteName() === 'admin.logs') active @endif">
          <span class="nav-icon">☷</span>
          <span>System Logs</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="@if(Route::currentRouteName() === 'admin.settings') active @endif">
          <span class="nav-icon">⚙</span>
          <span>Settings</span>
        </a>
      </nav>

      <div class="logout-wrap">
        <form method="POST" action="{{ route('logout') }}" style="width:100%">
          @csrf
          <button type="submit" class="logout">
            <span class="logout-icon">↪</span>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </aside>

    <!-- MAIN -->
    <main>
      <header class="topbar">
        <div class="page-title">
          <h2>@yield('pageTitle', 'Dashboard')</h2>
          <p>@yield('pageSubtitle', 'Welcome back, Admin!')</p>
        </div>
        <div class="top-right">
          <div class="clock-pill">
            📅 <span id="clockDate">{{ now()->format('M d, Y') }}</span>
            &nbsp;·&nbsp;
            <span id="clockTime" style="font-family:var(--mono);font-size:12px">—</span>
          </div>
          <div class="notif-btn">
            🔔
            <span class="notif-dot"></span>
          </div>
          <a href="{{ route('admin.settings') }}" class="top-avatar" title="Go to Settings">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</a>
        </div>
      </header>

      <div class="content">
        @yield('content')
      </div>
    </main>
  </div>

  <script>
    function updateClock(){
      const now = new Date();
      const h = now.getHours().toString().padStart(2,'0');
      const m = now.getMinutes().toString().padStart(2,'0');
      const s = now.getSeconds().toString().padStart(2,'0');
      document.getElementById('clockTime').textContent = `${h}:${m}:${s}`;
    }
    updateClock();
    setInterval(updateClock,1000);

    function showToast(msg, icon='✓', color='#4dffa0'){
      const tc = document.getElementById('toastContainer');
      const t = document.createElement('div');
      t.className = 'toast success';
      t.innerHTML = `<span style="font-size:18px">${icon}</span> <span>${msg}</span>`;
      t.style.borderColor = color+'55';
      tc.appendChild(t);
      setTimeout(()=>t.remove(), 3200);
    }

    const flashSuccess = document.getElementById('flashSuccess')?.dataset.message?.trim() ?? '';
    const flashError = document.getElementById('flashError')?.dataset.message?.trim() ?? '';

    if (flashSuccess) {
      showToast(flashSuccess, '✓', '#18f08b');
    }

    if (flashError) {
      showToast(flashError, '!', '#ff3d72');
    }

    if(!sessionStorage.getItem('admin_welcomed')){
      sessionStorage.setItem('admin_welcomed', 'true');
      setTimeout(()=>showToast('Welcome back, Admin!','👋','#b9c4ff'), 600);
    }
  </script>

  @yield('scripts')
</body>
</html>
