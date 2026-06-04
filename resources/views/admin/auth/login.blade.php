<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Diskominfo Sanggau</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-left h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .login-left p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-right {
            padding: 60px 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }

        .login-header h2 {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #c6f6d5;
            color: #2f855a;
            border: 1px solid #9ae6b4;
        }

        .alert-danger {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h1>🏛️ Diskominfo Kabupaten Sanggau</h1>
            <p>Sistem Informasi Manajemen Konten Website Resmi Dinas Komunikasi dan Informatika Kabupaten Sanggau.</p>
            <p style="margin-top: 1rem; font-size: 0.875rem; opacity: 0.8;">
                Silakan login dengan akun yang telah diberikan oleh administrator untuk mengakses panel manajemen konten.
            </p>
        </div>

        <div class="login-right">
            <div class="login-header">
                <img src="{{ asset('images/logo-sanggau.png') }}" alt="Logo Sanggau" onerror="this.style.display='none'">
                <h2>Login Admin</h2>
                <p>Masuk ke Panel Manajemen</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        value="{{ old('email') }}"
                        placeholder="email@example.com"
                        required 
                        autofocus
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Masukkan password Anda"
                        required
                    >
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" style="margin: 0; font-weight: normal;">Ingat Saya</label>
                </div>

                <button type="submit" class="btn-login">
                    Masuk
                </button>
            </form>

            <p style="text-align: center; margin-top: 2rem; color: #666; font-size: 0.875rem;">
                © 2026 Diskominfo Kabupaten Sanggau. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
