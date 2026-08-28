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
  @if(setting('site_favicon'))
    <link rel="icon" type="image/png" sizes="48x48" href="{{ media_url(setting('site_favicon')) }}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ media_url(setting('site_favicon')) }}"/>
    <link rel="shortcut icon" href="{{ media_url(setting('site_favicon')) }}"/>
    <link rel="apple-touch-icon" href="{{ media_url(setting('site_favicon')) }}"/>
  @endif

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
    /* Nav safety: desktop tetap horizontal, mobile full hamburger */
    .nav-center { flex-wrap: nowrap; }
    #mainNav .nav-right { gap: 12px; }
    @media(max-width:860px){
      #mainNav .nav-center{display:none!important;}
      #mainNav .nav-right{gap:8px;}
    }
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
      <li><a href="{{ route('site.partnership') }}">Kemitraan</a></li>
      <li><a href="{{ route('site.news') }}">News & Update</a></li>
      <li><a href="{{ route('home') }}#keunggulan-s">Keunggulan</a></li>
      <li><a href="{{ route('home') }}#kontak-s">Kontak</a></li>
      <li><a href="#" onclick="openCS(event)" class="nav-cta"><i class="fab fa-whatsapp"></i> Hubungi Kami</a></li>
    </ul>
    <button class="nav-search-pill" onclick="openSearch()"><i class="fas fa-search"></i> <span class="nav-search-pill-text">Cari Produk</span></button>
    <button type="button" class="hamburger" id="navToggle" aria-label="Buka menu" aria-controls="navDrawer" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Mobile drawer -->
<div class="nav-drawer" id="navDrawer">
  <a href="{{ route('home') }}#about-s">Tentang</a>
  <a href="{{ route('site.brands') }}">Produk & Brand</a>
  <a href="{{ route('site.partnership') }}">Kemitraan</a>
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
        <a href="#" onclick="openCS(event)" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        @if(setting('contact_tokopedia'))<a href="{{ setting('contact_tokopedia') }}" target="_blank" title="Tokopedia"><i class="fas fa-store"></i></a>@endif
      </div>
    </div>
    <div class="footer-col">
      <h4>Navigasi</h4>
      <ul class="footer-links">
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('home') }}#about-s">Tentang Kami</a></li>
        <li><a href="{{ route('site.brands') }}">Produk & Brand</a></li>
        <li><a href="{{ route('site.partnership') }}">Kemitraan</a></li>
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
        <li><a href="#" onclick="openCS(event)">{{ setting('contact_whatsapp_display') }}</a></li>
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
  <div class="search-close-bar">
    <button type="button" class="search-close-btn" onclick="closeSearch()">
      <i class="fas fa-times"></i> Tutup (Esc)
    </button>
  </div>
  <div class="search-box">
    <form action="{{ route('site.search') }}" method="GET">
      <input class="search-input" id="searchInput" type="text" name="q" placeholder="Cari produk, brand, kategori..." autocomplete="off" oninput="liveSearch(this.value)">
    </form>
    <div class="search-hint">
      <kbd>Enter</kbd> lihat semua hasil &nbsp;·&nbsp; <kbd>Esc</kbd> tutup
    </div>
  </div>
  <div class="search-results-wrap">
    <div id="liveResults"></div>
  </div>
</div>

