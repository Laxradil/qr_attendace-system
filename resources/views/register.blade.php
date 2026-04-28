<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3a5f;
            --secondary-color: #3d7ab5;
            --accent-color: #5fa8e3;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .register-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .card-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .card-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        
        .card-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .card-body {
            padding: 35px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(61, 122, 181, 0.1);
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .input-group-text {
            border-radius: 0 10px 10px 0;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(61, 122, 181, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="card-header">
                <i class="fas fa-user-plus"></i>
                <h2>Create Account</h2>
                <p>Join the Attendance System today</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Enter your full name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Enter your email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Create a password" required>
                            <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon1')">
                                <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="password-requirements">
                            <ul>
                                <li>At least 6 characters</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm your password" required>
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')">
                                <i class="fas fa-eye" id="togglePasswordIcon2"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                </form>
                
                <div class="login-link">
                    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>