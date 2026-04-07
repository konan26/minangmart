<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi - {{ config('app.name', 'Minangmart') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        body.register-page {
            background-color: #0b1a33;
            margin: 0;
            font-family: 'Figtree', sans-serif;
            color: white;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .split-left {
            flex: 1;
            background-image: url('{{ asset('images/registration_food_background.png') }}');
            background-size: cover;
            background-position: center;
            display: none; /* Hidden on mobile */
        }

        @media (min-width: 768px) {
            .split-left {
                display: block;
            }
        }

        .split-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            overflow-y: auto;
        }

        .register-container {
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .logo-p {
            width: 70px;
            height: 70px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            font-family: serif;
        }

        .register-title {
            font-family: serif;
            letter-spacing: 0.3rem;
            font-size: 2rem;
            margin-bottom: 2.5rem;
            text-transform: uppercase;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.2rem;
        }

        .form-group label {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1rem;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-pill-dark {
            width: 100%;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            border: none;
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            cursor: pointer;
        }

        .btn-register {
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            border: none;
            background-color: white;
            color: #0b1a33;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 2rem;
            letter-spacing: 0.2rem;
            text-transform: uppercase;
        }

        .login-text {
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .login-text a {
            color: white;
            text-decoration: none;
            font-weight: 700;
        }

        /* Role Styles for Register */
        .role-tabs { display: flex; justify-content: center; gap: 1rem; }
        .role-tab { 
            padding: 0.6rem 1.2rem; border-radius: 50px; font-size: 0.75rem; font-weight: 800; 
            cursor: pointer; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; 
            background: rgba(255, 255, 255, 0.05);
        }
        
        .theme-customer .role-tab[data-role="customer"] { background: #d4af37; color: #0b1a33; border-color: #d4af37; }
        .theme-customer .logo-p { border-color: #d4af37; color: #d4af37; }
        .theme-customer .btn-register { background: #d4af37; color: #0b1a33; }

        .theme-admin .role-tab[data-role="admin"] { background: #ef4444; color: white; border-color: #ef4444; }
        .theme-admin .logo-p { border-color: #ef4444; color: #ef4444; }
        .theme-admin .btn-register { background: #ef4444; color: white; }
    </style>
</head>
<body class="register-page theme-customer">
    <div class="split-left"></div>
    <div class="split-right">
        <div class="register-container">
            <div class="role-tabs" style="margin-bottom: 2rem;">
                <div class="role-tab active" data-role="customer" onclick="setRegisterRole('customer')">CUSTOMER</div>
                <div class="role-tab" data-role="admin" onclick="setRegisterRole('admin')">ADMIN</div>
            </div>

            <div class="logo-p" id="register-logo">C</div>
            <h1 class="register-title">REGISTRASI</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" id="role-input" value="customer">

                <!-- Full Name -->
                <div class="form-group">
                    <label for="name">FULL NAME</label>
                    <input id="name" class="input-pill-dark" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" style="color: #f87171; font-size: 0.75rem; list-style: none; padding: 0;" />
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone_number">PHONE NUMBER</label>
                    <input id="phone_number" class="input-pill-dark" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="+62 812 3456 7890" required>
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-1" style="color: #f87171; font-size: 0.75rem; list-style: none; padding: 0;" />
                </div>

                <!-- Email / Username -->
                <div class="form-group">
                    <label for="email">EMAIL / USERNAME</label>
                    <input id="email" class="input-pill-dark" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" style="color: #f87171; font-size: 0.75rem; list-style: none; padding: 0;" />
                    
                    <input type="hidden" name="username" id="username_hidden">
                </div>

                @push('scripts')
                <script>
                    document.getElementById('email').addEventListener('input', function() {
                        const email = this.value;
                        if (email.includes('@')) {
                            document.getElementById('username_hidden').value = email.split('@')[0];
                        } else {
                            document.getElementById('username_hidden').value = email;
                        }
                    });
                </script>
                @endpush

                <!-- Password -->
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="password-container">
                        <input id="password" class="input-pill-dark" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                        <i class="fa-regular fa-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" style="color: #f87171; font-size: 0.75rem; list-style: none; padding: 0;" />
                </div>

                <!-- Password Confirmation -->
                <div class="form-group">
                    <label for="password_confirmation">CONFIRM PASSWORD</label>
                    <div class="password-container">
                        <input id="password_confirmation" class="input-pill-dark" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    REGISTER
                </button>
            </form>

            <p class="login-text">
                Sudah punya akun? <a href="{{ route('login') }}">Log In</a>
            </p>
        </div>
    </div>

    <script>
        function setRegisterRole(role) {
            document.body.className = 'register-page theme-' + role;
            document.getElementById('role-input').value = role;
            
            const logo = document.getElementById('register-logo');
            if (role === 'customer') logo.innerText = 'C';
            else if (role === 'admin') logo.innerText = 'A';

            document.querySelectorAll('.role-tab').forEach(tab => tab.classList.remove('active'));
            const clickedTab = document.querySelector(`.role-tab[data-role="${role}"]`);
            if (clickedTab) clickedTab.classList.add('active');
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.password-toggle');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
