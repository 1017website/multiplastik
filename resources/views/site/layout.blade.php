<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', setting('site_title', 'Multi Plastik'))</title>
  <meta name="description" content="@yield('meta_description', setting('site_description'))"/>
  <meta name="keywords" content="{{ setting('site_keywords') }}"/>
@php
    $seoRobots     = setting('seo_robots', 'index, follow');
    $seoTwitter    = setting('seo_twitter_card');
    $seoTwitterSite= setting('seo_twitter_site');
    $seoGVerify    = setting('seo_google_verify');
    $seoBingVerify = setting('seo_bing_verify');
    $seoOgImage    = setting('seo_og_image') ?: setting('og_image');
@endphp
  <meta name="robots" content="{{ $seoRobots }}"/>
  @if($seoTwitter)
  <meta name="twitter:card" content="{{ $seoTwitter }}"/>
  <meta name="twitter:title" content="@yield('title', setting('site_title'))"/>
  <meta name="twitter:description" content="@yield('meta_description', setting('site_description'))"/>
  @if($seoOgImage)<meta name="twitter:image" content="{{ media_url($seoOgImage) }}"/>@endif
  @if($seoTwitterSite)<meta name="twitter:site" content="{{ $seoTwitterSite }}"/>@endif
  @endif
  @if($seoGVerify)<meta name="google-site-verification" content="{{ $seoGVerify }}"/>@endif
  @if($seoBingVerify)<meta name="msvalidate.01" content="{{ $seoBingVerify }}"/>@endif
  <link rel="canonical" href="{{ url()->current() }}"/>
  <meta property="og:type" content="website"/>
  <meta property="og:title" content="@yield('title', setting('site_title'))"/>
  <meta property="og:description" content="@yield('meta_description', setting('site_description'))"/>
  @if(setting('og_image'))<meta property="og:image" content="{{ media_url(setting('og_image')) }}"/>@endif
  @if(setting('site_favicon'))<link rel="icon" href="{{ media_url(setting('site_favicon')) }}"/>@endif

    @php
        $ldData = ['@context'=>'https://schema.org','@type'=>'Organization','name'=>setting('site_title'),'url'=>url('/')];
        if(setting('site_logo')) $ldData['logo'] = media_url(setting('site_logo'));
    @endphp
    <script type="application/ld+json">{!! json_encode($ldData) !!}</script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="{{ asset('css/site.css') }}"/>
  <link rel="stylesheet" href="{{ asset('css/site-extra.css') }}"/>
  <style>
    /* Nav fix: pastikan nav-center tidak wrap */
    .nav-center { flex-wrap: nowrap; }
    #mainNav .nav-right { gap: 12px; }
  </style>
  <script src="https://elfsightcdn.com/platform.js" defer></script>

  @include('site.partials.tracking-head')
  @stack('styles')
</head>
<body>
@include('site.partials.tracking-body')

<!-- ==================== NAV ==================== -->
<nav id="mainNav">
  <a class="nav-logo" href="{{ route('home') }}" style="text-decoration:none;">
    @if(setting('site_logo'))
      <img src="{{ media_url(setting('site_logo')) }}" alt="{{ setting('site_title') }}"/>
    @else
      <strong style="color:var(--red);font-family:'Barlow Condensed';font-size:22px;">MULTI PLASTIK</strong>
    @endif
  </a>

  <div class="nav-right">
    <ul class="nav-center">
      <li><a href="{{ route('home') }}#about-s">Tentang</a></li>
      <li><a href="{{ route('site.brands') }}">Produk & Brand</a></li>
      <li><a href="{{ route('site.news') }}">News & Update</a></li>
      <li><a href="{{ route('home') }}#keunggulan-s">Keunggulan</a></li>
      <li><a href="{{ route('home') }}#kontak-s">Kontak</a></li>
      <li><a href="#" onclick="openCS(event)" class="nav-cta"><i class="fab fa-whatsapp"></i> Hubungi Kami</a></li>
    </ul>
    <button class="nav-search-pill" onclick="openSearch()"><i class="fas fa-search"></i> <span class="nav-search-pill-text">Cari Produk</span></button>
    <div class="hamburger" onclick="document.getElementById('navDrawer').classList.toggle('open')">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- Mobile drawer -->
<div class="nav-drawer" id="navDrawer">
  <a href="{{ route('home') }}#about-s">Tentang</a>
  <a href="{{ route('site.brands') }}">Produk & Brand</a>
  <a href="{{ route('site.news') }}">News & Update</a>
  <a href="{{ route('home') }}#keunggulan-s">Keunggulan</a>
  <a href="{{ route('home') }}#kontak-s">Kontak</a>
  <a onclick="openSearch();document.getElementById('navDrawer').classList.remove('open')"><i class="fas fa-search"></i> Cari Produk</a>
  <a href="#" onclick="openCS(event)" class="drawer-cta"><i class="fab fa-whatsapp"></i> Hubungi Kami</a>
