    <style>
    body.theme-light .sidebar {
      background: #fff !important;
      border-right: 1px solid #e5e7eb !important;
      box-shadow: 0 14px 50px rgba(15,23,42,.06) !important;
      background-image: none !important;
      color: #23272f !important;
    }
    body.theme-light .sidebar .nav a,
    body.theme-light .sidebar .nav button {
      color: #23272f !important;
    }
    body.theme-light .sidebar .nav a .nav-icon,
    body.theme-light .sidebar .nav button .nav-icon {
      color: #7c3aed !important;
      background: #ede9fe !important;
      border-color: #c7d2fe !important;
    }
    </style>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'QR Attendance System — Student')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
  <style>
    :root {
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

    /* Ensure dashboard header is visible in all modes */
    .page-title h2, .page-title p {
      color: var(--text) !important;
    }
    
    /* Theme-specific heading colors */
    body.theme-light .page-title h2 {
      color: #0f172a !important;
    }
    body.theme-light .page-title p {
      color: #475569 !important;
    }
    
    body.theme-ash .page-title h2 {
      color: #0f172a !important;
    }
    body.theme-ash .page-title p {
      color: #475569 !important;
    }
    
    body.theme-dark .page-title h2 {
      color: #f0f4ff !important;
    }
    body.theme-dark .page-title p {
      color: #9ba8cc !important;
    }
    
    body.theme-onyx .page-title h2 {
      color: #f4f7ff !important;
    }
    body.theme-onyx .page-title p {
      color: #a5aed4 !important;
    }
    body.theme-light .sidebar {
      background: #fff !important;
      border-right: 1px solid #e5e7eb !important;
      box-shadow: 0 14px 50px rgba(15,23,42,.06) !important;
      background-image: none !important;
    }
    body.theme-light .nav a, body.theme-light .nav button {
      color: #222 !important;
    }
    body.theme-light .nav a .nav-icon, body.theme-light .nav button .nav-icon {
      color: #5b21b6 !important;
      background: #ede9fe !important;
      border-color: #c7d2fe !important;
    }
    body.theme-light .nav a.active, body.theme-light .nav button.active {
      background: linear-gradient(135deg, #5b21b6 80%, #2563eb 100%) !important;
      color: #fff !important;
      box-shadow: 0 8px 24px rgba(80,94,255,.18),inset 0 1px 0 rgba(255,255,255,.18);
    }
    body.theme-light .nav a.active .nav-icon, body.theme-light .nav button.active .nav-icon {
      color: #fff !important;
      background: rgba(91,33,182,0.9) !important;
      border-color: #5b21b6 !important;
    }
    body.theme-light .logout-wrap{border-top-color:#f3f4f6}
    body.theme-light .profile-card{
      background: #ede9fe !important;
      border-color: #c4b5fd !important;
      color: #222 !important;
    }
    body.theme-light .profile-card .avatar,
    body.theme-light .profile-card .tag {
      color: #fff !important;
    }
    body.theme-light .profile-card h2,
    body.theme-light .profile-card p,
    body.theme-light .profile-card .online-badge {
      color: #222 !important;
    }
    body.theme-light .nav-icon {
      color: #222 !important;
      background: #e0e7ff !important;
      border-color: #c7d2fe !important;
    }
    body.theme-light .avatar{background:linear-gradient(145deg,#a78bfa,#7c3aed)}
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
    body.theme-light .pill
    {
      background: #f8fafb !important;
      border-color: #e5e7eb !important;
    }
    
    body.theme-light {
      --bg: #ffffff;
      --panel: #ffffff;
      --panel2: #f3f0ff;
      --glass: #f5f5f5;
      --glass-strong: #ffffff;
      --stroke: #e5e7eb;
      --stroke-soft: #f3f4f6;
      --text: #0f172a;
      --muted: #475569;
      --muted2: #475569;
      --faint: #475569;
      --purple: #7c3aed;
      --pl: #a78bfa;
      --blue: #2563eb;
      --green: #16a34a;
      --amber: #d97706;
      --red: #dc2626;
      --yellow: #ca8a04;
      --cyan: #0891b2;
      --shadow: 0 4px 12px rgba(0,0,0,.08);
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

    body.theme-light .glass span,
    body.theme-light .glass p,
    body.theme-light .glass h1,
    body.theme-light .glass h2,
    body.theme-light .glass h3,
    body.theme-light .glass h4,
    body.theme-light .glass h5,
    body.theme-light .glass h6,
    body.theme-light .glass strong,
    body.theme-light .glass label,
    body.theme-light .glass td,
    body.theme-light .glass th,
    body.theme-light .glass li,
    body.theme-light .glass small,
    body.theme-light .stat-body span
    {
      color: #1f2937 !important;
    }

    body.theme-light .page-title h2 {
      background: none !important;
      -webkit-background-clip: unset !important;
      -webkit-text-fill-color: unset !important;
      color: #7c3aed !important;
    }

    body.theme-light .section-head h3 {
      color: #7c3aed !important;
    }

    /* Light theme: Table elements in attendance and cards */
    body.theme-light .att-table-wrap table tr,
    body.theme-light .att-table-wrap table tbody tr,
    body.theme-light .card table tr,
    body.theme-light .card table tbody tr
    {
      background: #ffffff !important;
      color: #0f172a !important;
    }

    body.theme-light .att-table-wrap table tbody tr:nth-child(even),
    body.theme-light .card table tbody tr:nth-child(even)
    {
      background: #f8fafb !important;
    }

    body.theme-light .att-table-wrap table tbody tr:hover,
    body.theme-light .card table tbody tr:hover
    {
      background: #f1f5f9 !important;
    }

    body.theme-light .att-table-wrap table td,
    body.theme-light .att-table-wrap table th,
    body.theme-light .card table td,
    body.theme-light .card table th
    {
      color: #0f172a !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .att-stats .stat.glass {
      background: #ffffff !important;
      color: #0f172a !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .att-stats .stat.glass strong,
    body.theme-light .att-stats .stat.glass .stat-label,
    body.theme-light .att-stats .stat.glass span
    {
      color: #0f172a !important;
    }

    body.theme-ash{
      --bg:#e2e8f0;
      --glass:rgba(255,255,255,.92);
      --glass-strong:rgba(255,255,255,.98);
      --stroke:rgba(15,23,42,.12);
      --stroke-soft:rgba(15,23,42,.08);
      --text:#0f172a;
      --muted:#475569;
      --faint:#64748b;
      --purple:#6d28d9;
      --blue:#2563eb;
      --green:#16a34a;
      --red:#b91c1c;
      --yellow:#ca8a04;
      --cyan:#0c4a6e;
    }
    body.theme-ash .sidebar{background:rgba(226,232,240,.80);border-right-color:rgba(15,23,42,.12)}
    body.theme-ash .brand{border-bottom-color:rgba(15,23,42,.1)}
    body.theme-ash .nav a, body.theme-ash .nav button{color:#475569}
    body.theme-ash .nav a .nav-icon, body.theme-ash .nav button .nav-icon{background:rgba(15,23,42,.08);border-color:rgba(15,23,42,.1)}
    body.theme-ash .nav a:hover, body.theme-ash .nav button:hover{background:rgba(15,23,42,.1);color:#0f172a}
    body.theme-ash .nav a.active, body.theme-ash .nav button.active{background:linear-gradient(135deg,#6d28d9,.8,#2563eb);color:#fff}
    body.theme-ash .logout-wrap{border-top-color:rgba(15,23,42,.1)}
    body.theme-ash .profile-card{background:rgba(109,40,217,.1);border-color:rgba(109,40,217,.2)}
    body.theme-ash .avatar{background:linear-gradient(145deg,#a78bfa,#6d28d9)}

    /* Ash theme: Table elements in attendance and cards */
    body.theme-ash .att-table-wrap table tr,
    body.theme-ash .att-table-wrap table tbody tr,
    body.theme-ash .card table tr,
    body.theme-ash .card table tbody tr
    {
      background: rgba(255,255,255,.92) !important;
      color: #0f172a !important;
    }

    body.theme-ash .att-table-wrap table tbody tr:nth-child(even),
    body.theme-ash .card table tbody tr:nth-child(even)
    {
      background: rgba(255,255,255,.7) !important;
    }

    body.theme-ash .att-table-wrap table tbody tr:hover,
    body.theme-ash .card table tbody tr:hover
    {
      background: rgba(255,255,255,.8) !important;
    }

    body.theme-ash .att-table-wrap table td,
    body.theme-ash .att-table-wrap table th,
    body.theme-ash .card table td,
    body.theme-ash .card table th
    {
      color: #0f172a !important;
      border-color: rgba(15,23,42,.12) !important;
    }

    body.theme-ash .att-stats .stat.glass {
      background: rgba(255,255,255,.92) !important;
      color: #0f172a !important;
      border-color: rgba(15,23,42,.12) !important;
    }

    body.theme-ash .att-stats .stat.glass strong,
    body.theme-ash .att-stats .stat.glass .stat-label,
    body.theme-ash .att-stats .stat.glass span
    {
      color: #0f172a !important;
    }

    body.theme-dark{
      --bg:#020510;
      --glass:rgba(255,255,255,.08);
      --glass-strong:rgba(255,255,255,.12);
      --stroke:rgba(255,255,255,.14);
      --stroke-soft:rgba(255,255,255,.07);
      --text:#f0f4ff;
      --muted:#9ba8cc;
      --purple:#8b5cff;
      --blue:#43a6ff;
      --green:#18f08b;
      --red:#ff3d72;
      --yellow:#ffc75a;
      --cyan:#00e5ff;
    }
    body.theme-dark .sidebar{background:rgba(2,4,18,.70);border-right-color:rgba(255,255,255,.1)}
    body.theme-dark .brand{border-bottom-color:rgba(255,255,255,.09)}
    body.theme-dark .nav a, body.theme-dark .nav button{color:rgba(234,240,255,.75)}
    body.theme-dark .nav a .nav-icon, body.theme-dark .nav button .nav-icon{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.09)}
    body.theme-dark .nav a:hover, body.theme-dark .nav button:hover{background:rgba(255,255,255,.08);color:var(--text)}
    body.theme-dark .nav a.active, body.theme-dark .nav button.active{background:linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));color:#fff}
    body.theme-dark .logout-wrap{border-top-color:rgba(255,255,255,.08)}
    body.theme-dark .profile-card{background:rgba(139,92,255,.12);border-color:rgba(139,92,255,.25)}
    body.theme-dark .avatar{background:linear-gradient(145deg,#9a77ff,#4715d1)}

    /* Dark theme: Table elements in attendance and cards */
    body.theme-dark .att-table-wrap table tr,
    body.theme-dark .att-table-wrap table tbody tr,
    body.theme-dark .card table tr,
    body.theme-dark .card table tbody tr
    {
      background: rgba(2,4,18,.58) !important;
      color: #f0f4ff !important;
    }

    body.theme-dark .att-table-wrap table tbody tr:nth-child(even),
    body.theme-dark .card table tbody tr:nth-child(even)
    {
      background: rgba(2,4,18,.8) !important;
    }

    body.theme-dark .att-table-wrap table tbody tr:hover,
    body.theme-dark .card table tbody tr:hover
    {
      background: rgba(2,4,18,.7) !important;
    }

    body.theme-dark .att-table-wrap table td,
    body.theme-dark .att-table-wrap table th,
    body.theme-dark .card table td,
    body.theme-dark .card table th
    {
      color: #f0f4ff !important;
      border-color: rgba(255,255,255,.14) !important;
    }

    body.theme-dark .att-stats .stat.glass {
      background: rgba(255,255,255,.08) !important;
      color: #f0f4ff !important;
      border-color: rgba(255,255,255,.14) !important;
    }

    body.theme-dark .att-stats .stat.glass strong,
    body.theme-dark .att-stats .stat.glass .stat-label,
    body.theme-dark .att-stats .stat.glass span
    {
      color: #f0f4ff !important;
    }

    body.theme-onyx{
      --bg:#070a16;
      --glass:rgba(255,255,255,.07);
      --glass-strong:rgba(255,255,255,.1);
      --stroke:rgba(255,255,255,.12);
      --stroke-soft:rgba(255,255,255,.06);
      --text:#f4f7ff;
      --muted:#a5aed4;
      --purple:#7c3aed;
      --blue:#60a5fa;
      --green:#22c55e;
      --red:#f472b6;
      --yellow:#facc15;
      --cyan:#22d3ee;
    }
    body.theme-onyx .sidebar{background:rgba(7,10,22,.75);border-right-color:rgba(255,255,255,.1)}
    body.theme-onyx .brand{border-bottom-color:rgba(255,255,255,.08)}
    body.theme-onyx .nav a, body.theme-onyx .nav button{color:rgba(244,247,255,.8)}
    body.theme-onyx .nav a .nav-icon, body.theme-onyx .nav button .nav-icon{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.08)}
    body.theme-onyx .nav a:hover, body.theme-onyx .nav button:hover{background:rgba(255,255,255,.08);color:var(--text)}
    body.theme-onyx .nav a.active, body.theme-onyx .nav button.active{background:linear-gradient(135deg,rgba(124,58,237,.8),rgba(96,165,250,.5));color:#fff}
    body.theme-onyx .logout-wrap{border-top-color:rgba(255,255,255,.08)}
    body.theme-onyx .profile-card{background:rgba(124,58,237,.1);border-color:rgba(124,58,237,.2)}
    body.theme-onyx .avatar{background:linear-gradient(145deg,#a78bfa,#7c3aed)}

    /* Onyx theme: Table elements in attendance and cards */
    body.theme-onyx .att-table-wrap table tr,
    body.theme-onyx .att-table-wrap table tbody tr,
    body.theme-onyx .card table tr,
    body.theme-onyx .card table tbody tr
    {
      background: rgba(7,10,22,.58) !important;
      color: #f4f7ff !important;
    }

    body.theme-onyx .att-table-wrap table tbody tr:nth-child(even),
    body.theme-onyx .card table tbody tr:nth-child(even)
    {
      background: rgba(7,10,22,.75) !important;
    }

    body.theme-onyx .att-table-wrap table tbody tr:hover,
    body.theme-onyx .card table tbody tr:hover
    {
      background: rgba(7,10,22,.65) !important;
    }

    body.theme-onyx .att-table-wrap table td,
    body.theme-onyx .att-table-wrap table th,
    body.theme-onyx .card table td,
    body.theme-onyx .card table th
    {
      color: #f4f7ff !important;
      border-color: rgba(255,255,255,.12) !important;
    }

    body.theme-onyx .att-stats .stat.glass {
      background: rgba(255,255,255,.07) !important;
      color: #f4f7ff !important;
      border-color: rgba(255,255,255,.12) !important;
    }

    body.theme-onyx .att-stats .stat.glass strong,
    body.theme-onyx .att-stats .stat.glass .stat-label,
    body.theme-onyx .att-stats .stat.glass span
    {
      color: #f4f7ff !important;
    }

    /* Light theme: Class card elements */
    body.theme-light .class-card-divider {
      background: rgba(15,23,42,.09) !important;
    }

    body.theme-light .classmate-item {
      background: rgba(15,23,42,.04) !important;
      border-color: rgba(15,23,42,.08) !important;
    }

    body.theme-light .classmate-item:hover {
      background: rgba(15,23,42,.08) !important;
      border-color: rgba(15,23,42,.15) !important;
    }

    /* Ash theme: Class card elements */
    body.theme-ash .class-card-divider {
      background: rgba(15,23,42,.1) !important;
    }

    body.theme-ash .classmate-item {
      background: rgba(15,23,42,.05) !important;
      border-color: rgba(15,23,42,.1) !important;
    }

    body.theme-ash .classmate-item:hover {
      background: rgba(15,23,42,.1) !important;
      border-color: rgba(15,23,42,.2) !important;
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
    
    /* Theme-specific body backgrounds */
    body.theme-light{
      background: #f9fafb !important;
    }
    
    body.theme-ash{
      background:linear-gradient(135deg,#f8fafc 0%,#cbd5e1 100%) !important;
    }
    
    body.theme-dark{
      background:radial-gradient(circle at top left, rgba(67,166,255,.16), transparent 22%), radial-gradient(circle at top right, rgba(139,92,255,.12), transparent 22%), linear-gradient(180deg, #020510 0%, #020511 100%) !important;
    }
    
    body.theme-onyx{
      background:radial-gradient(circle at top left, rgba(79,70,229,.14), transparent 20%), linear-gradient(180deg, #080a14 0%, #0d1527 100%) !important;
    }
    body::before{
      content:"";position:fixed;inset:0;pointer-events:none;
      background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size:60px 60px;
      mask-image:radial-gradient(ellipse at 50% 0%, black 30%, transparent 80%);
      z-index:0;
    }

    /* Orbs */
    .orb{position:fixed;border-radius:50%;filter:blur(70px);pointer-events:none;z-index:0;animation:orb-float 18s ease-in-out infinite}
    .orb-1{width:520px;height:520px;background:radial-gradient(circle,rgba(84,56,216,.32),transparent 70%);left:-80px;top:-80px;opacity:.7}
    .orb-2{width:400px;height:400px;background:radial-gradient(circle,rgba(20,85,199,.22),transparent 70%);right:-60px;top:100px;animation-delay:-6s;opacity:.6}
    .orb-3{width:360px;height:360px;background:radial-gradient(circle,rgba(138,23,72,.2),transparent 70%);right:15%;bottom:-40px;animation-delay:-12s;opacity:.5}
    .orb-4{width:280px;height:280px;background:radial-gradient(circle,rgba(0,229,255,.08),transparent 70%);left:40%;top:30%;animation-delay:-3s;opacity:.4}
    @keyframes orb-float{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(30px,-40px) scale(1.06)}66%{transform:translate(-20px,25px) scale(.96)}}

    /* Glass */
    .glass{
      border:1px solid var(--stroke);
      background:rgba(255,255,255,.18);
      backdrop-filter:var(--blur);-webkit-backdrop-filter:var(--blur);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.32),inset 0 -1px 0 rgba(0,0,0,.18),var(--shadow);
      position:relative;overflow:hidden;
    }
    .glass::after{
      content:"";position:absolute;top:0;left:0;right:0;height:1px;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.55) 50%,transparent);
      pointer-events:none;
    }
    .stat.glass::after{display:none}

    /* ═══ LAYOUT ═══ */
    .app{display:grid;grid-template-columns:260px 1fr;height:100vh;overflow:hidden;position:relative;z-index:1}

    /* ═══ SIDEBAR ═══ */
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
      transition:background .2s;
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
      color: #ffffff; /* Adds white color to the avatar letter */
    }
    .avatar-status{position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:var(--green);border:2px solid rgba(2,4,18,.8);box-shadow:0 0 8px rgba(24,240,139,.6)}
    .profile-info h2 {font-size: 14px;font-weight: 700; display: flex;gap: 6px;align-items: center;color: #ffffff;/* Adds white color to your name */}
    .tag{font-size:10px;padding:3px 7px;border-radius:999px;background:rgba(139,92,255,.3);color:#efeaff;border:1px solid rgba(139,92,255,.4)}
    .profile-info p{margin-top:3px;color:var(--muted);font-size:11.5px}
    .online-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--green);margin-top:2px;font-weight:600}
    .dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 6px rgba(24,240,139,.8);animation:pulse-dot 2s infinite}
    @keyframes pulse-dot{0%,100%{opacity:1;box-shadow:0 0 6px rgba(24,240,139,.8)}50%{opacity:.6;box-shadow:0 0 14px rgba(24,240,139,.4)}}

    .nav-label{margin:2px 8px 0;color:var(--faint);font-size:10px;letter-spacing:.18em;text-transform:uppercase;font-weight:700;flex-shrink:0}
    .nav{display:grid;gap:2px;flex-shrink:0}
    .nav a, .nav button{
      border:0;color:#fff;background:transparent;
      padding:8px 10px;border-radius:13px;display:flex;align-items:center;gap:10px;
      font-weight:600;cursor:pointer;transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;font-size:13.5px;font-family:var(--font);width:100%;text-decoration:none;
    }
    body.theme-light .nav a, body.theme-light .nav button {
      color: #475569;
    }
    .nav-icon{
      width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-size:14px;
      background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.09);flex-shrink:0;transition:.2s ease;
    }
    .nav a:hover, .nav button:hover{background:rgba(255,255,255,.08);color:var(--text);transform:translateX(3px)}
    .nav a:hover .nav-icon, .nav button:hover .nav-icon{background:rgba(255,255,255,.12)}
    body.theme-light .nav a.active:hover, body.theme-light .nav button.active:hover{background:linear-gradient(135deg,#7c3aed,.8,#2563eb);color:#fff;transform:none}
    body.theme-ash .nav a.active:hover, body.theme-ash .nav button.active:hover{background:linear-gradient(135deg,#6d28d9,.8,#2563eb);color:#fff;transform:none}
    body.theme-dark .nav a.active:hover, body.theme-dark .nav button.active:hover{background:linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5));color:#fff;transform:none}
    body.theme-onyx .nav a.active:hover, body.theme-onyx .nav button.active:hover{background:linear-gradient(135deg,rgba(124,58,237,.8),rgba(96,165,250,.5));color:#fff;transform:none}
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

    /* ═══ MAIN ═══ */
    main{padding:18px 24px 18px;height:100vh;display:flex;flex-direction:column;overflow:hidden}
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:14px;flex-shrink:0}
    .page-title h2{
      font-size:26px;font-weight:800;letter-spacing:-.06em;line-height:1;
      color:#0f172a;
      background:none;
      -webkit-background-clip:unset;
      -webkit-text-fill-color:unset;
    }
    .page-title p{margin-top:4px;color:#334155;font-size:13px;font-weight:500}
    .top-right{display:flex;align-items:center;gap:12px}
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
      font-weight:900;cursor:pointer;position:relative;transition:.2s ease;font-size:15px;color:#fff;
    }
    .top-avatar:hover{transform:scale(1.06)}
    .top-avatar::after{content:"";position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:var(--green);border:2px solid rgba(2,4,18,.9)}

    /* ═══ CONTENT ═══ */
    .content{flex:1;overflow-y:auto;overflow-x:hidden;min-height:0;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent}
    .page{display:block}
    @keyframes fadein{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
    .page{animation:fadein .28s cubic-bezier(.4,0,.2,1)}

    /* ═══ STAT CARDS ═══ */
    .stats{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:14px;flex-shrink:0;padding-top:6px}
    .stat{
      border-radius:22px;padding:16px;display:flex;gap:12px;align-items:flex-start;
      position:relative;z-index:1;
      border:none;
      background:transparent;
      backdrop-filter:none;-webkit-backdrop-filter:none;
      box-shadow:none;
      transition:border-color .3s ease,background .3s ease,backdrop-filter .3s ease,box-shadow .3s ease,transform .25s ease;
    }
    .stat:hover{
      transform:translateY(-3px);
      border-color:rgba(255,255,255,.22);
      background:linear-gradient(135deg,rgba(255,255,255,.1),rgba(255,255,255,.03) 40%,rgba(255,255,255,.07));
      backdrop-filter:var(--blur);-webkit-backdrop-filter:var(--blur);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.2),0 24px 60px rgba(0,0,0,.4);
      z-index:10;
    }
    .stat.glass{border:none}
    .stat.glass:hover{border-color:transparent}
    .stat-icon{
      width:40px;height:40px;border-radius:13px;display:grid;place-items:center;font-size:18px;flex-shrink:0;
    }
    .stat-icon.blue{background:rgba(67,166,255,.18);border:1px solid rgba(67,166,255,.25)}
    .stat-icon.green{background:rgba(24,240,139,.15);border:1px solid rgba(24,240,139,.2)}
    .stat-icon.yellow{background:rgba(255,199,90,.15);border:1px solid rgba(255,199,90,.2)}
    .stat-icon.purple{background:rgba(139,92,255,.18);border:1px solid rgba(139,92,255,.22)}
    .stat-icon.red{background:rgba(255,61,114,.15);border:1px solid rgba(255,61,114,.2)}
    .stat-body strong{font-size:28px;font-weight:900;letter-spacing:-.05em;display:block;line-height:1}
    .stat-body .stat-label{color:#d0d8f0;font-size:13px;font-weight:600;margin-top:4px;display:block}
    .stat-body a{color:rgba(139,92,255,.9);font-size:12px;font-weight:700;text-decoration:none;display:block;margin-top:6px;cursor:pointer}
    .stat-body a:hover{color:#b9c4ff}

    /* ═══ DASHBOARD GRID ═══ */
    .dash-grid{
      display:grid;
      grid-template-columns:1fr 1fr 340px;
      gap:14px;
      flex:1;
      min-height:0;
      overflow:hidden;
    }
    .dash-col{display:flex;flex-direction:column;gap:14px;min-height:0;overflow:hidden}
    .card{border-radius:22px;padding:18px}
    .card.stretch{flex:1;min-height:0;display:flex;flex-direction:column;overflow:hidden}
    .section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-shrink:0}
    .section-head h3{font-size:15px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px;color:var(--text)}
    .section-head a{color:rgba(139,92,255,.9);text-decoration:none;font-weight:700;font-size:12px;letter-spacing:.02em;cursor:pointer}
    .section-head a:hover{color:#b9c4ff}

    /* ═══ CLASS LIST ═══ */
    .class-row{
      display:flex;align-items:center;justify-content:space-between;
      padding:13px 14px;border-radius:16px;margin-bottom:8px;
      border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.055);
      transition:.2s ease;cursor:default;
    }
    .class-row:hover{background:rgba(255,255,255,.09);border-color:rgba(255,255,255,.18);transform:translateX(3px)}
    .class-row:last-child{margin-bottom:0}
    .class-row-left{}
    .class-row-name{font-size:14px;font-weight:700;color:var(--text)}
    .class-row-code{font-size:11.5px;color:var(--muted);font-family:var(--mono);margin-top:2px}
    .class-row-prof{font-size:12px;color:var(--muted);font-weight:500;text-align:right}
    .class-row-prof strong{display:block;font-size:12.5px;font-weight:700;color:#d0d8f0}

    /* ═══ RECENT ATTENDANCE ═══ */
    .att-row{
      display:grid;grid-template-columns:1fr auto auto;gap:10px;align-items:center;
      padding:12px 14px;border-radius:14px;margin-bottom:8px;
      border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);
      transition:.2s ease;
    }
    .att-row:hover{background:rgba(255,255,255,.07)}
    .att-row:last-child{margin-bottom:0}
    .att-class{font-size:13.5px;font-weight:700;color:var(--text)}
    .att-date{font-size:11.5px;color:var(--muted);margin-top:2px;font-family:var(--mono)}
    .att-time{font-size:12px;color:var(--faint);font-family:var(--mono);white-space:nowrap}
    .att-mins{font-size:12px;color:var(--muted);text-align:right}

    /* ═══ QR PANEL ═══ */
    .qr-panel{
      display:flex;flex-direction:column;gap:14px;min-height:0;
    }
    .qr-container{
      border-radius:var(--radius-lg);
      padding:22px;
      display:flex;flex-direction:column;align-items:center;
      flex:1;min-height:0;
    }
    .qr-label{
      font-size:10.5px;font-weight:700;color:var(--muted);letter-spacing:.18em;
      text-transform:uppercase;margin-bottom:16px;flex-shrink:0;
    }
    .qr-frame{
      width:100%;aspect-ratio:1;
      background:#fff;border-radius:20px;
      display:grid;place-items:center;
      overflow:hidden;
      box-shadow:0 0 0 6px rgba(255,255,255,.08), 0 20px 60px rgba(0,0,0,.5);
      flex-shrink:0;
      position:relative;
      max-width:260px;
    }
    .qr-frame canvas{width:100%!important;height:100%!important;display:block}
    .qr-frame::before,.qr-frame::after{
      content:"";position:absolute;width:26px;height:26px;
      border-color:rgba(139,92,255,.8);border-style:solid;
      pointer-events:none;z-index:2;
    }
    .qr-frame::before{top:8px;left:8px;border-width:3px 0 0 3px;border-radius:5px 0 0 0}
    .qr-frame::after{bottom:8px;right:8px;border-width:0 3px 3px 0;border-radius:0 0 5px 0}

    .qr-student-name{
      font-size:16px;font-weight:800;letter-spacing:-.03em;margin-top:14px;
      color:var(--text);text-align:center;flex-shrink:0;
    }
    .qr-student-id{
      font-size:12px;color:var(--muted);font-family:var(--mono);
      margin-top:4px;text-align:center;flex-shrink:0;
    }
    .qr-hint{
      font-size:11.5px;color:var(--faint);text-align:center;margin-top:3px;flex-shrink:0;
    }
    .qr-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;width:100%;margin-top:14px;flex-shrink:0}
    .qr-status{
      display:flex;align-items:center;justify-content:center;gap:6px;
      font-size:11px;font-weight:700;color:var(--green);margin-top:10px;
      letter-spacing:.1em;text-transform:uppercase;flex-shrink:0;
    }

    /* Quick stats card */
    .quick-stats-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px}
    .qs{
      border-radius:16px;padding:14px;text-align:center;
      border:1px solid rgba(255,255,255,.10);background:rgba(255,255,255,.055);
      transition:.2s ease;
    }
    .qs:hover{background:rgba(255,255,255,.09)}
    .qs-val{font-size:26px;font-weight:900;letter-spacing:-.05em;line-height:1;display:block}
    .qs-val.green{color:#4dffa0}
    .qs-val.yellow{color:#ffd584}
    .qs-val.red{color:#ff7f96}
    .qs-val.blue{color:#80cbff}
    .qs-val.purple{color:#d8cdff}
    .qs-lbl{font-size:12px;color:var(--muted);font-weight:600;margin-top:4px;display:block}

    /* Quick actions */
    .quick-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
    .quick{
      border-radius:16px;padding:12px;display:flex;align-items:center;gap:9px;
      cursor:pointer;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);transition:.25s ease;
    }
    .quick:hover{background:rgba(255,255,255,.1);transform:translateY(-2px);border-color:rgba(255,255,255,.22)}
    .quick strong{display:block;font-size:13px;font-weight:700;color:var(--text)}
    .quick span{display:block;color:var(--muted);font-size:11px;margin-top:1px}

    /* Pills */
    .pill{
      display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:6px 12px;
      font-size:12px;font-weight:700;border:1px solid rgba(255,255,255,.10);white-space:nowrap;
    }
    .pill.green{color:#4dffa0;background:rgba(24,240,139,.11);border-color:rgba(24,240,139,.2)}
    .pill.red{color:#ff7f96;background:rgba(255,61,114,.12);border-color:rgba(255,61,114,.22)}
    .pill.yellow{color:#ffd584;background:rgba(255,199,90,.12);border-color:rgba(255,199,90,.22)}
    .pill.blue{color:#80cbff;background:rgba(67,166,255,.12);border-color:rgba(67,166,255,.22)}
    .pill.purple{color:#d8cdff;background:rgba(139,92,255,.15);border-color:rgba(139,92,255,.25)}
    .pill.cyan{color:#5fffff;background:rgba(0,229,255,.1);border-color:rgba(0,229,255,.2)}
    .pill.excused{color:#d8cdff;background:rgba(139,92,255,.15);border-color:rgba(139,92,255,.25)}

    /* Buttons */
    .btn{
      border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:#fff;
      border-radius:13px;padding:10px 16px;font-weight:700;cursor:pointer;transition:.2s ease;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.14);font-size:13px;font-family:var(--font);
    }
    .btn:hover{transform:translateY(-2px);background:rgba(255,255,255,.13);border-color:rgba(255,255,255,.24)}
    .btn.primary{
      background:linear-gradient(135deg,rgba(139,92,255,.96),rgba(67,166,255,.6));border-color:transparent;
      box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 6px 18px rgba(80,94,255,.22);
    }
    .btn.primary:hover{box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 10px 28px rgba(80,94,255,.38)}
    .btn.slim{padding:7px 12px;font-size:12px;border-radius:10px}
    .btn-pill{border-radius:999px !important;padding:10px 20px;display:flex;align-items:center;justify-content:center}

    /* ─── MY CLASSES PAGE ─── */
    .classes-layout{display:grid;grid-template-columns:1fr 340px;gap:16px;align-items:start}
    .class-cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;margin-bottom:12px}
    .class-card{
      border-radius:16px;padding:13px;margin-bottom:10px;
      transition:.25s ease;display:flex;flex-direction:column;justify-content:space-between;
      border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);
      position:relative;overflow:hidden;
    }
    .class-card::before{
      content:"";position:absolute;top:-40%;right:-30%;width:80%;height:80%;
      border-radius:50%;background:radial-gradient(circle,rgba(139,92,255,.12),transparent 70%);
      pointer-events:none;
    }
    .class-card:hover{background:rgba(255,255,255,.10);border-color:rgba(255,255,255,.24);transform:translateY(-4px);box-shadow:0 20px 50px rgba(0,0,0,.4)}
    .class-card-top{display:flex;justify-content:space-between;align-items:flex-start}
    .class-card-icon{width:34px;height:34px;border-radius:11px;display:grid;place-items:center;font-size:16px;background:rgba(139,92,255,.18);border:1px solid rgba(139,92,255,.25);flex-shrink:0}
    .class-card-name{font-size:13px;font-weight:800;letter-spacing:-.03em;color:var(--text);line-height:1.25;margin-top:8px;padding-top:8px}
    .class-card-code{font-size:10px;color:var(--muted);font-family:var(--mono);margin-top:3px}
    .class-card-meta{display:flex;gap:12px;flex-wrap:wrap;margin-top:6px}
    .class-meta-item{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--muted)}
    .class-meta-item strong{color:#d0d8f0;font-weight:600}

    /* ─── QR SIDEBAR (My Classes) ─── */
    .qr-sidebar{
      border-radius:var(--radius-lg);padding:22px;
      display:flex;flex-direction:column;align-items:center;
      position:sticky;top:0;
    }
    .qr-sidebar .qr-frame{max-width:220px}
    .quick-stats-title{font-size:10.5px;font-weight:700;color:var(--muted);letter-spacing:.18em;text-transform:uppercase;margin-bottom:12px;display:block;width:100%}

    /* ─── ATTENDANCE PAGE ─── */
    .att-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;padding-top:6px}
    .att-table-wrap{overflow-x:auto;border-radius:var(--radius-md);scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.12) transparent}
    table{width:100%;border-collapse:separate;border-spacing:0;background:rgba(255,255,255,.02)}
    th,td{padding:14px 16px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07);vertical-align:middle}
    th{
      background:rgba(255,255,255,.055);color:var(--faint);font-size:11px;letter-spacing:.12em;
      text-transform:uppercase;font-weight:700;position:sticky;top:0;backdrop-filter:blur(8px);
    }
    th:first-child{border-radius:var(--radius-md) 0 0 0}
    th:last-child{border-radius:0 var(--radius-md) 0 0}
    tbody tr{transition:.15s ease}
    tbody tr:hover{background:rgba(255,255,255,.05)}
    td{color:#e8eeff;font-size:14px}
    tr:last-child td{border-bottom:0}
    .muted{color:var(--muted);font-weight:400}

    /* Empty state */
    .empty-state{text-align:center;padding:40px 0;color:var(--faint);font-size:14px}
    .empty-state span{display:block;font-size:32px;margin-bottom:10px}

    /* Report btn */
    .report-btn{
      width:100%;height:42px;border:0;border-radius:13px;color:white;font-weight:800;
      cursor:pointer;font-size:13px;letter-spacing:.02em;font-family:var(--font);
      background:linear-gradient(135deg,rgba(139,92,255,.85),rgba(67,166,255,.45));
      box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 8px 24px rgba(80,94,255,.2);transition:.2s ease;
    }
    .report-btn:hover{transform:translateY(-2px);box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 14px 32px rgba(80,94,255,.35)}

    /* Progress bar */
    .prog-bar{height:6px;border-radius:99px;background:rgba(255,255,255,.1);overflow:hidden;margin:10px 0 14px}
    .prog-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--green),var(--blue));box-shadow:0 0 10px rgba(24,240,139,.5);transition:width .5s ease}

    /* Toast */
    .toast-container{position:fixed;bottom:24px;right:24px;display:flex;flex-direction:column;gap:10px;z-index:1000}
    .toast{
      padding:13px 18px;border-radius:16px;backdrop-filter:blur(24px);
      background:rgba(20,24,54,.92);border:1px solid rgba(255,255,255,.15);
      box-shadow:0 16px 40px rgba(0,0,0,.35);display:flex;align-items:center;gap:10px;
      font-size:13.5px;font-weight:600;animation:toast-in .3s ease,toast-out .3s ease 2.7s forwards;max-width:320px;
    }
    @keyframes toast-in{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:none}}
    @keyframes toast-out{from{opacity:1;transform:none}to{opacity:0;transform:translateX(20px)}}

    /* QR Modal */
    .qr-modal{position:fixed;inset:0;display:none;z-index:2000;align-items:center;justify-content:center}
    .qr-modal.active{display:flex}
    .qr-modal-overlay{position:absolute;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)}
    .qr-modal-content{
      position:relative;z-index:10;border-radius:var(--radius-lg);padding:32px;max-width:420px;width:90vw;max-height:90vh;overflow-y:auto;
      border:1px solid var(--stroke);box-shadow:0 64px 128px rgba(0,0,0,.6);
    }
    .qr-modal-close{
      position:absolute;top:16px;right:16px;width:40px;height:40px;border-radius:50%;
      background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:#fff;
      font-size:20px;font-weight:700;cursor:pointer;transition:.2s ease;display:grid;place-items:center;
    }
    .qr-modal-close:hover{background:rgba(255,255,255,.15);transform:scale(1.08)}
    .qr-modal-body{padding-top:20px}
    .qr-modal-frame{
      width:300px;height:300px;background:#fff;border-radius:20px;display:grid;place-items:center;
      margin:0 auto;overflow:hidden;box-shadow:0 0 0 6px rgba(255,255,255,.08),0 20px 60px rgba(0,0,0,.5);
      position:relative;
    }
    .qr-modal-frame canvas{width:100%!important;height:100%!important;display:block}
    .qr-modal-frame::before,.qr-modal-frame::after{
      content:"";position:absolute;width:26px;height:26px;
      border-color:rgba(139,92,255,.8);border-style:solid;
      pointer-events:none;z-index:2;
    }
    .qr-modal-frame::before{top:8px;left:8px;border-width:3px 0 0 3px;border-radius:5px 0 0 0}
    .qr-modal-frame::after{bottom:8px;right:8px;border-width:0 3px 3px 0;border-radius:0 0 5px 0}

    /* Scrollbar */
    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.22)}

    @media(max-width:1200px){
      .app{grid-template-columns:76px 1fr}
      .brand-text,.profile-info,.nav-label,.nav button span,.logout span{display:none}
      .brand,.profile-card{justify-content:center}
      .profile-card{padding:10px;background:transparent;border:none}
      .nav button{justify-content:center;padding:11px}
      .logout{justify-content:center;padding:11px}
      .stats{grid-template-columns:repeat(3,1fr)}
      .att-stats{grid-template-columns:repeat(3,1fr)}
      .dash-grid{grid-template-columns:1fr;overflow-y:auto}
      .classes-layout{grid-template-columns:1fr}
    }
  </style>
</head>
<body @if((auth()->user()->theme ?? 'light') === 'light') class="theme-light" @elseif((auth()->user()->theme ?? '') === 'ash') class="theme-ash" @elseif((auth()->user()->theme ?? '') === 'dark') class="theme-dark" @elseif((auth()->user()->theme ?? '') === 'onyx') class="theme-onyx" @endif>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  <div class="orb orb-4"></div>
  <div class="toast-container" id="toastContainer"></div>

  <div class="app">
    <aside class="sidebar @if((auth()->user()->theme ?? 'light') === 'light') bg-white border-r border-gray-200 text-gray-900 @endif">
      <div class="brand">
        <div class="logo">▦</div>
        <div class="brand-text">
          <h1>QR Attendance</h1>
          <span>Student</span>
        </div>
      </div>

      <a href="{{ route('student.settings') }}" class="profile-card" style="text-decoration:none;cursor:pointer;transition:.2s ease;display:flex;align-items:center;gap:10px;">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
          <div class="avatar-status"></div>
        </div>
        <div class="profile-info">
          <h2>{{ auth()->user()->name }} <span class="tag">Student</span></h2>
          <p>{{ auth()->user()->email }}</p>
          <div class="online-badge"><span class="dot"></span> Online</div>
        </div>
      </a>

      <div class="nav-label">Menu</div>
      <nav class="nav">
        <a href="{{ route('student.dashboard') }}" class="nav-btn {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
          <span class="nav-icon @if((auth()->user()->theme ?? 'light') === 'light') text-indigo-700 bg-indigo-100 border-indigo-200 @endif">⌂</span>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('student.classes') }}" class="nav-btn {{ request()->routeIs('student.classes') ? 'active' : '' }}">
          <span class="nav-icon @if((auth()->user()->theme ?? 'light') === 'light') text-indigo-700 bg-indigo-100 border-indigo-200 @endif">▤</span>
          <span>My Classes</span>
        </a>
        <a href="{{ route('student.attendance') }}" class="nav-btn {{ request()->routeIs('student.attendance') ? 'active' : '' }}">
          <span class="nav-icon @if((auth()->user()->theme ?? 'light') === 'light') text-indigo-700 bg-indigo-100 border-indigo-200 @endif">📋</span>
          <span>Attendance</span>
        </a>
        <a href="{{ route('student.settings') }}" class="nav-btn {{ request()->routeIs('student.settings') ? 'active' : '' }}">
          <span class="nav-icon @if((auth()->user()->theme ?? 'light') === 'light') text-indigo-700 bg-indigo-100 border-indigo-200 @endif">⚙️</span>
          <span>Settings</span>
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

    <main>
      <header class="topbar">
        <div class="page-title">
          <h2 id="pageTitle">@yield('title', 'Dashboard')</h2>
          <p id="pageSubtitle">@yield('subtitle', 'Welcome')</p>
        </div>
        <div class="top-right">
          <div class="clock-pill">
            📅 <span class="clock-date" id="clockDate">{{ now()->format('M d, Y') }}</span>
            &nbsp;·&nbsp;
            <span id="clockTime" style="font-family:var(--mono);font-size:12px">--:--:--</span>
          </div>
          <div class="top-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
        </div>
      </header>

      <div class="content">
        @yield('content')
      </div></main>
  </div>

  <script>
    // ─── Live Clock (12-hour format with AM/PM) ───
    function updateClock(){
      const now = new Date();
      let hours = now.getHours();
      const ampm = hours >= 12 ? 'PM' : 'AM';
      
      hours = hours % 12;
      hours = hours ? hours : 12; // Converts 0 hour to 12
      
      const h = hours.toString().padStart(2, '0');
      const m = now.getMinutes().toString().padStart(2, '0');
      const s = now.getSeconds().toString().padStart(2, '0');
      
      document.getElementById('clockTime').textContent = `${h}:${m}:${s} ${ampm}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ─── Toast ───
    function showToast(msg, icon='✓', color='#4dffa0'){
      const tc = document.getElementById('toastContainer');
      const t = document.createElement('div');
      t.className = 'toast';
      t.innerHTML = `<span style="font-size:18px">${icon}</span> <span>${msg}</span>`;
      t.style.borderColor = color+'55';
      tc.appendChild(t);
      setTimeout(()=>t.remove(), 3200);
    }

    // ─── QR Code Generation ───
    function generateQR(canvasId, qrData) {
      const canvas = document.getElementById(canvasId);
      if (!canvas) {
        console.warn('Canvas not found:', canvasId);
        return;
      }
      QRCode.toCanvas(canvas, qrData, {
        width: 260,
        margin: 1,
        color: { dark: '#000000', light: '#ffffff' }
      }, function(err){
        if(err) {
          console.error('QR generation error:', err);
          const ctx = canvas.getContext('2d');
          canvas.width = 260; canvas.height = 260;
          ctx.fillStyle = '#fff';
          ctx.fillRect(0,0,260,260);
          ctx.fillStyle = '#000';
          ctx.font = 'bold 14px monospace';
          ctx.textAlign = 'center';
          ctx.fillText('QR CODE', 130, 130);
        }
      });
    }

    setTimeout(()=>showToast('Welcome back!','👋','#b9c4ff'), 600);

    // Theme switching via localStorage (matches admin/professor behavior)
    (function() {
      const themeKey = 'qr_attendance_theme';
      const themeNames = ['light','ash','dark','onyx'];
      const defaultTheme = 'dark';
      const current = themeNames.includes(localStorage.getItem(themeKey)) ? localStorage.getItem(themeKey) : defaultTheme;
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + current);
    })();
  </script>
</body>
</html>