<style>
  /* WA float + search overlay (komponen tambahan multi-page) */
  .wa-float{position:fixed;bottom:24px;right:24px;width:56px;height:56px;background:#25D366;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;box-shadow:0 6px 20px rgba(37,211,102,.45);z-index:900;text-decoration:none;transition:transform .2s;}
  .wa-float:hover{transform:scale(1.1);color:#fff;}
  .search-overlay{position:fixed;inset:0;z-index:2000;background:rgba(17,17,17,.9);backdrop-filter:blur(10px);display:none;flex-direction:column;align-items:center;padding-top:80px;}
  .search-overlay.open{display:flex;}
  .search-close-bar{width:min(680px,90%);display:flex;justify-content:flex-end;margin-bottom:16px;}
  .search-close-btn{display:flex;align-items:center;gap:6px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.6);font-family:'Barlow Condensed',sans-serif;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:7px 14px;cursor:pointer;border-radius:3px;transition:all .2s;}
  .search-close-btn:hover{background:var(--red);border-color:var(--red);color:#fff;}
  .search-box{width:min(680px,90%);position:relative;}
  .search-box form{margin:0;}
  .search-input{width:100%;box-sizing:border-box;padding:18px 24px;font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:600;letter-spacing:.5px;border:none;border-bottom:3px solid var(--red);background:rgba(255,255,255,.06);color:#fff;outline:none;}
  .search-input::placeholder{color:rgba(255,255,255,.3);}
  .search-hint{margin-top:12px;font-size:12px;color:rgba(255,255,255,.28);letter-spacing:.5px;display:flex;align-items:center;gap:8px;}
  .search-hint kbd{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:3px;padding:1px 6px;font-size:11px;font-family:monospace;color:rgba(255,255,255,.5);}
  .search-results-wrap{width:min(680px,90%);margin-top:28px;max-height:55vh;overflow-y:auto;}
  .search-results-wrap::-webkit-scrollbar{width:4px;}
  .search-results-wrap::-webkit-scrollbar-thumb{background:var(--red);border-radius:2px;}
  .sr-count{font-size:12px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:14px;}
  .sr-item{display:flex;align-items:center;gap:16px;padding:14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);margin-bottom:8px;cursor:pointer;transition:background .2s;border-radius:2px;text-decoration:none;}
  .sr-item:hover{background:rgba(255,255,255,.12);border-color:var(--red);}
  .sr-img{width:56px;height:56px;object-fit:cover;flex-shrink:0;background:rgba(255,255,255,.08);border-radius:2px;}
  .sr-body{flex:1;min-width:0;}
  .sr-brand{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--red);margin-bottom:3px;}
  .sr-name{font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:700;text-transform:uppercase;color:#fff;line-height:1.1;margin-bottom:3px;}
  .sr-desc{font-size:12px;color:rgba(255,255,255,.45);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
  .sr-arrow{color:rgba(255,255,255,.4);font-size:14px;flex-shrink:0;}
  .sr-empty{text-align:center;color:rgba(255,255,255,.35);font-size:15px;padding:32px 0;}
</style>

<script>
  // Nav scroll effect
  window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);
  });

  // Mobile navbar drawer
  const navToggle = document.getElementById('navToggle');
  const navDrawer = document.getElementById('navDrawer');
  function closeNavDrawer(){
    if (!navToggle || !navDrawer) return;
    navDrawer.classList.remove('open');
    navToggle.classList.remove('open');
    navToggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('nav-open');
    const searchOverlay = document.getElementById('searchOverlay');
    if (!searchOverlay || !searchOverlay.classList.contains('open')) {
      document.body.style.overflow = '';
    }
  }
  if (navToggle && navDrawer) {
    navToggle.addEventListener('click', () => {
      const isOpen = navDrawer.classList.toggle('open');
      navToggle.classList.toggle('open', isOpen);
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      document.body.classList.toggle('nav-open', isOpen);
    });
    navDrawer.querySelectorAll('a').forEach(link => link.addEventListener('click', closeNavDrawer));
    window.addEventListener('resize', () => { if (window.innerWidth > 860) closeNavDrawer(); });
  }
  // Search overlay
  function openSearch(){
    document.getElementById('searchOverlay').classList.add('open');
    setTimeout(() => document.getElementById('searchInput').focus(), 50);
    document.body.style.overflow = 'hidden';
  }
  function closeSearch(){
    document.getElementById('searchOverlay').classList.remove('open');
    document.body.style.overflow = '';
    const inp = document.getElementById('searchInput');
    if (inp) inp.value = '';
    document.getElementById('liveResults').innerHTML = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSearch(); });

  const _liveSearchTimers = {};
  function liveSearch(q){
    runLiveSearch(q, document.getElementById('liveResults'), 'overlay');
  }
  function homeLiveSearch(q){
    runLiveSearch(q, document.getElementById('homeLiveResults'), 'home');
  }
  function runLiveSearch(q, box, timerKey){
    clearTimeout(_liveSearchTimers[timerKey]);
    if (!box) return;
    q = (q || '').trim();
    if (q.length < 2){
      box.innerHTML = '';
      box.classList.remove('open');
      return;
    }
    _liveSearchTimers[timerKey] = setTimeout(async () => {
      try {
        const res = await fetch('{{ route("site.search.live") }}?q=' + encodeURIComponent(q));
        if (!res.ok) throw new Error();
        const data = await res.json();
        if (!data.count){
          box.innerHTML = '<div class="sr-empty">Produk tidak ditemukan untuk "' + escHtml(q) + '"</div>';
          box.classList.add('open');
          return;
        }
        let html = '<div class="sr-count">' + data.count + ' Produk Ditemukan</div>';
        data.items.forEach(it => {
          const meta = [it.brand, it.category].filter(Boolean).join(' · ');
          const img = it.image
            ? '<img class="sr-img" src="' + it.image + '" alt="" loading="lazy">'
            : '<div class="sr-img"></div>';
          html += '<a class="sr-item" href="' + it.url + '">' +
                    img +
                    '<div class="sr-body">' +
                      (meta ? '<div class="sr-brand">' + escHtml(meta) + '</div>' : '') +
                      '<div class="sr-name">' + escHtml(it.name) + '</div>' +
                      (it.desc ? '<div class="sr-desc">' + escHtml(it.desc) + '</div>' : '') +
                    '</div>' +
                    '<i class="fas fa-arrow-right sr-arrow"></i>' +
                  '</a>';
        });
        box.innerHTML = html;
        box.classList.add('open');
      } catch {
        box.innerHTML = '';
        box.classList.remove('open');
      }
    }, 250);
  }
  function escHtml(s){
    return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

</script>
<script>
// CS Round-Robin
// openCS(event)               -> pesan default (greeting agent)
// openCS(event, "pesan ...")  -> pesan kontekstual + nomor CS round-robin
async function openCS(e, customMessage) {
    e.preventDefault();
    const fallbackNum = '{{ preg_replace("/[^0-9]/", "", setting("contact_whatsapp", "6281234567890")) }}';
    const buildLink = (num, text) =>
        'https://wa.me/' + num.replace(/[^0-9]/g, '') + (text ? '?text=' + encodeURIComponent(text) : '');
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
        // ada pesan kontekstual -> pakai nomor CS round-robin + pesan tsb
        // tidak ada -> pakai wa_link bawaan agent (greeting)
        const link = customMessage ? buildLink(cs.display || cs.number || '', customMessage) : cs.wa_link;
        window.open(link, '_blank');
    } catch {
        // fallback ke nomor utama dari settings
        const text = customMessage || 'Halo, saya ingin bertanya tentang produk Multi Plastik';
        window.open(buildLink(fallbackNum, text), '_blank');
    }
}
</script>
@stack('scripts')
</body>
</html>
