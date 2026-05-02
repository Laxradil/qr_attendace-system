<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --navy: #0b1224;
            --panel: rgba(15, 23, 42, 0.96);
            --panel-strong: rgba(10, 16, 30, 0.98);
            --border: rgba(148, 163, 184, 0.12);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #7c3aed;
            --accent-soft: rgba(124, 58, 237, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at top left, rgba(124, 58, 237, 0.18), transparent 25%),
                        linear-gradient(180deg, #070a17 0%, #091124 60%, #0b1224 100%);
            min-height: 100vh;
            font-family: Inter, 'Segoe UI', sans-serif;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-container {
            width: min(480px, 100%);
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: 0 40px 120px rgba(0, 0, 0, 0.35);
            padding: 36px 34px;
            overflow: hidden;
        }

        .brand-logo {
            width: 62px;
            height: 62px;
            margin: 0 auto 1.5rem;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.95), rgba(59, 130, 246, 0.9));
            box-shadow: 0 16px 40px rgba(124, 58, 237, 0.18);
        }

        .brand-logo i {
            color: white;
            font-size: 1.45rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            font-size: 2rem;
            color: white;
            margin-bottom: 0.4rem;
        }

        .login-header p {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.3rem;
        }

        .form-label {
            color: #cbd5e1;
            font-size: 0.88rem;
            margin-bottom: 0.6rem;
            display: block;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
            display: block;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 1rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.95rem 4.6rem 0.95rem 3rem;
            border-radius: 16px;
            border: 1px solid transparent;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transition: all 0.25s ease;
            font-size: 0.96rem;
            min-height: 3.2rem;
        }

        .form-control::placeholder {
            color: rgba(226, 232, 240, 0.5);
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(124, 58, 237, 0.45);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }

        .input-wrapper:focus-within i {
            color: #a78bfa;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border: none;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            color: var(--muted);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
            pointer-events: auto;
            z-index: 5;
            font-size: 0.95rem;
            padding: 0;
        }

        .password-toggle i {
            line-height: 1;
            font-size: 14px;
            margin: 0;
            display: inline-block;
        }

        .password-toggle i {
            line-height: 1;
        }

        .password-toggle:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #e2e8f0;
            transform: translateY(-50%) scale(1.02);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            color: var(--text);
            font-size: 0.9rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: rgba(255,255,255,0.05);
            accent-color: #7c3aed;
            cursor: pointer;
        }

        .form-check-label {
            color: var(--text);
            cursor: pointer;
        }

        .forgot-link {
            color: #a78bfa;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #ede9fe;
        }

        .btn-login {
            width: 100%;
            padding: 1rem 1.1rem;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 18px 30px rgba(124, 58, 237, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 40px rgba(124, 58, 237, 0.24);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-note {
            margin-top: 1.6rem;
            text-align: center;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <i class="fas fa-clock"></i>
        </div>
        
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Please enter your details to sign in</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-lock"></i>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-login">
                Sign In
            </button>
        </form>
        
        <div class="footer-note">
            Access is restricted to registered students and professors only.
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    </script>
</body>
</html>