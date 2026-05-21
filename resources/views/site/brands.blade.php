@extends('site.layout')
@section('title', 'Brand & Produk – ' . setting('site_title'))
@section('meta_description', 'Daftar semua brand plastik dan kemasan yang kami distribusikan.')

@section('content')
    <div class="bc">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <span>Brand & Produk</span>
    </div>

    <section style="padding:56px 8% 72px;">
        <div class="sec-label">Semua Brand</div>
        <h2 class="sec-title">Brand yang Kami Distribusikan</h2>
        <div class="sec-div"></div>

        <div class="brands-grid" style="margin-top:40px;">
            @foreach ($brands as $b)
                @php
                    $totalProduk = $b->activeCategories->sum(fn($c) => $c->active_products_count ?? 0);
                @endphp
                <a href="{{ route('site.brand', $b->slug) }}" class="brand-card"
                    style="text-decoration:none;display:block;">
                    <div class="brand-arr"><i class="fas fa-arrow-right"></i></div>
                    @if ($b->logo)
                        <img class="brand-logo-img" src="{{ $b->logo_url }}" alt="{{ $b->name }}" />
                    @endif
                    <div class="brand-nm">{{ $b->name }}</div>
                    <div class="brand-tl">{{ $b->tagline }}</div>
                    <div class="brand-cnt">{{ $b->active_categories_count }} Kategori · {{ $totalProduk }} Produk</div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
