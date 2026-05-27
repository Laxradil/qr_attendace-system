<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --navy: #0d0c1d;
            --navy2: #13122a;
            --navy3: #1a1938;
            --purple: #6c5ce7;
            --purple2: #5a4fcf;
            --purple-light: #a29bfe;
            --purple-glow: rgba(108, 92, 231, 0.15);
            --text: #e8e6ff;
            --text2: #9b97cc;
            --text3: #5a5880;
            --border: rgba(255, 255, 255, 0.06);
            --border2: rgba(255, 255, 255, 0.03);
            --surface: rgba(255, 255, 255, 0.02);
            --surface2: rgba(255, 255, 255, 0.05);
            --radius: 8px;
            --radius-lg: 12px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #07080f;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at center, rgba(108, 92, 231, 0.12), transparent 24%);
            pointer-events: none;
            mix-blend-mode: screen;
        }

        .login-container {
            position: relative;
            background: rgba(12, 14, 28, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: var(--radius-lg);
            overflow: hidden;
            max-width: 520px;
            width: 100%;
            padding: 2.5rem;
            backdrop-filter: blur(22px);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.22);
        }

        .login-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.12), transparent 32%),
                        linear-gradient(225deg, rgba(97, 139, 255, 0.07), transparent 28%);
            pointer-events: none;
            mix-blend-mode: screen;
        }

        #aurora-wrap {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            background: linear-gradient(180deg, #070816 0%, #0d1334 40%, #070816 100%);
        }

        #aurora-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
        }

        #mountain-silhouette {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 36vh;
            max-height: 420px;
            display: block;
            z-index: 0;
            pointer-events: none;
            overflow: visible;
        }

        #mountain-overlay {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 36vh;
            max-height: 420px;
            background: linear-gradient(180deg, rgba(2,6,10,0) 0%, rgba(2,6,10,0.65) 100%);
            z-index: 2;
            pointer-events: none;
        }

        .login-container {
            z-index: 5;
            position: relative;
        }

        .login-container > * {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--purple), #6c5ce7);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.2);
        }

        .brand-logo i {
            font-size: 1.75rem;
            color: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--text);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text2);
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            color: var(--text);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text3);
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 2.75rem 0.75rem 2.5rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.9rem;
            transition: all 0.3s;
            background: var(--surface2);
            color: var(--text);
            font-family: 'Inter', sans-serif;
        }

        .form-control::placeholder {
            color: var(--text3);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--purple);
            background: var(--navy3);
            box-shadow: 0 0 0 3px var(--purple-glow);
        }

        .form-control:focus ~ i {
            color: var(--purple);
        }

        .password-toggle {
            position: absolute;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text3);
            cursor: pointer;
            padding: 5px 8px;
            transition: all 0.3s;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--purple-light);
        }
        
        .password-toggle:active {
            color: var(--purple);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid var(--border);
            cursor: pointer;
            background: var(--surface);
            appearance: none;
            transition: all 0.3s;
        }

        .form-check-input:checked {
            background: var(--purple);
            border-color: var(--purple);
        }

        .form-check-label {
            color: var(--text2);
            font-size: 0.875rem;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--purple-light);
            font-size: 0.875rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: var(--purple);
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.96), rgba(168, 85, 247, 0.95));
            border: none;
            border-radius: calc(var(--radius) * 1.2);
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 14px 30px rgba(108, 92, 231, 0.18);
            letter-spacing: 0.01em;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, rgba(168, 85, 247, 1), rgba(108, 92, 231, 0.95));
            transform: translateY(-2px);
            box-shadow: 0 18px 35px rgba(108, 92, 231, 0.22);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-box {
            text-align: center;
            margin-top: 1.5rem;
        }

        .register-box p {
            margin: 0;
            color: var(--text2);
            font-size: 0.95rem;
        }

        .register-box a {
            color: var(--purple);
            text-decoration: none;
            font-weight: 600;
        }

        .register-box a:hover {
            color: var(--purple2);
        }

        .error-box {
            background: rgba(255, 72, 66, 0.12);
            border: 1px solid rgba(255, 72, 66, 0.25);
            color: #ffe8e6;
            padding: 1rem 1.1rem;
            border-radius: 0.85rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .error-box strong {
            display: block;
            margin-bottom: 0.35rem;
            color: #ffb3b0;
        }

        .error-text {
            color: #ffb3b0;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .form-control.error {
            border-color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div id="aurora-wrap">
        <canvas id="aurora-canvas" aria-hidden="true"></canvas>
        <svg id="mountain-silhouette" viewBox="0 0 1200 300" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
            <defs>
                <linearGradient id="mg1" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%" stop-color="#121a36" />
                    <stop offset="100%" stop-color="#050a18" />
                </linearGradient>
                <linearGradient id="mg2" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%" stop-color="#1f2a5c" />
                    <stop offset="100%" stop-color="#050a18" />
                </linearGradient>
            </defs>
            <path d="M0,230 L120,190 L220,210 L320,180 L420,205 L540,160 L660,210 L780,170 L900,200 L1020,150 L1200,190 L1200,300 L0,300 Z" fill="url(#mg1)" opacity="0.95" />
            <path d="M0,260 L80,200 L160,240 L260,180 L360,240 L460,170 L560,240 L680,160 L760,220 L860,170 L960,230 L1080,160 L1200,240 L1200,300 L0,300 Z" fill="url(#mg2)" opacity="0.98" />
            <path d="M0,290 L140,230 L260,270 L380,220 L520,270 L660,210 L800,270 L960,220 L1100,280 L1200,260 L1200,300 L0,300 Z" fill="#02060a" opacity="1" />
        </svg>
        <div id="mountain-overlay" aria-hidden="true"></div>
    </div>
    <div class="login-container">
        <div class="brand-logo">
            <i class="fas fa-clock"></i>
        </div>
        
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="error-box" id="loginErrorBox">
                <strong>Login failed</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" novalidate id="loginForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" class="form-control @error('email') error @enderror" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                    <i class="fas fa-envelope"></i>
                </div>
                <span class="error-text" id="emailError" @if(!$errors->has('email')) style="display:none;" @endif>
                    @error('email') {{ $message }} @enderror
                </span>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control @error('password') error @enderror" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-lock"></i>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="error-text" id="passwordError" @if(!$errors->has('password')) style="display:none;" @endif>
                    @error('password') {{ $message }} @enderror
                </span>
            </div>
            
            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                Sign In <i class="fas fa-arrow-right" style="margin-left: 0.65rem;"></i>
            </button>
        </form>

        <div class="register-box">
            <p>Don't have an account yet? <a href="{{ route('register') }}">Register now</a></p>
        </div>
    </div>
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        const clearErrors = () => {
            const errorBox = document.getElementById('loginErrorBox');
            if (errorBox) {
                errorBox.style.display = 'none';
            }
            document.querySelectorAll('.error-text').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.form-control.error').forEach(el => el.classList.remove('error'));
        };

        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        const showFieldError = (field, message) => {
            const errorSpan = document.getElementById(field.id + 'Error');
            if (errorSpan) {
                errorSpan.textContent = message;
                errorSpan.style.display = 'block';
            }
            field.classList.add('error');
        };

        loginForm.addEventListener('submit', function(event) {
            clearErrors();
            let hasError = false;
            const emailValue = emailInput.value.trim();
            const passwordValue = passwordInput.value.trim();

            if (!emailValue) {
                showFieldError(emailInput, 'Email is required.');
                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                showFieldError(emailInput, 'Please enter a valid email address.');
                hasError = true;
            }

            if (!passwordValue) {
                showFieldError(passwordInput, 'Password is required.');
                hasError = true;
            }

            if (hasError) {
                event.preventDefault();
            }
        });

        document.querySelectorAll('input[name="email"], input[name="password"]').forEach(input => {
            input.addEventListener('input', clearErrors);
        });

        // Aurora canvas animator + SVG mountain parallax
        (function(){
            const canvas = document.getElementById('aurora-canvas');
            const mountain = document.getElementById('mountain-silhouette');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let w = canvas.width = window.innerWidth;
            let h = canvas.height = window.innerHeight;
            let t = 0;

            function resize(){
                w = canvas.width = window.innerWidth;
                h = canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);

            const stars = [];
            const STAR_COUNT = Math.min(240, Math.floor((w*h)/4600));
            for (let i=0;i<STAR_COUNT;i++){
                stars.push({
                    x: Math.random()*w,
                    y: Math.random()*h*0.60,
                    size: Math.random()*1.8 + (Math.random()>0.92?1.9:0),
                    phase: Math.random()*Math.PI*2,
                    twinkleSpeed: 0.002 + Math.random()*0.007
                });
            }

            function drawStars(){
                ctx.save();
                ctx.globalCompositeOperation = 'screen';
                for (let i=0;i<stars.length;i++){
                    const s = stars[i];
                    const a = 0.25 + 0.75*Math.abs(Math.sin(t*s.twinkleSpeed + s.phase));
                    ctx.fillStyle = `rgba(255,255,255,${Math.min(0.85, a)})`;
                    ctx.fillRect(s.x, s.y, s.size, s.size);
                    const g = ctx.createRadialGradient(s.x, s.y, 0, s.x, s.y, s.size*6);
                    g.addColorStop(0, `rgba(255,255,255,${a*0.12})`);
                    g.addColorStop(1, 'rgba(255,255,255,0)');
                    ctx.fillStyle = g;
                    ctx.beginPath();
                    ctx.arc(s.x, s.y, s.size*6, 0, Math.PI*2);
                    ctx.fill();
                }
                ctx.restore();
            }

            function fade(t){
                return t * t * t * (t * (t * 6 - 15) + 10);
            }

            function hash(x,y,z){
                return Math.abs(Math.sin(x*127.1 + y*311.7 + z*74.7) * 43758.5453123) % 1;
            }

            function noise(x, y, z=0) {
                const xi = Math.floor(x);
                const yi = Math.floor(y);
                const xf = x - xi;
                const yf = y - yi;

                const a = hash(xi, yi, z);
                const b = hash(xi + 1, yi, z);
                const c = hash(xi, yi + 1, z);
                const d = hash(xi + 1, yi + 1, z);

                const u = fade(xf);
                const v = fade(yf);
                const lerp = (a, b, t) => a + (b - a) * t;
                return lerp(lerp(a,b,u), lerp(c,d,u), v) * 2 - 1;
            }

            function fractal(x, y, z, octaves=3){
                let sum = 0;
                let amp = 1;
                let freq = 1;
                let max = 0;
                for (let i=0;i<octaves;i++){
                    sum += noise(x*freq, y*freq, z*freq) * amp;
                    max += amp;
                    amp *= 0.5;
                    freq *= 2;
                }
                return sum / max;
            }

            function drawRibbon(hue, baseY, height, speed, offset, alpha, wave){
                const pts = 48;
                const amp = height;
                ctx.save();
                ctx.globalCompositeOperation = 'lighter';

                ctx.beginPath();
                ctx.moveTo(0, h);
                ctx.lineTo(0, baseY);
                for (let i=0;i<=pts;i++){
                    const x = (i/pts)*w;
                    const px = x/w;
                    const warp = fractal(px*2.4 + offset*0.7, baseY*0.008 + t*0.00085, offset) * amp * 0.18;
                    const y = baseY
                        + Math.sin(px * Math.PI * 2 * wave + t * speed * 0.78 + offset) * amp * 0.32
                        + Math.cos(px * Math.PI * 4 + t * speed * 0.42 + offset * 1.4) * amp * 0.2
                        + warp;
                    ctx.lineTo(x, y);
                }
                ctx.lineTo(w, h);
                ctx.closePath();

                const grad = ctx.createLinearGradient(0, baseY - amp, 0, baseY + amp * 1.4);
                grad.addColorStop(0, `hsla(${hue},92%,62%,0)`);
                grad.addColorStop(0.15, `hsla(${hue+8},95%,56%,${alpha*0.22})`);
                grad.addColorStop(0.45, `hsla(${hue+24},98%,48%,${alpha*0.92})`);
                grad.addColorStop(0.75, `hsla(${hue+58},95%,44%,${alpha*0.24})`);
                grad.addColorStop(1, `hsla(${hue+85},95%,38%,0)`);

                ctx.fillStyle = grad;
                ctx.filter = 'blur(16px)';
                ctx.fill();
                ctx.filter = 'none';
                ctx.restore();
            }

            function drawGlow() {
                ctx.save();
                ctx.globalCompositeOperation = 'lighter';
                const glow = ctx.createRadialGradient(w*0.22, h*0.22, 0, w*0.22, h*0.22, h*0.56);
                glow.addColorStop(0, 'rgba(102, 159, 255, 0.28)');
                glow.addColorStop(0.35, 'rgba(123, 78, 255, 0.15)');
                glow.addColorStop(1, 'rgba(64, 35, 100, 0)');
                ctx.fillStyle = glow;
                ctx.fillRect(0, 0, w, h);
                ctx.restore();
            }

            function render(){
                ctx.clearRect(0, 0, w, h);
                const sky = ctx.createLinearGradient(0, 0, 0, h);
                sky.addColorStop(0, 'rgba(5, 14, 24, 0.08)');
                sky.addColorStop(0.18, 'rgba(12, 20, 44, 0.25)');
                sky.addColorStop(0.52, 'rgba(18, 36, 78, 0.32)');
                sky.addColorStop(1, 'rgba(3, 8, 14, 0.97)');
                ctx.fillStyle = sky;
                ctx.fillRect(0, 0, w, h);

                drawGlow();
                drawRibbon(268, h*0.20, Math.max(110, h*0.15), 0.00185, 0.0, 0.94, 1.08);
                drawRibbon(286, h*0.40, Math.max(140, h*0.12), 0.0013, 1.9, 0.72, 0.89);
                drawRibbon(235, h*0.55, Math.max(96, h*0.08), 0.00072, 4.1, 0.44, 0.96);
                drawStars();

                if (mountain) {
                    const px = Math.sin(t*0.0019) * Math.min(18, w*0.008);
                    const py = Math.cos(t*0.00095) * Math.min(10, h*0.005);
                    mountain.style.transform = `translate3d(${px}px, ${py}px, 0)`;
                }

                t += 1;
                requestAnimationFrame(render);
            }

            render();
        })();
    </script>
</body>
</html>