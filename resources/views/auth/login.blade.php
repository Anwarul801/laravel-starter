<!DOCTYPE html>
{{--
 @Author: Modern Redesign
 @Description: Innova IT - Modern Login Page (No Scroll)
--}}
<html lang="en">
<head>
    <title>Admin  Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta charset="UTF-8">
    <meta name="description" content="Secure access to Admin Dashboard">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/images/favicon.ico') }}">

    <!-- Font Awesome 6 + Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <style>
        /* RESET & NO SCROLL */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;   /* kill horizontal scroll */
            overflow-y: auto;     /* allow vertical if content exceeds, but our card fits */
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            min-height: 100vh;
            position: relative;
        }

        /* Subtle animated background (FIXED: no overflow) */
        body::before {
            content: '';
            position: fixed;      /* instead of absolute, so it never causes scroll */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 40%, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            animation: slowDrift 40s linear infinite;
            z-index: 0;
        }

        @keyframes slowDrift {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 20px); }
        }

        /* Modern Card – fits exactly */
        .login-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255,255,255,0.2);
            width: 100%;
            max-width: 460px;
            padding: 2rem 2rem 2.2rem;
            transition: transform 0.25s ease, box-shadow 0.3s ease;
            animation: fadeSlideUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            position: relative;
            z-index: 2;
            margin: 0 auto;       /* center and avoid overflow */
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card:hover {
            box-shadow: 0 30px 50px -15px rgba(0, 0, 0, 0.4);
        }

        /* Branding */
        .brand {
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .brand-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(145deg, #1f6e43, #0e4b2f);
            border-radius: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 12px 18px -8px rgba(0,0,0,0.2);
        }
        .brand-logo i {
            font-size: 2.4rem;
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        .brand h1 {
            font-size: 1.85rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1a472a, #2b7a4b);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            margin: 0;
        }
        .brand p {
            color: #5f6c7a;
            font-size: 0.9rem;
            margin-top: 0.4rem;
            font-weight: 500;
        }

        /* Role Switcher */
        .role-switch {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            background: #f1f5f9;
            padding: 0.4rem;
            border-radius: 60px;
        }
        .role-link {
            flex: 1;
            text-align: center;
            padding: 0.6rem 0;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 40px;
            transition: all 0.2s;
            text-decoration: none;
            background: transparent;
            color: #2c3e50;
        }
        .role-link.active {
            background: white;
            color: #1f6e43;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .role-link:not(.active):hover {
            background: rgba(255,255,255,0.6);
            color: #1f6e43;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.4rem;
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .form-control {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            font-size: 0.95rem;
            font-weight: 500;
            border: 1.5px solid #e2e8f0;
            border-radius: 1.2rem;
            background: #ffffff;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            outline: none;
        }
        .form-control:focus {
            border-color: #2b7a4b;
            box-shadow: 0 0 0 4px rgba(43, 122, 75, 0.15);
        }
        .form-control.is-invalid {
            border-color: #e53e3e;
            background-color: #fff5f5;
        }
        .invalid-feedback {
            display: block;
            margin-top: 0.45rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #e53e3e;
            background: rgba(229,62,62,0.08);
            padding: 0.25rem 0.75rem;
            border-radius: 40px;
            width: fit-content;
        }
        .invalid-feedback i {
            font-size: 0.7rem;
            margin-right: 4px;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .password-toggle:hover {
            color: #1f6e43;
        }

        /* Checkbox & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.2rem 0 1.6rem;
            font-size: 0.85rem;
        }
        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            font-weight: 500;
            color: #2d3e50;
        }
        .checkbox-custom input {
            width: 16px;
            height: 16px;
            accent-color: #2b7a4b;
            cursor: pointer;
            margin: 0;
        }
        .forgot-link {
            color: #1f6e43;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }
        .forgot-link:hover {
            text-decoration: underline;
            color: #0e4b2f;
        }

        /* Login Button */
        .btn-login {
            background: linear-gradient(105deg, #1f6e43, #0f4c2f);
            border: none;
            width: 100%;
            padding: 0.9rem;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 2rem;
            color: white;
            transition: all 0.25s;
            box-shadow: 0 8px 18px rgba(31, 110, 67, 0.3);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            background: linear-gradient(105deg, #2a8b57, #15623b);
            box-shadow: 0 12px 22px rgba(31, 110, 67, 0.4);
        }
        .btn-login:active {
            transform: translateY(1px);
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #7c8b9c;
            border-top: 1px solid #eef2f6;
            padding-top: 1.5rem;
        }

        /* Responsive: no overflow */
        @media (max-width: 520px) {
            .login-card {
                padding: 1.5rem;
                max-width: 100%;
            }
            .brand h1 {
                font-size: 1.6rem;
            }
            .form-control {
                padding: 0.8rem 1rem 0.8rem 2.6rem;
            }
            body {
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Brand Section -->
    <div class="brand">
        <div class="brand-logo">
            <i class="fas fa-leaf"></i>
        </div>
        <h1>Admin</h1>
        <p>Your sustainable ecosystem gateway</p>
    </div>

    <!-- Role switcher (Admin / Register commented) -->
    <div class="role-switch">
        <a href="{{ route('login') }}" class="role-link active">Admin Login</a>
        {{-- <a href="{{ route('register') }}" class="role-link">Register</a> --}}
    </div>

    <!-- LOGIN FORM - Same POST to route('login') -->
    <form action="{{ route('login') }}" method="POST" id="loginForm">
        @csrf

        <!-- Email Field -->
        <div class="form-group">
            <span class="input-icon"><i class="fas fa-envelope"></i></span>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email"
                   value="{{ old('email') }}"
                   autocomplete="email"
                   required
                   autofocus
                   placeholder="Email address"
                   id="emailInput">
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-circle" style="font-size: 6px; vertical-align: middle;"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Field with Show/Hide -->
        <div class="form-group">
            <span class="input-icon"><i class="fas fa-lock"></i></span>
            <input type="password"
                   name="password"
                   id="passwordInput"
                   class="form-control @error('password') is-invalid @enderror"
                   autocomplete="current-password"
                   required
                   placeholder="Password">
            <button type="button" class="password-toggle" id="togglePasswordBtn" tabindex="-1">
                <i class="far fa-eye-slash" id="toggleIcon"></i>
            </button>
            @error('password')
                <div class="invalid-feedback">
                    <i class="fas fa-circle" style="font-size: 6px; vertical-align: middle;"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-options">
            <label class="checkbox-custom">
                <input type="checkbox" name="remember" id="rememberMe" value="1">
                <span>Remember me</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-login">
            <i class="fas fa-arrow-right-to-bracket"></i> Sign In
        </button>
    </form>

    <!-- Extra admin link -->
    <div class="auth-footer">
        <span>🔐 Secure access • </span>
        <a href="{{ url('admin/login') }}" style="color: #1f6e43; text-decoration: none; font-weight: 500;">Admin Portal</a>
        <span> &nbsp;|&nbsp; v2.0</span>
    </div>
</div>

<script>
    (function() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordField = document.getElementById('passwordInput');
        const toggleIcon = document.getElementById('toggleIcon');

        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                toggleIcon.classList.toggle('fa-eye-slash');
                toggleIcon.classList.toggle('fa-eye');
            });
        }

        const forgotLink = document.getElementById('forgotPasswordLink');
        if (forgotLink) {
            forgotLink.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Please contact your administrator to reset your password.');
            });
        }
    })();
</script>
</body>
</html>