</div>

@yield('content')

<!-- ==================== FOOTER ==================== -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      @if(setting('site_logo'))<img src="{{ media_url(setting('site_logo')) }}" alt="{{ setting('site_title') }}"/>@endif
      <p>{{ setting('footer_about') }}</p>
      <div class="footer-social">
        @if(setting('contact_instagram'))<a href="{{ setting('contact_instagram') }}" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>@endif
        @if(setting('contact_facebook'))<a href="{{ setting('contact_facebook') }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
        <a href="{{ wa_link() }}" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        @if(setting('contact_tokopedia'))<a href="{{ setting('contact_tokopedia') }}" target="_blank" title="Tokopedia"><i class="fas fa-store"></i></a>@endif
      </div>
    </div>
    <div class="footer-col">
      <h4>Navigasi</h4>
      <ul class="footer-links">
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('home') }}#about-s">Tentang Kami</a></li>
        <li><a href="{{ route('site.brands') }}">Produk & Brand</a></li>
        <li><a href="{{ route('site.news') }}">News & Update</a></li>
        <li><a href="{{ route('home') }}#kontak-s">Kontak</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Brand Kami</h4>
      <ul class="footer-links">
        @foreach($siteNavBrands as $b)
          <li><a href="{{ route('site.brand', $b->slug) }}">{{ $b->name }}</a></li>
        @endforeach
      </ul>
    </div>
    <div class="footer-col">
      <h4>Kontak</h4>
      <ul class="footer-links">
        <li><a href="{{ wa_link() }}" target="_blank">{{ setting('contact_whatsapp_display') }}</a></li>
        <li><a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></li>
        <li><a>{{ setting('contact_address') }}</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bot">
    <p>{{ setting('copyright_text', '© '.date('Y').' Multi Plastik') }}</p>
    <p>Website by <span>1017Studios</span></p>
  </div>
</footer>

<!-- ==================== WA FLOAT ==================== -->
<a href="#" id="waFloatBtn" class="wa-float" title="Chat WhatsApp" onclick="openCS(event)">
  <i class="fab fa-whatsapp"></i>
</a>

<!-- ==================== SEARCH OVERLAY ==================== -->
<div class="search-overlay" id="searchOverlay">
  <div class="search-box">
    <form action="{{ route('site.search') }}" method="GET">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" id="searchInput" class="search-input" placeholder="Cari produk: gelas, tusuk, sendok, styrofoam..." autocomplete="off">
        <button type="button" class="search-close" onclick="closeSearch()"><i class="fas fa-times"></i></button>
      </div>
    </form>
    <div class="search-hint">Tekan Enter untuk mencari • ESC untuk menutup</div>
  </div>
</div>

<style>
  /* WA float + search overlay (komponen tambahan multi-page) */
  .wa-float{position:fixed;bottom:24px;right:24px;width:56px;height:56px;background:#25D366;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;box-shadow:0 6px 20px rgba(37,211,102,.45);z-index:900;text-decoration:none;transition:transform .2s;}
  .wa-float:hover{transform:scale(1.1);color:#fff;}
  .search-overlay{position:fixed;inset:0;background:rgba(17,17,17,.96);z-index:2000;display:none;align-items:flex-start;justify-content:center;padding-top:14vh;}
  .search-overlay.open{display:flex;}
  .search-box{width:90%;max-width:680px;}
  .search-input-wrap{display:flex;align-items:center;gap:14px;background:#fff;border-radius:8px;padding:18px 22px;}
  .search-input-wrap > i{color:var(--red);font-size:20px;}
  .search-input{flex:1;border:none;outline:none;font-size:18px;font-family:'Barlow',sans-serif;}
  .search-close{background:none;border:none;color:var(--g400);font-size:20px;cursor:pointer;}
  .search-hint{color:rgba(255,255,255,.5);font-size:13px;text-align:center;margin-top:16px;}
</style>

<script>
  // Nav scroll effect
  window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);
  });
  // Search overlay
  function openSearch(){
    document.getElementById('searchOverlay').classList.add('open');
    setTimeout(() => document.getElementById('searchInput').focus(), 50);
    document.body.style.overflow = 'hidden';
  }
  function closeSearch(){
    document.getElementById('searchOverlay').classList.remove('open');
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSearch(); });
</script>
<script>
// CS Round-Robin
async function openCS(e) {
    e.preventDefault();
    try {
        const res = await fetch('{{ route("cs.next") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ page: window.location.pathname })
        });
        if (!res.ok) throw new Error();
        const cs = await res.json();
        window.open(cs.wa_link, '_blank');
    } catch {
        // fallback ke nomor utama dari settings
        window.open('{{ wa_link("Halo, saya ingin bertanya tentang produk Multi Plastik") }}', '_blank');
    }
}
</script>
@stack('scripts')
</body>
</html>
