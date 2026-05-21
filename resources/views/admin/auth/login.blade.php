<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login – CMS</title>
    @php
        $favicon = \App\Models\SiteSetting::get('site_favicon');
        $logo = \App\Models\SiteSetting::get('site_logo');
        $title = \App\Models\SiteSetting::get('site_title', 'Multi Plastik');
    @endphp
    @if ($favicon)
        <link rel="icon" href="{{ str_starts_with($favicon, 'http') ? $favicon : asset($favicon) }}" />
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #0f1117;
        }

        .login-left {
            background: linear-gradient(135deg, #0f1117 0%, #1a0508 60%, #2d0a0d 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(192, 39, 45, .2) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .login-left-logo {
            max-height: 64px;
            max-width: 200px;
            object-fit: contain;
            margin-bottom: 32px;
            position: relative;
        }

        .login-left-brand {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 12px;
            position: relative;
        }

        .login-left-brand span {
            color: #C0272D;
        }

        .login-left-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, .4);
            text-align: center;
            position: relative;
        }

        .login-right {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-box h1 {
            font-size: 22px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
            letter-spacing: -.4px;
        }

        .login-box p {
            font-size: 13.5px;
            color: #9ca3af;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #111;
            transition: all .15s;
            outline: none;
        }

        .form-control:focus {
            border-color: #C0272D;
            box-shadow: 0 0 0 3px rgba(192, 39, 45, .1);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-check input {
            accent-color: #C0272D;
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .form-check label {
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: #C0272D;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all .15s;
            letter-spacing: -.1px;
        }

        .btn-login:hover {
            background: #9B1C21;
            box-shadow: 0 4px 16px rgba(192, 39, 45, .35);
        }

        .error-box {
            background: #fff1f2;
            color: #9f1239;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }

            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>

    <div class="login-left">
        @if ($logo)
            <img src="{{ str_starts_with($logo, 'http') ? $logo : asset($logo) }}" class="login-left-logo"
                alt="{{ $title }}">
        @endif
        <div class="login-left-brand">{{ explode(' ', $title)[0] ?? 'Multi' }}
            <span>{{ explode(' ', $title)[1] ?? 'Plastik' }}</span></div>
        <div class="login-left-sub">Content Management System</div>
    </div>

    <div class="login-right">
        <div class="login-box">
            <h1>Selamat datang</h1>
            <p>Masuk ke panel admin CMS</p>

            @if ($errors->any())
                <div class="error-box"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="admin@email.com" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn-login">Masuk <i class="fas fa-arrow-right ms-1"></i></button>
            </form>
        </div>
    </div>

</body>

</html>
