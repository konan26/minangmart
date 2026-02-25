<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Minangmart') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="login-page theme-customer">
    <div class="login-container">
        <div class="role-tabs">
            <div class="role-tab active" data-role="customer" onclick="setRole('customer')">CUSTOMER</div>
            <div class="role-tab" data-role="petugas" onclick="setRole('petugas')">PETUGAS</div>
            <div class="role-tab" data-role="admin" onclick="setRole('admin')">ADMIN</div>
        </div>

        <h1 class="login-title">LOGIN</h1>

        <div class="logo-circle">
            <i class="fa-solid fa-utensils" id="role-icon"></i>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email or Username -->
            <div class="form-group">
                <label for="login">Email / Username</label>
                <input id="login" class="input-pill" type="text" name="login" value="{{ old('login') }}" placeholder="Enter your email" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('login')" class="mt-2" style="color: #f87171; list-style: none; padding: 0; margin-top: 5px; font-size: 0.8rem;" />
            </div>

            <!-- Password -->
            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="password">Password</label>
                <input id="password" class="input-pill" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #f87171; list-style: none; padding: 0; margin-top: 5px; font-size: 0.8rem;" />
                
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                LOGIN
            </button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <div id="register-link-container">
            <p class="registrasi-text">
                not a user yet? <a href="{{ route('register') }}">Registrasi</a>
            </p>
        </div>
    </div>
    <script>
        function setRole(role) {
            document.body.className = 'login-page theme-' + role;
            
            // Update Icon
            const icon = document.getElementById('role-icon');
            if (role === 'customer') icon.className = 'fa-solid fa-utensils';
            else if (role === 'petugas') icon.className = 'fa-solid fa-truck-fast';
            else if (role === 'admin') icon.className = 'fa-solid fa-user-shield';

            // Show/Hide Register Link (Only Customer & Admin can register)
            const registerLink = document.getElementById('register-link-container');
            if (role === 'petugas') {
                registerLink.style.display = 'none';
            } else {
                registerLink.style.display = 'block';
            }

            // Mark active tab
            document.querySelectorAll('.role-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>
