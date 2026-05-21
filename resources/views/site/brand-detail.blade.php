@extends('site.layout')
@section('title', $brand->name . ' – ' . setting('site_title'))
@section('meta_description', $brand->tagline)

@section('content')
    <div class="bc">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <a href="{{ route('site.brands') }}">Brand</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <span>{{ $brand->name }}</span>
    </div>

    {{-- Brand Hero --}}
    <div
        style="background:var(--g50);border-bottom:3px solid var(--red);padding:36px 8%;display:flex;align-items:center;gap:24px;">
        @if ($brand->logo)
            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                style="height:64px;object-fit:contain;flex-shrink:0;" />
        @endif
        <div>
            <div class="sec-label">Brand</div>
            <h1
                style="font-family:'Barlow Condensed';font-weight:900;font-size:clamp(28px,4vw,48px);text-transform:uppercase;color:var(--g800);line-height:1.05;">
                {{ $brand->name }}</h1>
            <p style="color:var(--g600);font-size:14px;margin-top:8px;max-width:600px;">{{ $brand->description }}</p>
        </div>
    </div>

    {{-- Kategori Grid --}}
    <section style="padding:48px 8% 72px;">
        <h2 style="font-family:'Barlow Condensed';font-weight:800;font-size:22px;text-transform:uppercase;margin-bottom:0;">
            Kategori Produk {{ $brand->name }}</h2>
        <div class="cats-grid">
            @forelse($brand->activeCategories as $c)
                <a href="{{ route('site.category', [$brand->slug, $c->slug]) }}" class="cat-card"
                    style="text-decoration:none;">
                    @if ($c->image)
                        <img class="cat-img" src="{{ $c->image_url }}" alt="{{ $c->name }}" />
                    @else
                        <div class="cat-img" style="display:flex;align-items:center;justify-content:center;">
                            @if ($c->icon)
                                <i class="{{ $c->icon }}" style="font-size:40px;color:var(--red);opacity:.4;"></i>
                            @endif
                        </div>
                    @endif
                    <div class="cat-body">
                        <div class="cat-name">{{ $c->name }}</div>
                        <div class="cat-desc">{{ $c->description }}</div>
                        <div class="cat-cnt">
                            @if ($c->icon)
                                <i class="{{ $c->icon }}" style="margin-right:6px;"></i>
                            @endif
                            {{ $c->active_products_count }} Produk
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-muted" style="grid-column:1/-1;">Belum ada kategori.</p>
            @endforelse
        </div>
    </section>
@endsection
