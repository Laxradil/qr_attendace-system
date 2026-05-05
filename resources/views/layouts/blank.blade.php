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
        html, body {
            font-family:'Inter', sans-serif;
            background:#090818;
            color:var(--text);
            min-height:100vh;
            font-size:13px;
            overflow:hidden;
        }
        body {
            margin:0;
        }
        .modal-body {
            padding: 18px;
            min-height: 100vh;
            background: var(--navy);
            overflow:hidden;
        }
        .btn { cursor: pointer; }
    </style>
</head>
<body class="modal-body">
    @yield('content')
</body>
</html>
