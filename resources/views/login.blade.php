<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3d7ab5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            padding: 3rem;
        }
        .brand-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1e3a5f 0%, #3d7ab5 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(30, 58, 95, 0.3);
        }
        .brand-logo i {
            font-size: 2rem;
            color: white;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h2 {
            color: #1e3a5f;
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            color: #374151;
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
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            transition: color 0.3s;
        }
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #f9fafb;
        }
        .form-control:focus {
            outline: none;
            border-color: #3d7ab5;
            background: white;
            box-shadow: 0 0 0 4px rgba(61, 122, 181, 0.1);
        }
        .form-control:focus + i,
        .input-wrapper:focus-within i {
            color: #3d7ab5;
        }
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #3d7ab5;
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
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 2px solid #d1d5db;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: #3d7ab5;
            border-color: #3d7ab5;
        }
        .form-check-label {
            color: #6b7280;
            font-size: 0.875rem;
            cursor: pointer;
        }
        .forgot-link {
            color: #3d7ab5;
            font-size: 0.875rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .forgot-link:hover {
            color: #1e3a5f;
        }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #3d7ab5 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(30, 58, 95, 0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 95, 0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
        .alert-danger {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        .alert-danger ul {
            padding-left: 1rem;
            margin-bottom: 0;
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
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