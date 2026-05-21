<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') – CMS</title>
@php $favicon = \App\Models\SiteSetting::get('site_favicon'); @endphp
@if($favicon)<link rel="icon" href="{{ str_starts_with($favicon,'http') ? $favicon : asset($favicon) }}"/>@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
    --red: #C0272D;
    --red-dark: #9B1C21;
    --red-soft: rgba(192,39,45,.08);
    --sidebar-bg: #0f1117;
    --sidebar-border: rgba(255,255,255,.06);
    --sidebar-text: #8b8fa8;
    --sidebar-hover: rgba(255,255,255,.05);
    --sidebar-active: rgba(192,39,45,.15);
    --body-bg: #f5f6fa;
    --card-bg: #fff;
    --radius: 10px;
}
* { box-sizing: border-box; }
body {
    font-family: 'Inter', -apple-system, sans-serif;
    background: var(--body-bg);
    color: #1a1d23;
    font-size: 14px;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0; left: 0;
    overflow-y: auto;
    z-index: 100;
    display: flex;
    flex-direction: column;
}
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }

.sidebar-logo {
    padding: 20px 20px 16px;
    border-bottom: 1px solid var(--sidebar-border);
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}
.sidebar-logo img {
    height: 28px;
    width: 28px;
    object-fit: contain;
    border-radius: 4px;
}
.sidebar-logo-text {
    font-weight: 700;
    font-size: 15px;
    color: #fff;
    letter-spacing: -.3px;
}
.sidebar-logo-text span { color: var(--red); }

.nav-section {
    padding: 20px 20px 6px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #3d4060;
}
.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 20px;
    color: var(--sidebar-text);
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 450;
    border-left: 3px solid transparent;
    transition: all .15s;
    border-radius: 0;
}
.sidebar-nav a i {
    width: 17px;
    font-size: 13px;
    text-align: center;
    opacity: .7;
}
.sidebar-nav a:hover {
    background: var(--sidebar-hover);
    color: #e8e9f0;
}
.sidebar-nav a.active {
    background: var(--sidebar-active);
    color: #fff;
    border-left-color: var(--red);
    font-weight: 500;
}
.sidebar-nav a.active i { opacity: 1; color: var(--red); }

.sidebar-footer {
    margin-top: auto;
    padding: 12px 0;
    border-top: 1px solid var(--sidebar-border);
}

/* ===== MAIN ===== */
.main {
    margin-left: 250px;
    padding: 20px 24px;
    min-height: 100vh;
}

/* ===== TOPBAR ===== */
.topbar {
    background: var(--card-bg);
    padding: 13px 20px;
    border-radius: var(--radius);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    border: 1px solid rgba(0,0,0,.05);
}
.topbar-title {
    font-size: 15px;
    font-weight: 600;
    color: #1a1d23;
}
.topbar-user {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6b7280;
    background: var(--body-bg);
    padding: 6px 12px;
    border-radius: 20px;
}
.topbar-user i { color: var(--red); }

/* ===== CARDS ===== */
.card {
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    border-radius: var(--radius);
    background: var(--card-bg);
}

