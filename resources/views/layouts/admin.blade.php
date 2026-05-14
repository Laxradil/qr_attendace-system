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
      --bg:#090818;
      --panel:#13122a;
      --panel2:#1a1938;
      --glass:rgba(255,255,255,.072);
      --glass-strong:rgba(255,255,255,.11);
      --stroke:rgba(255,255,255,.22);
      --stroke-soft:rgba(255,255,255,.10);
      --text:#e8e6ff;
      --muted:#9b97cc;
      --muted2:#5a5880;
      --faint:#626d96;
      --purple:#6c5ce7;
      --pl:#a29bfe;
      --blue:#0984e3;
      --green:#00b894;
      --amber:#fdcb6e;
      --red:#d63031;
      --yellow:#fdcb6e;
      --cyan:#00dfe6;
      --shadow:0 32px 90px rgba(0,0,0,.42);
      --blur:blur(32px) saturate(200%);
      --radius-lg:28px;
      --radius-md:18px;
      --radius-sm:12px;
      --font:'DM Sans',system-ui,sans-serif;
      --mono:'Space Mono',monospace;
    }

<<<<<<< HEAD
    *{box-sizing:border-box;margin:0;padding:0}

    html, body{
      height:100%;
      overflow:hidden;
    }
    body{
      height:100vh;
      font-family:var(--font);
      color:var(--text);
      overflow:hidden;
      background:
        radial-gradient(ellipse at 14% 12%, rgba(108,92,231,.16) 0%, transparent 38%),
        radial-gradient(ellipse at 85% 8%, rgba(9,132,227,.12) 0%, transparent 32%),
        radial-gradient(ellipse at 72% 90%, rgba(138,40,82,.12) 0%, transparent 34%),
        radial-gradient(ellipse at 50% 50%, rgba(0,223,230,.02) 0%, transparent 60%),
        linear-gradient(135deg, #090818 0%, #0d0c1d 40%, #0a0d22 70%, #050812 100%);
    }

    /* Animated mesh background */
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

    /* Floating orbs */
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

    /* Glass base */
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

    /* ═══════════ LAYOUT ═══════════ */
    .app{
      display:grid;
      grid-template-columns:260px 1fr;
      height:100vh;
      overflow:hidden;
      position:relative;
      z-index:1;
    }

    /* ═══════════ SIDEBAR ═══════════ */
    .sidebar{
      height:100vh;
      padding:14px 10px;
      border-right:1px solid rgba(255,255,255,.1);
      background:rgba(2,4,18,.70);
      backdrop-filter:blur(40px) saturate(180%);
      display:flex;
      flex-direction:column;
      gap:8px;
      overflow:hidden;
    }

    /* Brand */
    .brand{
      display:flex;
      align-items:center;
      gap:11px;
      padding:4px 8px 12px;
      border-bottom:1px solid rgba(255,255,255,.09);
      flex-shrink:0;
    }
    .logo{
      width:40px;height:40px;
      border-radius:13px;
      display:grid;
      place-items:center;
      font-size:20px;
      background:linear-gradient(145deg,rgba(108,92,231,1),rgba(9,132,227,.85));
      box-shadow:0 10px 30px rgba(93,93,255,.35), inset 0 1px 0 rgba(255,255,255,.3);
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

    /* Profile card */
    .profile-card{
      border-radius:18px;
      padding:10px 12px;
      display:flex;
      align-items:center;
      gap:10px;
      background:rgba(139,92,255,.12);
      border:1px solid rgba(139,92,255,.25);
      flex-shrink:0;
    }
    .avatar{
      width:40px;height:40px;
      border-radius:50%;
      display:grid;
      place-items:center;
      font-weight:900;
      font-size:14px;
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

    /* Nav section label */
    .nav-label{
      margin:2px 8px 0;
      color:var(--faint);
      font-size:10px;
      letter-spacing:.18em;
      text-transform:uppercase;
      font-weight:700;
      flex-shrink:0;
    }

    /* Nav */
    .nav{display:grid;gap:2px;flex-shrink:0}
    .nav a, .nav button{
      border:0;
      color:rgba(234,240,255,.75);
      background:transparent;
      padding:8px 10px;
      border-radius:13px;
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:600;
      cursor:pointer;
      transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;
      font-size:13.5px;
      font-family:var(--font);
      width:100%;
      position:relative;
      text-decoration:none;
    }
    .nav a .nav-icon, .nav button .nav-icon{
      width:30px;height:30px;
      border-radius:9px;
      display:grid;
      place-items:center;
      font-size:14px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.09);
      flex-shrink:0;
      transition:.2s ease;
    }
    .nav a:hover, .nav button:hover{
      background:rgba(255,255,255,.08);
      color:var(--text);
      transform:translateX(3px);
    }
    .nav a:hover .nav-icon, .nav button:hover .nav-icon{
      background:rgba(255,255,255,.12);
    }
    .nav a.active, .nav button.active{
      background:linear-gradient(135deg,rgba(108,92,231,.88),rgba(9,132,227,.5));
      color:#fff;
      box-shadow:0 12px 28px rgba(80,94,255,.16),inset 0 1px 0 rgba(255,255,255,.18);
    }
    .nav a.active .nav-icon, .nav button.active .nav-icon{
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

    /* Logout */
    .logout-wrap{margin-top:auto;border-top:1px solid rgba(255,255,255,.08);padding-top:10px;flex-shrink:0}
    .logout{
      border:1px solid transparent;
      background:transparent;
      color:#d63031;
      padding:9px 10px;
      border-radius:13px;
      display:flex;align-items:center;gap:10px;
      font-weight:700;cursor:pointer;
      transition:.2s ease;
      font-size:13.5px;
      font-family:var(--font);
      width:100%;
    }
    .logout:hover{background:rgba(214,48,49,.12);border-color:rgba(214,48,49,.2);transform:scale(1.02)}
    .logout-icon{
      width:30px;height:30px;border-radius:9px;
      display:grid;place-items:center;font-size:14px;
      background:transparent;
    }

    /* ═══════════ MAIN ═══════════ */
    main{
      padding:18px 24px 18px;
      height:100vh;
      display:flex;
      flex-direction:column;
      overflow:hidden;
    }

    /* Topbar */
    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:14px;
      margin-bottom:14px;
      flex-shrink:0;
    }
    .page-title h2{
      font-size:26px;
      font-weight:800;
      letter-spacing:-.06em;
      line-height:1;
      background:linear-gradient(135deg,#fff 40%,rgba(200,210,255,.7));
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
    }
    .page-title p{
      margin-top:4px;
      color:var(--muted);
      font-size:13px;
      font-weight:500;
    }
    .top-right{display:flex;align-items:center;gap:12px}

    /* Search */
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

    /* Clock */
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

    /* Notif bell */
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

    /* Top avatar */
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
    }
    .top-avatar:hover{transform:scale(1.06)}
    .top-avatar::after{
      content:"";
      position:absolute;
      bottom:1px;right:1px;
      width:11px;height:11px;
      border-radius:50%;
      background:var(--green);
      border:2px solid rgba(2,4,18,.9);
    }

    /* ═══════════ CONTENT ═══════════ */
    .content{flex:1;overflow:hidden;display:flex;flex-direction:column;min-height:0}
    .page{overflow-y:auto;overflow-x:hidden;animation:fadein .3s cubic-bezier(.4,0,.2,1);height:100%;}
    @keyframes fadein{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}

    /* ═══════════ STATS ═══════════ */
    .stats{display:grid;gap:12px;margin-bottom:12px;flex-shrink:0;}
    .stats-4{grid-template-columns:repeat(4,minmax(0,1fr))}
    .stats-3{grid-template-columns:repeat(3,minmax(0,1fr))}
    .stats-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    .stat{
      background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:16px;display:flex;align-items:center;gap:13px;
      transition:.3s cubic-bezier(.4,0,.2,1);cursor:default;text-decoration:none;color:inherit;
    }
    .stat:hover{transform:translateY(-4px);border-color:rgba(255,255,255,.32)}
    .stat-icon{
      width:46px;height:46px;border-radius:15px;display:grid;place-items:center;
      font-size:20px;border:1px solid rgba(255,255,255,.15);flex-shrink:0;
    }
    .stat-icon.blue{background:linear-gradient(145deg,rgba(9,132,227,.55),rgba(108,92,231,.28))}
    .stat-icon.green{background:linear-gradient(145deg,rgba(0,184,148,.42),rgba(9,132,227,.12))}
    .stat-icon.yellow{background:linear-gradient(145deg,rgba(253,203,110,.45),rgba(255,100,50,.15))}
    .stat-icon.purple{background:linear-gradient(145deg,rgba(108,92,231,.62),rgba(9,132,227,.22))}
    .stat-icon.red{background:linear-gradient(145deg,rgba(214,48,49,.55),rgba(255,100,50,.15))}
    .stat-body strong{display:block;font-size:24px;font-weight:900;letter-spacing:-.06em;font-variant-numeric:tabular-nums;}
    .stat-body span{display:block;color:#c9c7d9;font-weight:600;font-size:12px;margin-top:2px}
    .stat-body a{display:block;margin-top:6px;color:rgba(108,92,231,.9);text-decoration:none;font-size:11.5px;font-weight:700;letter-spacing:.02em}
    .stat-body a:hover{color:#b9c4ff}
    .trend{display:inline-flex;align-items:center;gap:3px;font-size:10.5px;font-weight:700;padding:2px 6px;border-radius:999px;margin-top:4px;}
    .trend.up{color:#00b894;background:rgba(0,184,148,.12)}
    .trend.down{color:#d63031;background:rgba(214,48,49,.12)}

    /* ═══════════ CARDS ═══════════ */
    .card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:16px;}
    .card.stretch{flex:1;min-height:0;display:flex;flex-direction:column;overflow:hidden}
    .section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-shrink:0;}
    .section-head h3{font-size:15px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px}
    .section-head a{color:rgba(108,92,231,.9);text-decoration:none;font-weight:700;font-size:12px;letter-spacing:.02em;cursor:pointer}
    .section-head a:hover{color:#b9c4ff}

    /* ═══════════ TABLES ═══════════ */
    .table-wrap{border-radius:14px;border:1px solid var(--line);overflow:hidden;background:var(--panel);}
    table{width:100%;border-collapse:collapse;font-size:13px;}
    th{
      background:rgba(255,255,255,.05);color:var(--faint);font-size:11px;letter-spacing:.12em;text-transform:uppercase;
      font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px);padding:14px 15px;
      text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle;
    }
    td{padding:14px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle;color:#e8eeff;}
    tr:last-child td{border-bottom:0}
    tbody tr:hover td{background:rgba(255,255,255,.028)}
    .user-cell{display:flex;align-items:center;gap:10px;font-weight:700}
    .small-avatar{
      width:34px;height:34px;border-radius:11px;display:grid;place-items:center;
      background:linear-gradient(145deg,rgba(108,92,231,.36),rgba(9,132,227,.2));
      font-size:11px;font-weight:900;border:1px solid rgba(108,92,231,.3);flex-shrink:0;
    }
    .muted{color:var(--muted);font-weight:400}
    .td-mono{font-family:var(--mono);font-size:12px;color:var(--muted)}
    .toolbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px;}
    .tools{display:flex;align-items:center;gap:9px;flex-wrap:wrap}
    .footer-bar{display:flex;justify-content:space-between;align-items:center;margin-top:16px;color:var(--muted);font-size:12.5px;}
    .pager{display:flex;gap:6px}
    .pager button{
      width:34px;height:34px;border:1px solid rgba(255,255,255,.14);border-radius:11px;
      background:rgba(255,255,255,.07);color:#fff;cursor:pointer;font-family:var(--font);
      font-weight:700;transition:.2s ease;
    }
    .pager button:hover{background:rgba(255,255,255,.13)}
    .pager .current{background:linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.55));border-color:transparent}

    /* ═══════════ BUTTONS ═══════════ */
    .btn{
      border:1px solid var(--line);background:rgba(255,255,255,.055);
      color:var(--text);border-radius:8px;padding:8px 14px;font-weight:700;cursor:pointer;
      display:inline-flex;gap:6px;align-items:center;transition:all .2s;
      font-size:11px;font-family:var(--font);
    }
    .btn:hover{background:var(--panel2)}
    .btn-pill{border-radius:999px !important;padding:10px 20px;display:flex;align-items:center;justify-content:center}
    .btn.primary{
      background:var(--purple);
      border-color:var(--purple);
      color:white;
    }
    .btn.primary:hover{
      background:#5a4fcf;
    }
    .btn.danger{
      background:rgba(214,48,49,.13);
      color:var(--red);
      border-color:rgba(214,48,49,.3);
    }
    .btn.sm{padding:7px 10px;font-size:12px;border-radius:10px}
    .btn.slim{padding:7px 10px;font-size:12px;border-radius:10px}

    /* ═══════════ CHIPS/PILLS ═══════════ */
    .chip{
      border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.065);
      color:#dde5ff;border-radius:12px;padding:9px 12px;font-weight:700;
      font-size:12.5px;cursor:pointer;transition:.2s ease;font-family:var(--font);
      display:inline-flex;align-items:center;gap:6px;
    }
    .chip:hover{background:rgba(255,255,255,.1)}
    .chip.active{background:linear-gradient(135deg,rgba(108,92,231,.88),rgba(9,132,227,.44));border-color:transparent}
    .pill{
      display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:6px 11px;
      font-size:11.5px;font-weight:700;border:1px solid rgba(255,255,255,.10);white-space:nowrap;letter-spacing:.01em;
    }
    .pill.green{color:#00b894;background:rgba(0,184,148,.13);border-color:rgba(0,184,148,.2)}
    .pill.red{color:#ff7f96;background:rgba(214,48,49,.12);border-color:rgba(214,48,49,.22)}
    .pill.yellow{color:#ffd584;background:rgba(253,203,110,.12);border-color:rgba(253,203,110,.22)}
    .pill.blue{color:#80cbff;background:rgba(9,132,227,.12);border-color:rgba(9,132,227,.22)}
    .pill.purple{color:#d8cdff;background:rgba(108,92,231,.15);border-color:rgba(108,92,231,.25)}
    .status-dot{width:7px;height:7px;border-radius:50%;display:inline-block;box-shadow:0 0 6px currentColor;}

    /* ═══════════ INPUTS ═══════════ */
    .fi{width:100%;padding:7px 10px;border:1px solid rgba(255,255,255,.12);border-radius:10px;
      background:rgba(255,255,255,.055);color:var(--text);font-size:13px;font-family:var(--font);
      transition:.2s ease;
    }
    .fi:focus{outline:none;border-color:rgba(139,92,255,.5);background:rgba(255,255,255,.08);box-shadow:0 0 0 3px rgba(139,92,255,.1)}
    .fi-label{font-size:12px;font-weight:600;color:var(--muted);margin-bottom:4px;display:block}

    /* ═══════════ LAYOUT GRIDS ═══════════ */
    .dashboard{display:grid;grid-template-columns:1.2fr .8fr;gap:12px;align-items:stretch;flex:1;min-height:0;overflow:hidden;}
    .dash-left{display:flex;flex-direction:column;gap:12px;min-height:0;overflow:hidden}
    .dash-right{display:flex;flex-direction:column;gap:12px;min-height:0;overflow:hidden}
    .g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .g3{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .g-6-4{display:grid;grid-template-columns:1.5fr 1fr;gap:12px}

    /* Scrollbar */
    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.22)}

=======
    body.theme-light{
      --bg:#ffffff;
      --panel:#ffffff;
      --panel2:#f3f0ff;
      --glass:#f5f5f5;
      --glass-strong:#ffffff;
      --stroke:#e5e7eb;
      --stroke-soft:#f3f4f6;
      --text:#0f172a;
      --muted:#475569;
      --muted2:#475569;
      --faint:#475569;
      --purple:#7c3aed;
      --pl:#a78bfa;
      --blue:#2563eb;
      --green:#16a34a;
      --amber:#d97706;
      --red:#dc2626;
      --yellow:#ca8a04;
      --cyan:#0891b2;
      --shadow:0 4px 12px rgba(0,0,0,.08);
      background:#f9fafb;
    }

    body.theme-light .glass,
    body.theme-light .glass-table,
    body.theme-light .card,
    body.theme-light .stat.glass,
    body.theme-light .page-card,
    body.theme-light .settings-container,
    body.theme-light .info-item,
    body.theme-light .row-item,
    body.theme-light .activity,
    body.theme-light .theme-option,
    body.theme-light .theme-option.selected
    {
      background: #ffffff !important;
      border: 1px solid #e5e7eb !important;
      color: #0f172a !important;
      box-shadow: 0 14px 50px rgba(15,23,42,.06) !important;
    }
    body.theme-light .theme-option:hover,
    body.theme-light .settings-btn,
    body.theme-light .pill,
    body.theme-light .btn,
    body.theme-light .btn.slim,
    body.theme-light .action-btn,
    body.theme-light .filter-btn.reset,
    body.theme-light .filter-select,
    body.theme-light .filter-input,
    body.theme-light .search-bar
    {
      background: #f8fafb !important;
      color: #0f172a !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .btn:hover,
    body.theme-light .action-btn:hover,
    body.theme-light .filter-btn:hover
    {
      background: #f1f5f9 !important;
    }
    body.theme-light [style*="background:rgba(8,12,30,.58)"] {
      background: #ffffff !important;
      color: #0f172a !important;
      border: 1px solid #e5e7eb !important;
    }
    body.theme-light [style*="background:rgba(255,255,255,.05)"],
    body.theme-light [style*="background:rgba(255,255,255,.04)"],
    body.theme-light [style*="background:rgba(255,255,255,.08)"],
    body.theme-light [style*="background:rgba(255,255,255,.1)"],
    body.theme-light [style*="background:rgba(255,255,255,.13)"],
    body.theme-light [style*="background:rgba(255,255,255,.055)"],
    body.theme-light [style*="background:rgba(255,255,255,.085)"],
    body.theme-light [style*="background:rgba(255,255,255,.18)"]
    {
      background: #f8fafb !important;
      border-color: #e5e7eb !important;
    }
    body.theme-light [style*="color:rgba(255,255,255,.5)"],
    body.theme-light [style*="color:rgba(255,255,255,.6)"],
    body.theme-light [style*="color:rgba(255,255,255,.7)"],
    body.theme-light [style*="color:rgba(255,255,255,.75)"]
    {
      color: #475569 !important;
    }
    body.theme-light [style*="color:rgba(139,92,255,.9)"] {
      color: #7c3aed !important;
    }
    body.theme-light [style*="border:1px solid rgba(255,255,255,.12)"],
    body.theme-light [style*="border:1px solid rgba(255,255,255,.14)"],
    body.theme-light [style*="border:1px solid rgba(255,255,255,.07)"],
    body.theme-light [style*="border:1px solid rgba(15,23,42,.1)"]
    {
      border-color: #e5e7eb !important;
    }

    body.theme-light label,
    body.theme-light .label,
    body.theme-light .info-label,
    body.theme-light .form-label,
    body.theme-light .text-muted,
    body.theme-light .muted,
    body.theme-light .form-control::placeholder,
    body.theme-light .form-select,
    body.theme-light .input-group-text
    {
      color: #1f2937 !important;
    }

    body.theme-light table,
    body.theme-light .table-wrap table,
    body.theme-light .table-wrap,
    body.theme-light .table-responsive,
    body.theme-light .table thead th,
    body.theme-light .table tbody td,
    body.theme-light .table-striped tbody tr:nth-child(odd),
    body.theme-light .table-striped tbody tr:nth-child(even)
    {
      background: #ffffff !important;
      color: #0f172a !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .table-striped tbody tr:nth-child(even) {
      background: #f8fafb !important;
    }

    body.theme-light .section-head h3 {
      color: #7c3aed !important;
    }

    body.theme-ash{
      --bg:#e2e8f0;
      --panel:#f8fafc;
      --panel2:#cbd5e1;
      --glass:rgba(255,255,255,.92);
      --glass-strong:rgba(255,255,255,.98);
      --stroke:rgba(15,23,42,.12);
      --stroke-soft:rgba(15,23,42,.08);
      --text:#0f172a;
      --muted:#475569;
      --muted2:#64748b;
      --purple:#6d28d9;
      --pl:#7c3aed;
      --blue:#2563eb;
      --green:#16a34a;
      --amber:#d97706;
      --red:#b91c1c;
      --yellow:#ca8a04;
      --cyan:#0c4a6e;
      --shadow:0 32px 90px rgba(15,23,42,.12);
      background:linear-gradient(135deg,#f8fafc 0%,#cbd5e1 100%);
    }

    body.theme-dark{
      --bg:#090818;
      --panel:#13122a;
      --panel2:#1a1938;
      --glass:rgba(255,255,255,.08);
      --glass-strong:rgba(255,255,255,.12);
      --stroke:rgba(255,255,255,.14);
      --stroke-soft:rgba(255,255,255,.07);
      --text:#e8e6ff;
      --muted:#9b97cc;
      --muted2:#5a5880;
      --purple:#6c5ce7;
      --pl:#a29bfe;
      --blue:#0984e3;
      --green:#00b894;
      --amber:#fdcb6e;
      --red:#d63031;
      --yellow:#fdcb6e;
      --cyan:#00dfe6;
      background:radial-gradient(circle at top left, rgba(67,166,255,.16), transparent 22%), radial-gradient(circle at top right, rgba(139,92,255,.12), transparent 22%), linear-gradient(180deg, #090818 0%, #05060f 100%);
    }

    body.theme-onyx{
      --bg:#070a16;
      --panel:#111426;
      --panel2:#171c34;
      --glass:rgba(255,255,255,.07);
      --glass-strong:rgba(255,255,255,.1);
      --stroke:rgba(255,255,255,.12);
      --stroke-soft:rgba(255,255,255,.06);
      --text:#f4f7ff;
      --muted:#a5aed4;
      --muted2:#8892bf;
      --purple:#7c3aed;
      --pl:#8b5cff;
      --blue:#60a5fa;
      --green:#22c55e;
      --amber:#facc15;
      --red:#f472b6;
      --yellow:#facc15;
      --cyan:#22d3ee;
      --shadow:0 32px 90px rgba(0,0,0,.38);
      background:radial-gradient(circle at top left, rgba(79,70,229,.14), transparent 20%), linear-gradient(180deg, #080a14 0%, #0d1527 100%);
    }

    *{box-sizing:border-box;margin:0;padding:0}

    html, body{
      height:100%;
      overflow:hidden;
    }
    body{
      height:100vh;
      font-family:var(--font);
      color:var(--text);
      overflow:hidden;
      background:
        radial-gradient(ellipse at 14% 12%, rgba(108,92,231,.16) 0%, transparent 38%),
        radial-gradient(ellipse at 85% 8%, rgba(9,132,227,.12) 0%, transparent 32%),
        radial-gradient(ellipse at 72% 90%, rgba(138,40,82,.12) 0%, transparent 34%),
        radial-gradient(ellipse at 50% 50%, rgba(0,223,230,.02) 0%, transparent 60%),
        linear-gradient(135deg, #090818 0%, #0d0c1d 40%, #0a0d22 70%, #050812 100%);
    }

    /* Animated mesh background */
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

    /* Floating orbs */
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

    /* Glass base */
    .glass{
      border:1px solid var(--stroke);
      background:rgba(255,255,255,.18);
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

    /* ═══════════ LAYOUT ═══════════ */
    .app{
      display:grid;
      grid-template-columns:260px 1fr;
      height:100vh;
      overflow:hidden;
      position:relative;
      z-index:1;
    }

    /* ═══════════ SIDEBAR ═══════════ */
    .sidebar{
      height:100vh;
      padding:14px 10px;
      border-right:1px solid rgba(255,255,255,.1);
      background:rgba(2,4,18,.70);
      backdrop-filter:blur(40px) saturate(180%);
      display:flex;
      flex-direction:column;
      gap:8px;
      overflow:hidden;
    }

    /* Brand */
    .brand{
      display:flex;
      align-items:center;
      gap:11px;
      padding:4px 8px 12px;
      border-bottom:1px solid rgba(255,255,255,.09);
      flex-shrink:0;
    }
    .logo{
      width:40px;height:40px;
      border-radius:13px;
      display:grid;
      place-items:center;
      font-size:20px;
      background:linear-gradient(145deg,rgba(108,92,231,1),rgba(9,132,227,.85));
      box-shadow:0 10px 30px rgba(93,93,255,.35), inset 0 1px 0 rgba(255,255,255,.3);
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

    /* Profile card */
    .profile-card{
      border-radius:18px;
      padding:10px 12px;
      display:flex;
      align-items:center;
      gap:10px;
      background:rgba(139,92,255,.12);
      border:1px solid rgba(139,92,255,.25);
      flex-shrink:0;
    }
    .avatar{
      width:40px;height:40px;
      border-radius:50%;
      display:grid;
      place-items:center;
      font-weight:900;
      font-size:14px;
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

    /* Nav section label */
    .nav-label{
      margin:2px 8px 0;
      color:var(--faint);
      font-size:10px;
      letter-spacing:.18em;
      text-transform:uppercase;
      font-weight:700;
      flex-shrink:0;
    }

    /* Nav */
    .nav{display:grid;gap:2px;flex-shrink:0}
    .nav a, .nav button{
      border:0;
      color:rgba(234,240,255,.75);
      background:transparent;
      padding:8px 10px;
      border-radius:13px;
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:600;
      cursor:pointer;
      transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;
      font-size:13.5px;
      font-family:var(--font);
      width:100%;
      position:relative;
      text-decoration:none;
    }
    .nav a .nav-icon, .nav button .nav-icon{
      width:30px;height:30px;
      border-radius:9px;
      display:grid;
      place-items:center;
      font-size:14px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.09);
      flex-shrink:0;
      transition:.2s ease;
    }
    .nav a:hover, .nav button:hover{
      background:rgba(255,255,255,.08);
      color:var(--text);
      transform:translateX(3px);
    }
    .nav a:hover .nav-icon, .nav button:hover .nav-icon{
      background:rgba(255,255,255,.12);
    }
    .nav a.active, .nav button.active{
      background:linear-gradient(135deg,rgba(108,92,231,.88),rgba(9,132,227,.5));
      color:#fff;
      box-shadow:0 12px 28px rgba(80,94,255,.16),inset 0 1px 0 rgba(255,255,255,.18);
    }
    .nav a.active .nav-icon, .nav button.active .nav-icon{
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

    /* Logout */
    .logout-wrap{margin-top:auto;border-top:1px solid rgba(255,255,255,.08);padding-top:10px;flex-shrink:0}
    .logout{
      border:1px solid transparent;
      background:transparent;
      color:#d63031;
      padding:9px 10px;
      border-radius:13px;
      display:flex;align-items:center;gap:10px;
      font-weight:700;cursor:pointer;
      transition:.2s ease;
      font-size:13.5px;
      font-family:var(--font);
      width:100%;
    }
    .logout:hover{background:rgba(214,48,49,.12);border-color:rgba(214,48,49,.2);transform:scale(1.02)}
    .logout-icon{
      width:30px;height:30px;border-radius:9px;
      display:grid;place-items:center;font-size:14px;
      background:transparent;
    }

    /* ═══════════ MAIN ═══════════ */
    main{
      padding:18px 24px 18px;
      height:100vh;
      display:flex;
      flex-direction:column;
      overflow:hidden;
    }

    /* Topbar */
    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:14px;
      margin-bottom:14px;
      flex-shrink:0;
    }
    .page-title h2{
      font-size:26px;
      font-weight:800;
      letter-spacing:-.06em;
      line-height:1;
      color:#0f172a;
      background:none;
      -webkit-background-clip:unset;
      -webkit-text-fill-color:unset;
    }
    .page-title p{
      margin-top:4px;
      color:#334155;
      font-size:13px;
      font-weight:500;
    }
    .top-right{display:flex;align-items:center;gap:12px}

    /* Search */
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

    /* Clock */
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

    /* Top avatar */
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
    }
    .top-avatar:hover{transform:scale(1.06)}
    .top-avatar::after{
      content:"";
      position:absolute;
      bottom:1px;right:1px;
      width:11px;height:11px;
      border-radius:50%;
      background:var(--green);
      border:2px solid rgba(2,4,18,.9);
    }

    /* ═══════════ CONTENT ═══════════ */
    .content{flex:1;overflow:hidden;display:flex;flex-direction:column;min-height:0}
    .page{overflow-y:auto;overflow-x:hidden;animation:fadein .3s cubic-bezier(.4,0,.2,1);height:100%;}
    @keyframes fadein{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}

    /* ═══════════ STATS ═══════════ */
    .stats{display:grid;gap:12px;margin-bottom:12px;flex-shrink:0;}
    .stats-4{grid-template-columns:repeat(4,minmax(0,1fr))}
    .stats-3{grid-template-columns:repeat(3,minmax(0,1fr))}
    .stats-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    .stat{
      background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:16px;display:flex;align-items:center;gap:13px;
      transition:.3s cubic-bezier(.4,0,.2,1);cursor:default;text-decoration:none;color:inherit;
    }
    .stat:hover{transform:translateY(-4px);border-color:rgba(255,255,255,.32)}
    .stat-icon{
      width:46px;height:46px;border-radius:15px;display:grid;place-items:center;
      font-size:20px;border:1px solid rgba(255,255,255,.15);flex-shrink:0;
    }
    .stat-icon.blue{background:linear-gradient(145deg,rgba(9,132,227,.55),rgba(108,92,231,.28))}
    .stat-icon.green{background:linear-gradient(145deg,rgba(0,184,148,.42),rgba(9,132,227,.12))}
    .stat-icon.yellow{background:linear-gradient(145deg,rgba(253,203,110,.45),rgba(255,100,50,.15))}
    .stat-icon.purple{background:rgba(139,92,255,.18);border:1px solid rgba(139,92,255,.22)}
    .stat-icon.red{background:linear-gradient(145deg,rgba(214,48,49,.55),rgba(255,100,50,.15))}
    .stat-body strong{display:block;font-size:24px;font-weight:900;letter-spacing:-.06em;font-variant-numeric:tabular-nums;}
    .stat-body span{display:block;color:#c9c7d9;font-weight:600;font-size:12px;margin-top:2px}
    .stat-body a{display:block;margin-top:6px;color:rgba(108,92,231,.9);text-decoration:none;font-size:11.5px;font-weight:700;letter-spacing:.02em}
    .stat-body a:hover{color:#b9c4ff}
    .trend{display:inline-flex;align-items:center;gap:3px;font-size:10.5px;font-weight:700;padding:2px 6px;border-radius:999px;margin-top:4px;}
    .trend.up{color:#00b894;background:rgba(0,184,148,.12)}
    .trend.down{color:#d63031;background:rgba(214,48,49,.12)}

    /* ═══════════ CARDS ═══════════ */
    .card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:16px;}
    .card.stretch{flex:1;min-height:0;display:flex;flex-direction:column;overflow:hidden}
    .section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-shrink:0;}
    .section-head h3{font-size:15px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px}
    .section-head a{color:rgba(108,92,231,.9);text-decoration:none;font-weight:700;font-size:12px;letter-spacing:.02em;cursor:pointer}
    .section-head a:hover{color:#b9c4ff}

    /* ═══════════ TABLES ═══════════ */
    .table-wrap{border-radius:14px;border:1px solid var(--line);overflow:hidden;background:var(--panel);}
    table{width:100%;border-collapse:collapse;font-size:13px;}
    th{
      background:rgba(255,255,255,.05);color:var(--faint);font-size:11px;letter-spacing:.12em;text-transform:uppercase;
      font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px);padding:14px 15px;
      text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle;
    }
    td{padding:14px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle;color:#e8eeff;}
    tr:last-child td{border-bottom:0}
    tbody tr:hover td{background:rgba(255,255,255,.028)}
    .user-cell{display:flex;align-items:center;gap:10px;font-weight:700}
    .small-avatar{
      width:34px;height:34px;border-radius:11px;display:grid;place-items:center;
      background:linear-gradient(145deg,rgba(108,92,231,.36),rgba(9,132,227,.2));
      font-size:11px;font-weight:900;border:1px solid rgba(108,92,231,.3);flex-shrink:0;
    }
    .muted{color:var(--muted);font-weight:400}
    .td-mono{font-family:var(--mono);font-size:12px;color:var(--muted)}
    .toolbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px;}
    .tools{display:flex;align-items:center;gap:9px;flex-wrap:wrap}
    .footer-bar{display:flex;justify-content:space-between;align-items:center;margin-top:16px;color:var(--muted);font-size:12.5px;}
    .pager{display:flex;gap:6px}
    .pager button{
      width:34px;height:34px;border:1px solid rgba(255,255,255,.14);border-radius:11px;
      background:rgba(255,255,255,.07);color:#fff;cursor:pointer;font-family:var(--font);
      font-weight:700;transition:.2s ease;
    }
    .pager button:hover{background:rgba(255,255,255,.13)}
    .pager .current{background:linear-gradient(135deg,rgba(139,92,255,.95),rgba(67,166,255,.55));border-color:transparent}

    /* ═══════════ BUTTONS ═══════════ */
    .btn{
      border:1px solid var(--line);background:rgba(255,255,255,.055);
      color:var(--text);border-radius:8px;padding:8px 14px;font-weight:700;cursor:pointer;
      display:inline-flex;gap:6px;align-items:center;transition:all .2s;
      font-size:11px;font-family:var(--font);
    }
    .btn:hover{background:var(--panel2)}
    .btn-pill{border-radius:999px !important;padding:10px 20px;display:flex;align-items:center;justify-content:center}
    .btn.primary{
      background:var(--purple);
      border-color:var(--purple);
      color:white;
    }
    .btn.primary:hover{
      background:#5a4fcf;
    }
    .btn.danger{
      background:rgba(214,48,49,.13);
      color:var(--red);
      border-color:rgba(214,48,49,.3);
    }
    .btn.sm{padding:7px 10px;font-size:12px;border-radius:10px}
    .btn.slim{padding:7px 10px;font-size:12px;border-radius:10px}

    /* ═══════════ CHIPS/PILLS ═══════════ */
    .chip{
      border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.065);
      color:#dde5ff;border-radius:12px;padding:9px 12px;font-weight:700;
      font-size:12.5px;cursor:pointer;transition:.2s ease;font-family:var(--font);
      display:inline-flex;align-items:center;gap:6px;
    }
    .chip:hover{background:rgba(255,255,255,.1)}
    .chip.active{background:linear-gradient(135deg,rgba(108,92,231,.88),rgba(9,132,227,.44));border-color:transparent}
    .pill{
      display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:6px 11px;
      font-size:11.5px;font-weight:700;border:1px solid rgba(255,255,255,.10);white-space:nowrap;letter-spacing:.01em;
    }
    .pill.green{color:#00b894;background:rgba(0,184,148,.13);border-color:rgba(0,184,148,.2)}
    .pill.red{color:#ff7f96;background:rgba(214,48,49,.12);border-color:rgba(214,48,49,.22)}
    .pill.yellow{color:#ffd584;background:rgba(253,203,110,.12);border-color:rgba(253,203,110,.22)}
    .pill.blue{color:#80cbff;background:rgba(9,132,227,.12);border-color:rgba(9,132,227,.22)}
    .pill.purple{color:#d8cdff;background:rgba(108,92,231,.15);border-color:rgba(108,92,231,.25)}
    .status-dot{width:7px;height:7px;border-radius:50%;display:inline-block;box-shadow:0 0 6px currentColor;}

    /* ═══════════ INPUTS ═══════════ */
    .fi{width:100%;padding:7px 10px;border:1px solid rgba(255,255,255,.12);border-radius:10px;
      background:rgba(255,255,255,.055);color:var(--text);font-size:13px;font-family:var(--font);
      transition:.2s ease;
    }
    .fi:focus{outline:none;border-color:rgba(139,92,255,.5);background:rgba(255,255,255,.08);box-shadow:0 0 0 3px rgba(139,92,255,.1)}
    .fi-label{font-size:12px;font-weight:600;color:var(--muted);margin-bottom:4px;display:block}

    /* ═══════════ LAYOUT GRIDS ═══════════ */
    .dashboard{display:grid;grid-template-columns:1.2fr .8fr;gap:12px;align-items:stretch;flex:1;min-height:0;overflow:hidden;}
    .dash-left{display:flex;flex-direction:column;gap:12px;min-height:0;overflow:hidden}
    .dash-right{display:flex;flex-direction:column;gap:12px;min-height:0;overflow:hidden}
    .g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .g3{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .g-6-4{display:grid;grid-template-columns:1.5fr 1fr;gap:12px}

    /* Scrollbar */
    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.22)}

>>>>>>> origin/branch-ni-kirb
    /* Responsive */
    @media(max-width:1200px){
      .app{grid-template-columns:76px 1fr}
      .brand-text,.profile-info,.nav-label{display:none}
      .brand,.profile-card{justify-content:center}
      .nav button{justify-content:center;padding:11px}
    }
    @media(max-width:760px){
      .app{grid-template-columns:1fr}
      .sidebar{position:relative;height:auto;flex-direction:row;padding:10px;overflow-x:auto}
      main{padding:16px 14px}
      .topbar{flex-direction:column;align-items:stretch}
      .top-right{flex-wrap:wrap}
    }
  </style>
</head>
<body>
  <!-- Ambient orbs -->
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  <div class="orb orb-4"></div>

  <div class="app">
    <!-- ═══════ SIDEBAR ═══════ -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">▦</div>
        <div class="brand-text">
          <h1>QR Attendance</h1>
          <span>Admin System</span>
        </div>
      </div>

      <div class="profile-card">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 1)) }}<div class="avatar-status"></div></div>
        <div class="profile-info">
          <h2>@yield('userRole', 'Admin') <span class="tag">Admin</span></h2>
          <p>System Administrator</p>
          <div class="online-badge"><span class="dot"></span> Online</div>
        </div>
      </div>

      <div class="nav-label">Main Menu</div>
      <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="nav-icon">⌂</span>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
          <span class="nav-icon">👥</span>
          <span>Users</span>
        </a>
        <a href="{{ route('admin.professors') }}" class="nav-link {{ request()->routeIs('admin.professors') ? 'active' : '' }}">
          <span class="nav-icon">🎓</span>
          <span>Professors</span>
        </a>
        <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
          <span class="nav-icon">🧑‍🎓</span>
          <span>Students</span>
        </a>
        <a href="{{ route('admin.classes') }}" class="nav-link {{ request()->routeIs('admin.classes*') ? 'active' : '' }}">
          <span class="nav-icon">▤</span>
          <span>Classes</span>
        </a>
        <a href="{{ route('admin.qr-codes') }}" class="nav-link {{ request()->routeIs('admin.qr-codes*') ? 'active' : '' }}">
          <span class="nav-icon">▦</span>
          <span>QR Codes</span>
        </a>
        <a href="{{ route('admin.attendance-records') }}" class="nav-link {{ request()->routeIs('admin.attendance-records') ? 'active' : '' }}">
          <span class="nav-icon">📋</span>
          <span>Attendance</span>
        </a>
        <a href="{{ route('admin.drop-requests') }}" class="nav-link {{ request()->routeIs('admin.drop-requests*') ? 'active' : '' }}">
          <span class="nav-icon">⇩</span>
          <span>Drop Requests</span>
          <span class="nav-badge">{{ App\Models\DropRequest::where('status', 'pending')->count() }}</span>
        </a>
        <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
          <span class="nav-icon">☷</span>
          <span>System Logs</span>
        </a>
      </nav>

      <div class="logout-wrap">
        <form method="POST" action="{{ route('logout') }}" style="width:100%;">
          @csrf
          <button type="submit" class="logout">
            <span class="logout-icon">↪</span>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </aside>

    <!-- ═══════ MAIN ═══════ -->
    <main>
      <header class="topbar">
        <div class="page-title">
          <h2>@yield('header', 'Dashboard')</h2>
          <p>@yield('subheader', "Welcome back! Here's what's happening in the system.")</p>
        </div>
        <div class="top-right">
          <div class="search-bar">🔍 <span style="font-size:13.5px">@yield('searchPlaceholder', 'Search...')</span></div>
          <div class="clock-pill">
            📅 <span id="clockDate">May 7, 2026</span>
            &nbsp;·&nbsp;
            <span id="clockTime" style="font-family:var(--mono);font-size:12px">—</span>
          </div>
<<<<<<< HEAD
          <div class="notif-btn">🔔<span class="notif-dot"></span></div>
=======
>>>>>>> origin/branch-ni-kirb
          <div class="top-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        </div>
      </header>

      <div class="content">
        @if($errors->any())
          <div class="glass" style="border-radius:var(--radius-lg);padding:14px;background:rgba(255,61,114,.08);border:1px solid rgba(255,61,114,.22);margin-bottom:16px;">
            <ul style="color:var(--muted);font-size:13px;margin:0;padding:0;">
              @foreach($errors->all() as $error)
                <li style="margin-bottom:6px;color:#ff8298;">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(session('success'))
          <div class="glass" style="border-radius:var(--radius-lg);padding:14px;background:rgba(24,240,139,.08);border:1px solid rgba(24,240,139,.22);margin-bottom:16px;color:#4dffa0;font-weight:600;font-size:13px;">
            {{ session('success') }}
          </div>
        @endif

        <div class="page">
          @yield('content')
        </div>
      </div>
    </main>
  </div>

  <script>
    // ─── Live Clock ───
    function updateClock(){
      const now = new Date();
      const h = now.getHours().toString().padStart(2,'0');
      const m = now.getMinutes().toString().padStart(2,'0');
      const s = now.getSeconds().toString().padStart(2,'0');
      document.getElementById('clockTime').textContent = `${h}:${m}:${s}`;
      document.getElementById('clockDate').textContent = now.toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'});
    }
    updateClock();
    setInterval(updateClock,1000);

    // ─── Toast ───
    function showToast(msg, icon='✓', color='#4dffa0'){
      const tc = document.querySelector('main');
      if (!tc) return;
      const t = document.createElement('div');
      t.style.cssText = `
        position:fixed;bottom:24px;right:24px;
        padding:13px 18px;border-radius:16px;
        backdrop-filter:blur(24px);background:rgba(20,24,54,.92);
        border:1px solid rgba(255,255,255,.15);
        box-shadow:0 16px 40px rgba(0,0,0,.35);
        display:flex;align-items:center;gap:10px;
        font-size:13.5px;font-weight:600;
        animation:toast-in 0.3s ease, toast-out 0.3s ease 2.7s forwards;
        max-width:320px;z-index:9999;
      `;
      t.innerHTML = `<span style="font-size:18px">${icon}</span> <span>${msg}</span>`;
      t.style.borderColor = color + '55';
      document.body.appendChild(t);
      setTimeout(()=>t.remove(), 3200);
    }
    
    const style = document.createElement('style');
    style.textContent = `
      @keyframes toast-in{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:none}}
      @keyframes toast-out{from{opacity:1;transform:none}to{opacity:0;transform:translateX(20px)}}
    `;
    document.head.appendChild(style);
<<<<<<< HEAD
  </script>
=======
    (function() {
      const themeKey = 'qr_attendance_theme';
      const themeNames = ['light','ash','dark','onyx'];
      const defaultTheme = 'dark';
      const current = themeNames.includes(localStorage.getItem(themeKey)) ? localStorage.getItem(themeKey) : defaultTheme;
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + current);
    })();  </script>
>>>>>>> origin/branch-ni-kirb
</body>
</html>
