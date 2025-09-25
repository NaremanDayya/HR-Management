<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | نظام الموارد البشرية</title>

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Tajawal Arabic Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #740e0e;
            --secondary-color: #ED5565;
            --light-bg: #F5F7FA;
            --card-bg: #FFFFFF;
            --text-dark: #2E2E2E;
            --text-light: #6B7C93;
            --border-radius: 16px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-color: var(--light-bg);
        }

        /* Background with overlay */
        .background-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
        }

        .background-image {
            background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            width: 100%;
            height: 100%;
            filter: brightness(1.2);
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(245, 247, 250, 0.95) 0%, rgba(245, 247, 250, 0.85) 100%);
        }
        .input-field {
            text-align: right;
            direction: rtl;
        }

        .input-field::placeholder {
            text-align: right;
            direction: rtl;
        }
        input[type="password"]::-webkit-credentials-auto-fill-button,
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-strong-password-auto-fill-button {
            display: none !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        /* For Firefox */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }

        /* For Edge */
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
        }

        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-card {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            transition: var(--transition);
        }

        .login-card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-img {
            height: 80px;
            margin-bottom: 15px;
            transition: var(--transition);
            filter: url(#red-recolor);

        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: var(--text-light);
            margin-bottom: 30px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .input-field {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background-color: #f9f9f9;
        }

        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(116, 14, 14, 0.1);
            background-color: var(--card-bg);
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            padding-left: 45px;
        }

        /* Remember Me & Forgot Password */
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-left: 8px;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(116, 14, 14, 0.3);
        }

        .login-btn:hover {
            background-color: #5d0a0a;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(116, 14, 14, 0.4);
        }

        /* Error Messages */
        .error-message {
            color: #d32f2f;
            background-color: #fde8e8;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .password-toggle {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #495057;
        }
        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .login-card {
                padding: 30px 20px;
            }

            .logo-img {
                height: 70px;
            }
        }
    </style>
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <filter id="red-recolor">
            <feColorMatrix type="matrix"
                values="
        0.8 0.1 0.1 0 0
        0.2 0.6 0.2 0 0
        0.1 0.2 0.6 0 0
        0   0   0   1 0
        " />
        </filter>
    </svg>


    <!-- Background -->
    <div class="background-wrapper">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('build/assets/img/logo.png') }}" alt="شعار الشركة" class="logo-img">
                <h1 class="login-title">نظام إدارة الموارد البشرية</h1>
                <p class="login-subtitle">سجل الدخول للوصول إلى حسابك</p>
            </div>

            @error('email')
                <div class="error-message">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" type="email" name="email" required autofocus class="input-field"
                               placeholder="أدخل بريدك الإلكتروني" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <div class="input-with-icon">
                        <input id="password" type="password" name="password" required class="input-field"
                               placeholder="أدخل كلمة المرور">
                        <i class="fas fa-eye-slash password-toggle" id="passwordToggle"></i>
                    </div>
                </div>

                <div class="login-options">
                    {{-- <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">تذكرني</label>
                    </div>
                    <a href="#" class="forgot-password">نسيت كلمة المرور؟</a> --}}
                </div>

                <button type="submit" class="login-btn">
                    تسجيل الدخول <i class="fas fa-sign-in-alt mr-2"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');

            passwordToggle.addEventListener('click', function() {
                // Toggle password visibility
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordToggle.classList.remove('fa-eye-slash');
                    passwordToggle.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    passwordToggle.classList.remove('fa-eye');
                    passwordToggle.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
</body>

</html>
