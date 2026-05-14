<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schedule Management — Attendance System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
      min-height:100vh;
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
      background:rgba(255,255,255,.18);
      backdrop-filter:var(--blur);
      -webkit-backdrop-filter:var(--blur);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.32),inset 0 -1px 0 rgba(0,0,0,.18),var(--shadow);
      position:relative;overflow:hidden;
      border-radius:var(--radius-lg);
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
      position:relative;overflow:hidden;
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
      position:relative;
    }
    .avatar-status{position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:var(--green);border:2px solid rgba(2,4,18,.8);box-shadow:0 0 8px rgba(24,240,139,.6)}
    .profile-info h2{font-size:14px;font-weight:700;display:flex;gap:6px;align-items:center}
    .tag{font-size:10px;padding:3px 7px;border-radius:999px;background:rgba(139,92,255,.3);color:#efeaff;border:1px solid rgba(139,92,255,.4)}
    .profile-info p{margin-top:3px;color:var(--muted);font-size:11.5px}
    .online-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--green);margin-top:2px;font-weight:600}
    .dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 6px rgba(24,240,139,.8);animation:pulse-dot 2s infinite}

    .nav-label{margin:2px 8px 0;color:var(--faint);font-size:10px;letter-spacing:.18em;text-transform:uppercase;font-weight:700;flex-shrink:0}
    .nav{display:grid;gap:2px;flex-shrink:0}
    .nav button{
      border:0;color:rgba(234,240,255,.75);background:transparent;
      padding:8px 10px;border-radius:13px;display:flex;align-items:center;gap:10px;
      font-weight:600;cursor:pointer;transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;font-size:13.5px;font-family:var(--font);width:100%;position:relative;
    }
    .nav button .nav-icon{
      width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-size:14px;
      background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.09);flex-shrink:0;transition:.2s ease;
    }
    .nav button:hover{background:rgba(255,255,255,.08);color:var(--text);transform:translateX(3px)}
    .nav button:hover .nav-icon{background:rgba(255,255,255,.12)}
    .nav button.active{background:linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));color:#fff;box-shadow:0 12px 28px rgba(80,94,255,.26),inset 0 1px 0 rgba(255,255,255,.28)}
    .nav button.active .nav-icon{background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.25)}

    .logout-wrap{margin-top:auto;border-top:1px solid rgba(255,255,255,.08);padding-top:10px;flex-shrink:0}
    .logout{
      border:1px solid rgba(255,61,114,.2);background:rgba(255,61,114,.07);color:#ff8298;
      padding:9px 10px;border-radius:13px;display:flex;align-items:center;gap:10px;
      font-weight:700;cursor:pointer;transition:.2s ease;font-size:13.5px;font-family:var(--font);width:100%;
    }
    .logout:hover{background:rgba(255,61,114,.14);transform:translateX(3px)}
    .logout-icon{width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-size:14px;background:rgba(255,61,114,.15)}

    main{padding:18px 24px 18px;height:100vh;display:flex;flex-direction:column;overflow:hidden}
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:14px;flex-shrink:0}
    .page-title h2{font-size:26px;font-weight:800;letter-spacing:-.06em;line-height:1;background:linear-gradient(135deg,#fff 40%,rgba(200,210,255,.7));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
    .page-title p{margin-top:4px;color:var(--muted);font-size:13px;font-weight:500}
    .top-right{display:flex;align-items:center;gap:12px}
    .search-bar{height:44px;width:280px;border-radius:999px;padding:0 16px;display:flex;align-items:center;gap:9px;color:var(--muted);border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.06);backdrop-filter:blur(20px);box-shadow:inset 0 1px 0 rgba(255,255,255,.15);font-size:13.5px}
    .search-bar:hover{border-color:rgba(255,255,255,.25);background:rgba(255,255,255,.09)}
    .search-bar input{flex:1;border:0;background:transparent;color:var(--text);outline:none;font-size:13.5px;min-width:0}
    .clock-pill{display:flex;align-items:center;gap:8px;padding:0 14px;height:44px;border-radius:999px;border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.06);backdrop-filter:blur(16px);font-size:13px;font-weight:700;white-space:nowrap;color:var(--text)}
    .clock-date{color:var(--muted);font-size:12px}
    .top-avatar{width:44px;height:44px;border-radius:50%;background:linear-gradient(145deg,#8b5cff,#4915d3);border:2px solid rgba(255,255,255,.25);display:grid;place-items:center;font-weight:900;cursor:pointer;position:relative;transition:.2s ease}
    .top-avatar:hover{transform:scale(1.06)}
    .top-avatar::after{content:"";position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:var(--green);border:2px solid rgba(2,4,18,.9)}

    .content{flex:1;overflow-y:auto;min-height:0;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent}
    .page{display:none}
    .page.active{display:block}

    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:14px}
    .stat{border-radius:22px;padding:16px;display:flex;gap:12px;align-items:flex-start;transition:.25s ease;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.06)}
    .stat:hover{transform:translateY(-3px);border-color:rgba(255,255,255,.3)}
    .stat-icon{width:40px;height:40px;border-radius:13px;display:grid;place-items:center;font-size:18px;flex-shrink:0}
    .stat-icon.blue{background:rgba(67,166,255,.18);border:1px solid rgba(67,166,255,.25)}
    .stat-icon.green{background:rgba(24,240,139,.15);border:1px solid rgba(24,240,139,.2)}
    .stat-icon.yellow{background:rgba(255,199,90,.15);border:1px solid rgba(255,199,90,.2)}
    .stat-icon.purple{background:rgba(139,92,255,.18);border:1px solid rgba(139,92,255,.22)}
    .stat-body strong{font-size:26px;font-weight:900;letter-spacing:-.05em;display:block;line-height:1}
    .stat-body span{color:var(--muted);font-size:12px;font-weight:500;margin-top:3px;display:block}

    .glass-table{border-radius:var(--radius-lg);padding:20px;transition:.3s ease}
    .glass-table:hover{transform:translateY(-3px);border-color:rgba(255,255,255,.3)}
    .table-wrap{overflow-x:auto;border-radius:var(--radius-md);scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.12) transparent}
    table{width:100%;min-width:700px;border-collapse:separate;border-spacing:0}
    th,td{padding:14px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle}
    th{background:rgba(255,255,255,.055);color:var(--faint);font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px)}
    th:first-child{border-radius:var(--radius-md) 0 0 0}
    th:last-child{border-radius:0 var(--radius-md) 0 0}
    td{color:#e8eeff;font-size:13.5px}
    tr:last-child td{border-bottom:0}
    tr:hover td{background:rgba(255,255,255,.028)}
    .user-cell{display:flex;align-items:center;gap:10px;font-weight:700}
    .small-avatar{width:34px;height:34px;border-radius:11px;display:grid;place-items:center;background:linear-gradient(145deg,rgba(139,92,255,.36),rgba(67,166,255,.2));font-size:11px;font-weight:900;border:1px solid rgba(139,92,255,.3);flex-shrink:0}
    .muted{color:var(--muted);font-weight:400}

    .btn{border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:#fff;border-radius:13px;padding:10px 14px;font-weight:700;cursor:pointer;transition:.2s ease;box-shadow:inset 0 1px 0 rgba(255,255,255,.14);font-size:13px;font-family:var(--font)}
    .btn:hover{transform:translateY(-2px);background:rgba(255,255,255,.13);border-color:rgba(255,255,255,.24)}
    .btn.primary{background:linear-gradient(135deg,rgba(139,92,255,.96),rgba(67,166,255,.6));border-color:transparent;box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 6px 18px rgba(80,94,255,.22)}
    .btn.primary:hover{box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 10px 28px rgba(80,94,255,.38)}
    .btn.danger{color:#ff8298;background:rgba(255,61,114,.1);border-color:rgba(255,61,114,.25)}
    .btn.danger:hover{background:rgba(255,61,114,.18)}
    .btn.slim{padding:7px 10px;font-size:12px;border-radius:10px}

    .section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-shrink:0}
    .section-head h3{font-size:15px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px}
    .section-head button{white-space:nowrap}

    .toast-container{position:fixed;bottom:24px;right:24px;display:flex;flex-direction:column;gap:10px;z-index:1000}
    .toast{padding:13px 18px;border-radius:16px;backdrop-filter:blur(24px);background:rgba(20,24,54,.92);border:1px solid rgba(255,255,255,.15);box-shadow:0 16px 40px rgba(0,0,0,.35);display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600;animation:toast-in .3s ease,toast-out .3s ease 2.7s forwards;max-width:320px}
    .toast.success{border-color:rgba(24,240,139,.3)}
    .toast.error{border-color:rgba(255,61,114,.3)}
    @keyframes toast-in{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:none}}
    @keyframes toast-out{from{opacity:1;transform:none}to{opacity:0;transform:translateX(20px)}}

    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.22)}

    @media(max-width:1200px){
      .app{grid-template-columns:76px 1fr}
      .brand-text,.profile-info p,.nav-label,.nav button span,.logout span{display:none}
      .brand,.profile-card{justify-content:center}
      .profile-card{padding:10px;background:transparent;border:none}
      .nav button{justify-content:center;padding:11px}
      .logout{justify-content:center;padding:11px}
      .stats{grid-template-columns:repeat(2,1fr)}
      .content{padding-right:8px}
    }
  </style>
