@extends('site.layout')
@section('title', $category->name . ' – ' . $brand->name)
@section('meta_description', $category->description)

@section('content')
    <div class="bc">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <a href="{{ route('site.brands') }}">Brand</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <a href="{{ route('site.brand', $brand->slug) }}">{{ $brand->name }}</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <span>{{ $category->name }}</span>
    </div>

    {{-- Category Hero --}}
    <div class="cat-hero">
        <div class="cat-hero-inner">
            <div class="cat-hero-icon">
                @if ($category->icon)
                <i class="{{ $category->icon }}"></i>@else<i class="fas fa-box-open"></i>
                @endif
            </div>
            <div>
                <div class="cat-hero-name">{{ $category->name }}</div>
                <div class="cat-hero-brand">{{ $brand->name }} · {{ $category->description }}</div>
            </div>
        </div>
    </div>

    {{-- Produk Grid --}}
    <div class="prods-grid">
        @forelse($category->activeProducts as $p)
            <a href="{{ route('site.product', [$brand->slug, $category->slug, $p->slug]) }}" class="pc"
                style="text-decoration:none;">
                @if ($p->image)
                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}" />
                @else
                    <div
                        style="width:100%;height:200px;background:var(--g100);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-box-open" style="font-size:40px;color:var(--g200);"></i>
                    </div>
                @endif
                <div class="pc-body">
                    <div
                        style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--red);margin-bottom:4px;">
                        {{ $brand->name }}</div>
                    <div class="pc-name">{{ $p->name }}</div>
                    <div class="pc-desc">{{ Str::limit($p->description, 72) }}</div>
                </div>
            </a>
        @empty
            <p style="grid-column:1/-1;padding:40px;text-align:center;color:var(--g400);">Belum ada produk di kategori ini.
            </p>
        @endforelse
    </div>
@endsection
