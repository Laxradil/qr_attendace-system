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
      --shadow:0 10px 20px rgba(0,0,0,.08);
      --blur:blur(24px) saturate(180%);
      --radius-lg:28px;
      --radius-md:18px;
      --radius-sm:12px;
      --font:'DM Sans',system-ui,sans-serif;
      --mono:'Space Mono',monospace;
    }

    body.theme-light{
      --bg:#ffffff;
      --glass:#f5f5f5;
      --glass-strong:#ffffff;
      --stroke:#e5e7eb;
      --stroke-soft:#f3f4f6;
      --text:#0f172a;
      --muted:#475569;
      --faint:#475569;
      --purple:#7c3aed;
      --blue:#2563eb;
      --green:#16a34a;
      --red:#dc2626;
      --yellow:#ca8a04;
      --cyan:#0891b2;
      background:#f9fafb;
    }
    body.theme-light .sidebar{background:#ffffff;border-right-color:#e5e7eb}
    body.theme-light .brand{border-bottom-color:#f3f4f6}
    body.theme-light .nav a, body.theme-light .nav button{color:#475569}
    body.theme-light .nav a .nav-icon, body.theme-light .nav button .nav-icon{background:#f3f4f6;border-color:#e5e7eb}
    body.theme-light .nav a:hover, body.theme-light .nav button:hover{background:#f0f1f3;color:#0f172a}
    body.theme-light .nav a.active, body.theme-light .nav button.active{background:linear-gradient(135deg,#7c3aed,.8,#2563eb);color:#fff}
    body.theme-light .logout-wrap{border-top-color:#f3f4f6}
    body.theme-light .profile-card{background:#f3f0ff;border-color:#ede9fe}
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
      background: rgba(255,255,255,.92) !important;
      border: 1px solid rgba(226,232,240,.9) !important;
      color: #0f172a !important;
      box-shadow: 0 8px 24px rgba(15,23,42,.04) !important;
    }
    body.theme-light .theme-option.selected {
      border-color:#6b73ff !important;
      border-width:2px;
      background:rgba(107,115,255,.12);
    }
    body.theme-light .theme-option.selected:hover {
      border-color:#6b73ff !important;
      border-width:2px;
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
    body.theme-light .search-bar,
    body.theme-light .report-btn,
    body.theme-light .cam-btn
    {
      background: rgba(15,23,42,.08) !important;
      color: #0f172a !important;
      border-color: rgba(15,23,42,.15) !important;
    }

    body.theme-light .btn:hover,
    body.theme-light .action-btn:hover,
    body.theme-light .filter-btn:hover,
    body.theme-light .report-btn:hover,
    body.theme-light .cam-btn:hover
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
      color: #0f172a !important;
    }

    body.theme-light .page-title p {
      color: #475569 !important;
    }

    body.theme-light .section-head h3 {
      color: #7c3aed !important;
    }

    body.theme-light .btn,
    body.theme-light .btn.slim,
    body.theme-light .action-btn,
    body.theme-light .filter-btn.reset,
    body.theme-light .filter-select,
    body.theme-light .filter-input,
    body.theme-light .search-bar
    {
      color: #0f172a !important;
      border-color: #e5e7eb !important;
      background: #f8fafb !important;
    }

    body.theme-light .btn:hover,
    body.theme-light .action-btn:hover,
    body.theme-light .filter-btn:hover
    {
      background: #f1f5f9 !important;
    }

    body.theme-light .logs-table-wrap {
      background: #ffffff !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .logs-table-wrap th {
      background: #f8fafb !important;
      color: #475569 !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .logs-table-wrap td {
      color: #0f172a !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .logs-table-wrap tbody tr:hover td {
      background: #f3f4f6 !important;
    }

    body.theme-light .chip {
      background: #f8fafb !important;
      border-color: #e5e7eb !important;
      color: #475569 !important;
    }

    body.theme-light .chip:hover {
      background: #f1f5f9 !important;
    }

    body.theme-light .chip.active {
      background: linear-gradient(135deg,#7c3aed,.85,#2563eb,.45) !important;
      color: #fff !important;
      border-color: transparent !important;
      box-shadow: 0 8px 24px rgba(80,94,255,.2) !important;
    }

    body.theme-light .td-mono {
      color: #6b7280 !important;
    }

    body.theme-light .log-desc {
      color: #475569 !important;
    }

    body.theme-light .log-desc.muted {
      color: #6b7280 !important;
    }

    body.theme-light .log-action-badge.other {
      background: #f8fafb !important;
      color: #475569 !important;
      border-color: #e5e7eb !important;
    }

    body.theme-light .topbar {
      color: #0f172a !important;
    }

    body.theme-ash{
      --bg:#e2e8f0;
      --glass:rgba(220,225,235,.75);
      --glass-strong:rgba(235,240,245,.85);
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
      background:linear-gradient(135deg,#f8fafc 0%,#cbd5e1 100%);
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
    /* Override bright white inline styles for ash theme */
    body.theme-ash [style*="background:rgba(255,255,255,.92)"],
    body.theme-ash [style*="background:rgba(255,255,255,.98)"],
    body.theme-ash [style*="background:rgba(255,255,255,.085)"],
    body.theme-ash [style*="background:rgba(255,255,255,.055)"],
    body.theme-ash [style*="background:rgba(255,255,255,.1)"],
    body.theme-ash [style*="background:rgba(255,255,255,.13)"],
    body.theme-ash [style*="background:rgba(255,255,255,.18)"],
    body.theme-ash [style*="background:rgba(255,255,255,.05)"],
    body.theme-ash [style*="background:rgba(255,255,255,.04)"],
    body.theme-ash [style*="background:rgba(255,255,255,.08)"]
    {
      background: rgba(220,225,235,.6) !important;
      color: #0f172a !important;
    }
    body.theme-ash [style*="color:rgba(255,255,255,.5)"],
    body.theme-ash [style*="color:rgba(255,255,255,.6)"],
    body.theme-ash [style*="color:rgba(255,255,255,.7)"],
    body.theme-ash [style*="color:rgba(255,255,255,.75)"],
    body.theme-ash [style*="color:rgba(255,255,255,.8)"]
    {
      color: #0f172a !important;
    }
    /* Ash theme text color fixes */
    body.theme-ash,
    body.theme-ash h1,
    body.theme-ash h2,
    body.theme-ash h3,
    body.theme-ash h4,
    body.theme-ash h5,
    body.theme-ash h6,
    body.theme-ash p,
    body.theme-ash span,
    body.theme-ash label,
    body.theme-ash b,
    body.theme-ash strong,
    body.theme-ash td,
    body.theme-ash th,
    body.theme-ash li,
    body.theme-ash a,
    body.theme-ash button
    {
      color: #0f172a !important;
    }
    body.theme-ash .glass h3,
    body.theme-ash .glass h2,
    body.theme-ash .glass h1,
    body.theme-ash .card h3,
    body.theme-ash .card h2,
    body.theme-ash .card h1,
    body.theme-ash .section-head h3
    {
      color: #0f172a !important;
    }
    body.theme-ash .glass span,
    body.theme-ash .glass p,
    body.theme-ash .glass strong,
    body.theme-ash .glass b,
    body.theme-ash .glass label,
    body.theme-ash .glass td,
    body.theme-ash .glass th,
    body.theme-ash .card span,
    body.theme-ash .card p,
    body.theme-ash .card strong,
    body.theme-ash .card b,
    body.theme-ash .card label,
    body.theme-ash .card td,
    body.theme-ash .card th
    {
      color: #0f172a !important;
    }
    body.theme-ash .stat-body span,
    body.theme-ash .stat-body strong,
    body.theme-ash .stat-body b
    {
      color: #0f172a !important;
    }
    body.theme-ash .activity b,
    body.theme-ash .activity p,
    body.theme-ash .activity span,
    body.theme-ash .activity small
    {
      color: #0f172a !important;
    }
    body.theme-ash .mini b,
    body.theme-ash .mini strong
    {
      color: #0f172a !important;
    }
    body.theme-ash .row-item,
    body.theme-ash .row-item span,
    body.theme-ash .row-item b,
    body.theme-ash .row-item strong
    {
      color: #0f172a !important;
    }
    /* Ash theme search bar styling */
    body.theme-ash .search-bar {
      background: rgba(245,247,250,.9) !important;
      border: 1px solid rgba(15,23,42,.15) !important;
      color: #0f172a !important;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.8) !important;
    }
    body.theme-ash .search-bar:hover {
      background: rgba(240,243,248,.95) !important;
      border-color: rgba(15,23,42,.25) !important;
    }
    body.theme-ash .search-bar::placeholder {
      color: #475569 !important;
    }
    body.theme-ash .search-bar span {
      color: #0f172a !important;
    }
    /* Override inline search input styles for ash theme */
    body.theme-ash input[style*="background:rgba(8,12,30,.58)"],
    body.theme-ash #tableSearch,
    body.theme-ash input[type="date"],
    body.theme-ash input[type="datetime-local"]
    {
      background: rgba(245,247,250,.9) !important;
      border: 1px solid rgba(15,23,42,.15) !important;
      color: #0f172a !important;
    }
    body.theme-ash input[style*="background:rgba(8,12,30,.58)"]::placeholder,
    body.theme-ash #tableSearch::placeholder,
    body.theme-ash input[type="date"]::placeholder,
    body.theme-ash input[type="datetime-local"]::placeholder {
      color: #475569 !important;
    }

    body.theme-ash input[type="date"]::-webkit-datetime-edit,
    body.theme-ash input[type="date"]::-webkit-datetime-edit-text,
    body.theme-ash input[type="date"]::-webkit-datetime-edit-month-field,
    body.theme-ash input[type="date"]::-webkit-datetime-edit-day-field,
    body.theme-ash input[type="date"]::-webkit-datetime-edit-year-field,
    body.theme-ash input[type="datetime-local"]::-webkit-datetime-edit,
    body.theme-ash input[type="datetime-local"]::-webkit-datetime-edit-text,
    body.theme-ash input[type="datetime-local"]::-webkit-datetime-edit-month-field,
    body.theme-ash input[type="datetime-local"]::-webkit-datetime-edit-day-field,
    body.theme-ash input[type="datetime-local"]::-webkit-datetime-edit-year-field {
      color: #0f172a !important;
    }

    body.theme-ash .pill.green {
      color: #166534 !important;
      background: #dcfce7 !important;
      border-color: #bbf7d0 !important;
    }

    body.theme-ash .table-wrap,
    body.theme-ash .att-table-wrap {
      background: rgba(255,255,255,.08) !important;
      border: 1px solid rgba(255,255,255,.18) !important;
      border-radius: var(--radius-md);
      overflow: hidden;
      backdrop-filter: blur(14px);
    }

    body.theme-ash .table-wrap table,
    body.theme-ash .att-table-wrap table {
      background: transparent !important;
    }

    body.theme-ash .table-wrap table tr,
    body.theme-ash .table-wrap table tbody tr,
    body.theme-ash .att-table-wrap table tr,
    body.theme-ash .att-table-wrap table tbody tr
    {
      background: rgba(255,255,255,.18) !important;
      color: #0f172a !important;
    }

    body.theme-ash .table-wrap table tbody tr:nth-child(even),
    body.theme-ash .att-table-wrap table tbody tr:nth-child(even)
    {
      background: rgba(255,255,255,.12) !important;
    }

    body.theme-ash .table-wrap table tbody tr:hover,
    body.theme-ash .att-table-wrap table tbody tr:hover
    {
      background: rgba(255,255,255,.24) !important;
    }

    body.theme-ash .table-wrap table td,
    body.theme-ash .table-wrap table th,
    body.theme-ash .att-table-wrap table td,
    body.theme-ash .att-table-wrap table th
    {
      color: #0f172a !important;
      border-color: rgba(15,23,42,.08) !important;
    }

    body.theme-ash .table-wrap table th,
    body.theme-ash .att-table-wrap table th {
      background: rgba(255,255,255,.85) !important;
      color: #334155 !important;
      backdrop-filter: blur(12px) !important;
    }

    /* Ash theme button styling */
    body.theme-ash .btn,
    body.theme-ash .btn.slim,
    body.theme-ash button,
    body.theme-ash a.btn {
      background: linear-gradient(135deg, rgba(109,40,217,.35), rgba(37,99,235,.25)) !important;
      border: 1.5px solid rgba(109,40,217,.5) !important;
      color: #0f172a !important;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 4px 12px rgba(109,40,217,.15) !important;
      font-weight: 700;
    }
    body.theme-ash .btn:hover,
    body.theme-ash .btn.slim:hover,
    body.theme-ash button:hover,
    body.theme-ash a.btn:hover {
      background: linear-gradient(135deg, rgba(109,40,217,.5), rgba(37,99,235,.4)) !important;
      border-color: rgba(109,40,217,.7) !important;
      color: #0f172a !important;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.6), 0 6px 16px rgba(109,40,217,.25) !important;
      transform: translateY(-2px);
    }
    body.theme-ash .btn.primary {
      background: linear-gradient(135deg,#6d28d9,.8,#2563eb) !important;
      border-color: transparent !important;
      color: #fff !important;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 6px 18px rgba(80,94,255,.35) !important;
    }
    body.theme-ash .btn.primary:hover {
      box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 10px 28px rgba(80,94,255,.45) !important;
      transform: translateY(-2px);
    }
    body.theme-ash .btn.danger {
      background: linear-gradient(135deg, rgba(220,38,38,.3), rgba(239,68,68,.2)) !important;
      border: 1.5px solid rgba(220,38,38,.5) !important;
      color: #7f1d1d !important;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.4), 0 4px 12px rgba(220,38,38,.15) !important;
      font-weight: 700;
    }
    body.theme-ash .btn.danger:hover {
      background: linear-gradient(135deg, rgba(220,38,38,.45), rgba(239,68,68,.35)) !important;
      border-color: rgba(220,38,38,.7) !important;
      color: #580808 !important;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 6px 16px rgba(220,38,38,.25) !important;
      transform: translateY(-2px);
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
      background:radial-gradient(circle at top left, rgba(67,166,255,.16), transparent 22%), radial-gradient(circle at top right, rgba(139,92,255,.12), transparent 22%), linear-gradient(180deg, #020510 0%, #020511 100%);
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
      background:radial-gradient(circle at top left, rgba(79,70,229,.14), transparent 20%), linear-gradient(180deg, #080a14 0%, #0d1527 100%);
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

    body.theme-dark .table thead th,
    body.theme-dark .table-wrap th,
    body.theme-dark .logs-table-wrap th,
    body.theme-dark .table-responsive th,
    body.theme-dark .table thead th {
      color: #ffffff !important;
    }

    body.theme-onyx .table thead th,
    body.theme-onyx .table-wrap th,
    body.theme-onyx .logs-table-wrap th,
    body.theme-onyx .table-responsive th,
    body.theme-onyx .table thead th {
      color: #ffffff !important;
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
      background:rgba(255,255,255,.18);
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
      padding:11px 12px;border-radius:15px;display:flex;align-items:center;gap:11px;
      font-weight:600;cursor:pointer;transition:.2s cubic-bezier(.4,0,.2,1);
      text-align:left;font-size:14px;font-family:var(--font);width:100%;position:relative;text-decoration:none;
    }
    .nav a .nav-icon, .nav button .nav-icon{
      width:34px;height:34px;border-radius:11px;display:grid;place-items:center;font-size:16px;
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
      color:var(--text);
      background:none;
      -webkit-background-clip:unset;
      -webkit-text-fill-color:unset;
    }
    .page-title p{margin-top:4px;color:var(--muted);font-size:13px;font-weight:500}
    
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
    .section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-shrink:0;padding-bottom:8px;border-bottom:2px solid rgba(139,92,255,0.5)}
    .section-head h3{font-size:15px;font-weight:800;letter-spacing:-.03em;display:flex;align-items:center;gap:8px;color:#ffffff}
    .section-head a{color:#ffffff;text-decoration:none;font-weight:700;font-size:12px;letter-spacing:.02em;cursor:pointer}
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
      border-bottom:2px solid rgba(139,92,255,0.5);transition:.2s ease;border-radius:8px;
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
    .activity b{font-size:13.5px;font-weight:700;color:#ffffff}
    .activity p{margin:3px 0 0;color:#ffffff;font-size:12.5px;line-height:1.4}
    .activity time{font-size:11.5px;color:#ffffff;font-variant-numeric:tabular-nums;white-space:nowrap;font-family:var(--mono)}

    .row-item{
      display:flex;align-items:center;justify-content:space-between;
      border:2px solid rgba(139,92,255,0.6);background:rgba(255,255,255,.055);
      border-radius:12px;padding:9px 12px;margin-bottom:6px;font-weight:700;font-size:13px;transition:.2s ease;color:#ffffff;
    }
    .row-item:hover{background:rgba(255,255,255,.09)}
    .row-item:last-child{margin-bottom:0}
    .row-item span{color:#ffffff;font-weight:500}

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
    .pill.green{color:#166534;background:#dcfce7;border-color:#bbf7d0}

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

    /* Ash theme final readability cleanup */
    body.theme-ash th,
    body.theme-ash td,
    body.theme-ash .section-head h3,
    body.theme-ash .section-head a,
    body.theme-ash .activity b,
    body.theme-ash .activity p,
    body.theme-ash .activity time,
    body.theme-ash .row-item,
    body.theme-ash .row-item span,
    body.theme-ash .mini b,
    body.theme-ash .mini small,
    body.theme-ash .quick strong,
    body.theme-ash .quick span
    {
      color: #0f172a !important;
    }

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

    (function() {
      const themeKey = 'qr_attendance_theme';
      const themeNames = ['light','ash','dark','onyx'];
      const defaultTheme = 'light';
      const current = themeNames.includes(localStorage.getItem(themeKey)) ? localStorage.getItem(themeKey) : defaultTheme;
      document.body.classList.remove('theme-light','theme-ash','theme-dark','theme-onyx');
      document.body.classList.add('theme-' + current);
    })();
  </script>
</body>
</html>