/* ===== BUTTONS ===== */
.btn {
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 13px;
    border-radius: 7px;
    transition: all .15s;
    letter-spacing: -.1px;
}
.btn-primary {
    background: var(--red);
    border-color: var(--red);
    color: #fff;
}
.btn-primary:hover, .btn-primary:focus {
    background: var(--red-dark);
    border-color: var(--red-dark);
    color: #fff;
    box-shadow: 0 4px 12px rgba(192,39,45,.3);
}
.btn-outline-primary {
    color: var(--red);
    border-color: var(--red);
}
.btn-outline-primary:hover {
    background: var(--red);
    border-color: var(--red);
    color: #fff;
}
.btn-outline-danger { color: #dc3545; border-color: #dc3545; }
.btn-outline-danger:hover { background: #dc3545; color: #fff; }
.btn-outline-secondary { color: #6b7280; border-color: #d1d5db; }
.btn-outline-secondary:hover { background: #f3f4f6; color: #374151; border-color: #d1d5db; }
.btn-sm { padding: 5px 12px; font-size: 12px; }

/* ===== STAT CARDS ===== */
.stat-card {
    background: var(--card-bg);
    padding: 20px;
    border-radius: var(--radius);
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.stat-card .label {
    font-size: 11px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .8px;
}
.stat-card .value {
    font-size: 26px;
    font-weight: 700;
    color: #1a1d23;
    margin-top: 6px;
    letter-spacing: -.5px;
}

/* ===== TABLE ===== */
.table { font-size: 13.5px; }
.table thead th {
    background: #f9fafb;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #9ca3af;
    border-bottom: 1px solid #f0f0f0;
    padding: 12px 16px;
}
.table td { padding: 12px 16px; vertical-align: middle; color: #374151; }
.table tbody tr:hover { background: #fafafa; }

/* ===== BADGES ===== */
.badge-soft {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .2px;
}
.badge-soft.success { background: #ecfdf5; color: #059669; }
.badge-soft.danger  { background: #fff1f2; color: #e11d48; }

/* ===== FORM ===== */
.form-control, .form-select {
    font-family: 'Inter', sans-serif;
    font-size: 13.5px;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    padding: 8px 12px;
    color: #1a1d23;
    transition: all .15s;
}
.form-control:focus, .form-select:focus {
    border-color: var(--red);
    box-shadow: 0 0 0 3px rgba(192,39,45,.1);
}
.form-label { font-size: 12.5px; font-weight: 500; color: #374151; margin-bottom: 5px; }

/* ===== ALERTS ===== */
.alert { font-size: 13.5px; border-radius: var(--radius); border: none; }
.alert-success { background: #ecfdf5; color: #065f46; }
.alert-danger  { background: #fff1f2; color: #9f1239; }
.alert-info    { background: #eff6ff; color: #1e40af; }

/* ===== MISC ===== */
.text-primary { color: var(--red) !important; }
code { background: #f3f4f6; color: #c0392b; padding: 2px 6px; border-radius: 4px; font-size: 12px; }

@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); transition: .25s; }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; padding: 16px; }
}
</style>
@stack('styles')
</head>
<body>

<div class="sidebar" id="sidebar">
    {{-- Logo Sidebar --}}
    @php
        $siteFavicon = \App\Models\SiteSetting::get('site_favicon');
        $siteLogo    = \App\Models\SiteSetting::get('site_logo');
        $faviconUrl  = $siteFavicon ? (str_starts_with($siteFavicon,'http') ? $siteFavicon : asset($siteFavicon)) : null;
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        @if($faviconUrl)
            <img src="{{ $faviconUrl }}" alt="Logo">
        @endif
        <div class="sidebar-logo-text">Multi <span>Plastik</span></div>
    </a>

    <div class="sidebar-nav">
        <div class="nav-section">Dashboard</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Analytics</a>

        <div class="nav-section">Konten</div>
        <a href="{{ route('admin.slides.index') }}" class="{{ request()->routeIs('admin.slides.*') ? 'active' : '' }}"><i class="fas fa-images"></i> Hero Slider</a>
        <a href="{{ route('admin.promos.index') }}" class="{{ request()->routeIs('admin.promos.*') ? 'active' : '' }}"><i class="fas fa-bullhorn"></i> Promo Bar</a>
        <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> News & Artikel</a>

        <div class="nav-section">Katalog</div>
        <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Brand</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Kategori</a>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="fas fa-box-open"></i> Produk</a>

        <div class="nav-section">Pengaturan</div>
        <a href="{{ route('admin.settings.index', 'general') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-sliders-h"></i> Site Settings</a>
        <a href="{{ route('admin.cs.index') }}" class="{{ request()->routeIs('admin.cs.*') ? 'active' : '' }}"><i class="fas fa-headset"></i> CS WhatsApp</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users-cog"></i> User Admin</a>
        <a href="{{ route('admin.artisan.index') }}" class="{{ request()->routeIs('admin.artisan.*') ? 'active' : '' }}"><i class="fas fa-terminal"></i> Artisan Console</a>
    </div>

    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" style="color:#3d4060;text-decoration:none;display:flex;align-items:center;gap:10px;padding:9px 20px;font-size:13px;"><i class="fas fa-external-link-alt" style="width:17px;font-size:12px;"></i> Lihat Website</a>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" style="all:unset;cursor:pointer;width:100%;color:#3d4060;display:flex;align-items:center;gap:10px;padding:9px 20px;font-size:13px;">
                <i class="fas fa-sign-out-alt" style="width:17px;font-size:12px;"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
        </div>
        <div class="topbar-user">
            <i class="fas fa-user-circle"></i>
            {{ auth()->user()->name ?? 'Admin' }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <strong><i class="fas fa-exclamation-circle me-1"></i>Ada kesalahan:</strong>
            <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
