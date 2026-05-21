<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login Admin – Multi Plastik</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
    body{background:linear-gradient(135deg,#1a1a1a,#C0272D);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:system-ui;}
    .login-box{background:#fff;border-radius:12px;padding:36px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
    .login-box h1{font-size:22px;font-weight:800;margin-bottom:6px;color:#222;}
    .btn-red{background:#C0272D;color:#fff;border:none;}
    .btn-red:hover{background:#9B1C21;color:#fff;}
</style>
</head>
<body>
<div class="login-box">
    <h1>Multi Plastik CMS</h1>
    <p class="text-muted mb-4">Login untuk masuk panel admin</p>
    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
        @endif
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}" autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-red w-100">Login</button>
    </form>
</div>
</body>
</html>
