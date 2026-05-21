<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') – Multi Plastik CMS</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root{--red:#C0272D;--red-dark:#9B1C21;}
    body{background:#f4f5f7;font-family:-apple-system,system-ui,sans-serif;}
    .sidebar{width:240px;min-height:100vh;background:#1a1a1a;color:#fff;position:fixed;top:0;left:0;overflow-y:auto;}
    .sidebar .brand{padding:18px 20px;border-bottom:1px solid #333;font-weight:800;font-size:18px;color:var(--red);}
    .sidebar a{color:#bbb;text-decoration:none;padding:11px 20px;display:flex;align-items:center;gap:10px;font-size:14px;border-left:3px solid transparent;transition:.15s;}
    .sidebar a:hover{background:#252525;color:#fff;}
    .sidebar a.active{background:#252525;color:#fff;border-left-color:var(--red);}
    .sidebar a i{width:18px;}
    .sidebar .nav-section{padding:8px 20px;font-size:11px;text-transform:uppercase;color:#666;letter-spacing:1px;margin-top:8px;}
    .main{margin-left:240px;padding:24px;}
    .topbar{background:#fff;padding:14px 20px;border-radius:8px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 1px 3px rgba(0,0,0,.05);}
    .card{border:none;box-shadow:0 1px 3px rgba(0,0,0,.05);border-radius:8px;}
    .btn-primary{background:var(--red);border-color:var(--red);}
    .btn-primary:hover,.btn-primary:focus{background:var(--red-dark);border-color:var(--red-dark);}
    .text-primary{color:var(--red)!important;}
    .stat-card{background:#fff;padding:18px;border-radius:8px;}
    .stat-card .label{font-size:12px;color:#888;text-transform:uppercase;letter-spacing:.5px;}
    .stat-card .value{font-size:28px;font-weight:700;color:#222;margin-top:6px;}
    .table thead th{background:#fafafa;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#666;font-weight:600;}
    .badge-soft{padding:4px 10px;border-radius:4px;font-size:11px;font-weight:600;}
    .badge-soft.success{background:#d4edda;color:#155724;}
    .badge-soft.danger{background:#f8d7da;color:#721c24;}
    @media(max-width:768px){.sidebar{transform:translateX(-100%);transition:.25s;z-index:1000;}.sidebar.open{transform:translateX(0);}.main{margin-left:0;}}
</style>
@stack('styles')
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="brand"><i class="fas fa-box"></i> Multi Plastik</div>

    <div class="nav-section">Dashboard</div>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Analytics</a>

    <div class="nav-section">Konten Halaman</div>
    <a href="{{ route('admin.slides.index') }}" class="{{ request()->routeIs('admin.slides.*') ? 'active' : '' }}"><i class="fas fa-images"></i> Hero Slider</a>
    <a href="{{ route('admin.promos.index') }}" class="{{ request()->routeIs('admin.promos.*') ? 'active' : '' }}"><i class="fas fa-bullhorn"></i> Promo Bar</a>
    <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> News & Artikel</a>

    <div class="nav-section">Katalog</div>
    <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Brand</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Kategori</a>
    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="fas fa-box-open"></i> Produk</a>

    <div class="nav-section">Pengaturan</div>
    <a href="{{ route('admin.settings.index', 'general') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Site Settings</a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users-cog"></i> User Admin</a>

    <div class="nav-section">Lainnya</div>
    <a href="{{ route('home') }}" target="_blank"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
    <form action="{{ route('admin.logout') }}" method="POST">@csrf<button type="submit" style="all:unset;cursor:pointer;width:100%;color:#bbb;padding:11px 20px;display:flex;align-items:center;gap:10px;font-size:14px;border-left:3px solid transparent;"><i class="fas fa-sign-out-alt"></i> Logout</button></form>
</div>

<div class="main">
    <div class="topbar">
        <div>
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
            <strong class="ms-2">@yield('title', 'Admin')</strong>
        </div>
        <div class="text-muted small">
            <i class="fas fa-user-circle"></i> {{ auth()->user()->name ?? 'Admin' }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Ada kesalahan:</strong>
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
