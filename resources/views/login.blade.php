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
            background: #020205;
            overflow: hidden;
            position: relative;
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
            background: rgba(9, 8, 24, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-lg);
            overflow: hidden;
            max-width: 520px;
            width: 100%;
            padding: 2.5rem;
            backdrop-filter: blur(18px);
            box-shadow: 0 35px 95px rgba(0, 0, 0, 0.35);
        }

        .login-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.16), transparent 34%),
                        linear-gradient(225deg, rgba(0, 243, 255, 0.12), transparent 28%);
            pointer-events: none;
            mix-blend-mode: screen;
        }

        #particle-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
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
            padding: 0.875rem;
            background: var(--purple);
            border: none;
            border-radius: var(--radius);
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .btn-login:hover {
            background: var(--purple2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
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
    <canvas id="particle-canvas"></canvas>
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
                Sign In
            </button>
        </form>
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

        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        const particles = [];
        const particleCount = 320;
        const mouse = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
        const center = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
        const rotation = { x: 0, y: 0, targetX: 0, targetY: 0 };

        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            center.x = window.innerWidth / 2;
            center.y = window.innerHeight / 2;
        };

        class Particle {
            constructor() {
                this.reset();
            }

            reset() {
                this.x = (Math.random() - 0.5) * canvas.width * 0.9;
                this.y = (Math.random() - 0.5) * canvas.height * 0.9;
                this.z = (Math.random() - 0.5) * canvas.width * 0.6;
                this.vx = (Math.random() - 0.5) * 0.05;
                this.vy = (Math.random() - 0.5) * 0.05;
                this.vz = (Math.random() - 0.5) * 0.02;
                this.size = 1.3 + Math.random() * 3.5;
                this.alpha = 0.12 + Math.random() * 0.28;
                this.baseSize = this.size;
                this.phase = Math.random() * Math.PI * 2;
                this.projX = this.x + center.x;
                this.projY = this.y + center.y;
            }

            update() {
                const drift = 0.004 + Math.abs(this.z) / (canvas.width * 28);
                this.vx += (Math.random() - 0.5) * 0.004 + this.x * 0.000004;
                this.vy += (Math.random() - 0.5) * 0.004 + this.y * 0.000003;
                this.vz += (Math.random() - 0.5) * 0.002 - this.z * 0.00001;
                this.vx += Math.cos(this.phase * 0.8) * drift * 0.3;
                this.vy += Math.sin(this.phase * 0.9) * drift * 0.3;
                this.vz += Math.cos(this.phase * 1.2) * drift * 0.12;
                this.vx *= 0.986;
                this.vy *= 0.986;
                this.vz *= 0.986;

                this.x += this.vx;
                this.y += this.vy;
                this.z += this.vz;
                this.phase += 0.014;

                const boundX = canvas.width * 0.75;
                const boundY = canvas.height * 0.75;
                const boundZ = canvas.width * 0.55;

                if (this.x < -boundX) this.x = boundX;
                if (this.x > boundX) this.x = -boundX;
                if (this.y < -boundY) this.y = boundY;
                if (this.y > boundY) this.y = -boundY;
                if (this.z < -boundZ) this.z = boundZ;
                if (this.z > boundZ) this.z = -boundZ;

                const perspective = 520;
                const rotX = rotation.x;
                const rotY = rotation.y;
                const x3 = this.x;
                const y3 = this.y;
                const z3 = this.z;

                const cosY = Math.cos(rotY);
                const sinY = Math.sin(rotY);
                const cosX = Math.cos(rotX);
                const sinX = Math.sin(rotX);

                const xRot = x3 * cosY - z3 * sinY;
                const zRot = x3 * sinY + z3 * cosY;
                const yRot = y3 * cosX - zRot * sinX;
                const zFinal = y3 * sinX + zRot * cosX;

                const scale = perspective / (perspective + zFinal);
                this.projX = center.x + xRot * scale;
                this.projY = center.y + yRot * scale;
                this.drawSize = Math.max(0.8, this.baseSize * scale * 1.1 + Math.sin(this.phase) * 0.4);
                const pulse = (Math.sin(this.phase) + 1) * 0.35;
                this.opacity = Math.max(0.08, Math.min(0.45, this.alpha * Math.max(0.7, scale) + pulse * 0.12));
            }

            draw() {
                ctx.beginPath();
                ctx.fillStyle = `rgba(108, 92, 231, ${this.opacity})`;
                ctx.arc(this.projX, this.projY, this.drawSize, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        const initParticles = () => {
            particles.length = 0;
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        };

        const drawConnections = () => {
            ctx.strokeStyle = 'rgba(108, 92, 231, 0.06)';
            ctx.lineWidth = 1;
            for (let i = 0; i < particleCount; i++) {
                for (let j = i + 1; j < particleCount; j++) {
                    const p1 = particles[i];
                    const p2 = particles[j];
                    const dx = p1.projX - p2.projX;
                    const dy = p1.projY - p2.projY;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 105) {
                        ctx.globalAlpha = (1 - dist / 105) * 0.18;
                        ctx.beginPath();
                        ctx.moveTo(p1.projX, p1.projY);
                        ctx.lineTo(p2.projX, p2.projY);
                        ctx.stroke();
                    }
                }
            }
            ctx.globalAlpha = 1;
        };

        const render = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            rotation.x += (rotation.targetX - rotation.x) * 0.08;
            rotation.y += (rotation.targetY - rotation.y) * 0.08;

            particles.forEach(p => {
                p.update();
                p.draw();
            });
            drawConnections();
            requestAnimationFrame(render);
        };

        window.addEventListener('mousemove', (event) => {
            mouse.x = event.clientX;
            mouse.y = event.clientY;
            rotation.targetX = (event.clientY - center.y) / center.y * 0.18;
            rotation.targetY = (event.clientX - center.x) / center.x * 0.18;
        });

        window.addEventListener('resize', () => {
            resizeCanvas();
            initParticles();
        });

        resizeCanvas();
        initParticles();
        render();
    </script>
</body>
</html>