</head>
<body>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  <div class="orb orb-4"></div>
  <div class="toast-container" id="toastContainer"></div>

  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">▦</div>
        <div class="brand-text">
          <h1>QR Attendance</h1>
          <span>Schedule Admin</span>
        </div>
      </div>

      <div class="profile-card">
        <div class="avatar">A<div class="avatar-status"></div></div>
        <div class="profile-info">
          <h2>Attendance Admin <span class="tag">Admin</span></h2>
          <p>admin@attendance.local</p>
          <div class="online-badge"><span class="dot"></span> Online</div>
        </div>
      </div>

      <div class="nav-label">Menu</div>
      <nav class="nav">
        <button class="active">
          <span class="nav-icon">📅</span>
          <span>Schedules</span>
        </button>
        <button type="button">
          <span class="nav-icon">▤</span>
          <span>Classes</span>
        </button>
        <button type="button">
          <span class="nav-icon">📋</span>
          <span>Attendance</span>
        </button>
        <button type="button">
          <span class="nav-icon">🧑‍🎓</span>
          <span>Students</span>
        </button>
        <button type="button">
          <span class="nav-icon">☷</span>
          <span>Settings</span>
        </button>
      </nav>

      <div class="logout-wrap">
        <button class="logout" onclick="showToast('Logged out','⟵','#ff8298')">
          <span class="logout-icon">↪</span>
          <span>Logout</span>
        </button>
      </div>
    </aside>

    <main>
      <header class="topbar">
        <div class="page-title">
          <h2>Schedule Management</h2>
          <p>Manage class schedules and timetables</p>
        </div>
        <div class="top-right">
          <label class="search-bar">
            <span>🔍</span>
            <input id="searchInput" type="search" placeholder="Search schedules..." />
          </label>
          <div class="clock-pill">
            <span class="clock-date">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span>
            ·
            <span id="clockTime">—</span>
          </div>
          <div class="top-avatar">A</div>
        </div>
      </header>

      <div class="content">
        <section class="page active" id="schedules">
          <div class="stats">
            <div class="stat glass">
              <div class="stat-icon blue">📅</div>
              <div class="stat-body">
                <strong>{{ count($schedules) }}</strong>
                <span>Total Schedules</span>
              </div>
            </div>
            <div class="stat glass">
              <div class="stat-icon green">👨‍🏫</div>
              <div class="stat-body">
                <strong>{{ $professors->count() }}</strong>
                <span>Professors</span>
              </div>
            </div>
            <div class="stat glass">
              <div class="stat-icon purple">📚</div>
              <div class="stat-body">
                <strong>{{ count($schedules) }}</strong>
                <span>Subjects</span>
              </div>
            </div>
            <div class="stat glass">
              <div class="stat-icon yellow">🚪</div>
              <div class="stat-body">
                <strong>{{ $schedules->pluck('room')->unique()->count() }}</strong>
                <span>Rooms</span>
              </div>
            </div>
          </div>

          <div class="glass-table glass">
            <div class="section-head">
              <h3>All Schedules</h3>
              <button class="btn primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                <i class="fas fa-plus me-2"></i>Add Schedule
              </button>
            </div>
            <div class="table-wrap">
              <table id="scheduleTable">
                <thead>
                  <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Professor</th>
                    <th>Days</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($schedules as $schedule)
                  <tr>
                    <td><strong>{{ $schedule->subject_code }}</strong></td>
                    <td>{{ $schedule->subject_name }}</td>
                    <td>
                      <span style="display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:6px 12px;font-size:12px;font-weight:700;background:rgba(67,166,255,.12);color:#d8e8ff;border:1px solid rgba(67,166,255,.2);">
                        <i class="fas fa-user"></i>{{ $schedule->professor }}
                      </span>
                    </td>
                    <td>{{ $schedule->days }}</td>
                    <td>
                      @if($schedule->start_time || $schedule->end_time)
                        {{ $schedule->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('g:i A') : '' }}
                        @if($schedule->start_time && $schedule->end_time)-@endif
                        {{ $schedule->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('g:i A') : '' }}
                      @else
                        {{ $schedule->time }}
                      @endif
                    </td>
                    <td><i class="fas fa-door-open me-1"></i>{{ $schedule->room }}</td>
                    <td>
                      <button class="btn slim primary" title="Edit" onclick="editSchedule({{ $schedule->id }})">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn slim danger" title="Delete" onclick="deleteSchedule({{ $schedule->id }})">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="7" style="text-align:center;padding:32px 0;color:var(--muted);">
                      <i class="fas fa-calendar-times fa-2x" style="margin-bottom:14px;display:block;color:rgba(255,255,255,.55);"></i>
                      No schedules found. Add your first schedule!
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>

  <div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header glass" style="border-bottom:1px solid rgba(255,255,255,.12);">
          <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Schedule</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('schedules.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Subject Code</label>
              <input type="text" class="form-control" name="subject_code" placeholder="e.g., CS101" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Subject Name</label>
              <input type="text" class="form-control" name="subject_name" placeholder="e.g., Introduction to Computer Science" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Professor</label>
              <select class="form-select" name="professor_id" required>
                <option value="">Select Professor</option>
                @foreach($professors as $professor)
                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Days</label>
              <input type="text" class="form-control" name="days" placeholder="e.g., Mon, Wed, Fri" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Time</label>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label small">Start Time</label>
                  <input type="time" class="form-control" name="start_time" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small">End Time</label>
                  <input type="time" class="form-control" name="end_time" required>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Room</label>
              <input type="text" class="form-control" name="room" placeholder="e.g., Room 101" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Schedule</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header glass" style="border-bottom:1px solid rgba(255,255,255,.12);">
          <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Schedule</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="editScheduleForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <input type="hidden" id="editScheduleId" name="id">
            <div class="mb-3">
              <label class="form-label">Subject Code</label>
              <input type="text" class="form-control" id="editSubjectCode" name="subject_code" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Subject Name</label>
              <input type="text" class="form-control" id="editSubjectName" name="subject_name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Days</label>
              <input type="text" class="form-control" id="editDays" name="days" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Time</label>
              <div class="row">
                <div class="col-md-6">
                  <label class="form-label small">Start Time</label>
                  <input type="time" class="form-control" id="editStartTime" name="start_time" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small">End Time</label>
                  <input type="time" class="form-control" id="editEndTime" name="end_time" required>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Room</label>
              <input type="text" class="form-control" id="editRoom" name="room" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Schedule</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(135deg,rgba(255,61,114,.9),rgba(255,61,114,.6));color:#fff;">
          <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this schedule? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      const table = document.getElementById('scheduleTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
      for (let i = 0; i < rows.length; i++) {
        const rowText = rows[i].textContent.toLowerCase();
        if (rowText.includes(searchTerm)) {
          rows[i].style.display = '';
        } else {
          rows[i].style.display = 'none';
        }
      }
    });

    function editSchedule(id) {
      fetch(`/admin/schedules/${id}`)
        .then(response => response.json())
        .then(data => {
          document.getElementById('editScheduleId').value = data.id;
          document.getElementById('editSubjectCode').value = data.subject_code;
          document.getElementById('editSubjectName').value = data.subject_name;
          document.getElementById('editDays').value = data.days;
          document.getElementById('editStartTime').value = normalizeTimeForInput(data.start_time || '');
          document.getElementById('editEndTime').value = normalizeTimeForInput(data.end_time || '');
          document.getElementById('editRoom').value = data.room;
          document.getElementById('editScheduleForm').action = `/admin/schedules/${id}`;
          new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
        });
    }

    function normalizeTimeForInput(timeValue) {
      if (!timeValue) {
        return '';
      }
      const parts = timeValue.split(':');
      if (parts.length < 2) {
        return '';
      }
      return `${parts[0].padStart(2, '0')}:${parts[1].padStart(2, '0')}`;
    }

    function deleteSchedule(id) {
      document.getElementById('deleteForm').action = `/admin/schedules/${id}`;
      new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function updateClock(){
      const now = new Date();
      const h = now.getHours().toString().padStart(2,'0');
      const m = now.getMinutes().toString().padStart(2,'0');
      const s = now.getSeconds().toString().padStart(2,'0');
      document.getElementById('clockTime').textContent = `${h}:${m}:${s}`;
    }
    updateClock();
    setInterval(updateClock,1000);

    function showToast(msg, icon='✓', color='#4dffa0') {
      const tc = document.getElementById('toastContainer');
      const t = document.createElement('div');
      t.className = 'toast success';
      t.innerHTML = `<span style="font-size:18px">${icon}</span> <span>${msg}</span>`;
      t.style.borderColor = color + '55';
      tc.appendChild(t);
      setTimeout(()=>t.remove(), 3200);
    }
  </script>
</body>
</html>
