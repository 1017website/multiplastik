@extends('site.layout')
@section('title', $masterCategory->name . ' – ' . setting('site_title'))
@section('meta_description', $masterCategory->description)

@section('content')
    <div class="bc">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <span>Kategori</span>
        <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
        <span>{{ $masterCategory->name }}</span>
    </div>

    <div class="cat-hero">
        <div class="cat-hero-inner">
            <div class="cat-hero-icon">
                <i class="{{ $masterCategory->icon ?: 'fas fa-layer-group' }}"></i>
            </div>
            <div>
                <div class="sec-label">Kategori Produk</div>
                <h1 class="cat-hero-name">{{ $masterCategory->name }}</h1>
                @if ($masterCategory->description)
                    <div class="cat-hero-brand">{{ $masterCategory->description }}</div>
                @endif
            </div>
        </div>
    </div>

    <section style="padding:48px 8% 72px;">
        <h2 style="font-family:'Barlow Condensed';font-weight:800;font-size:22px;text-transform:uppercase;margin-bottom:0;">
            Pilih Kategori &amp; Brand
        </h2>
        <div class="cats-grid">
            @forelse ($masterCategory->activeCategories as $category)
                <a href="{{ route('site.category', [$category->brand->slug, $category->slug]) }}"
                    class="cat-card" style="text-decoration:none;">
                    @if ($category->image)
                        <img class="cat-img" src="{{ $category->image_url }}" alt="{{ $category->name }}" loading="lazy">
                    @else
                        <div class="cat-img" style="display:flex;align-items:center;justify-content:center;">
                            <i class="{{ $category->icon ?: $masterCategory->icon ?: 'fas fa-box-open' }}"
                                style="font-size:40px;color:var(--red);opacity:.4;"></i>
                        </div>
                    @endif
                    <div class="cat-body">
                        <div class="category-brand-label">{{ $category->brand->name }}</div>
                        <div class="cat-name">{{ $category->name }}</div>
                        <div class="cat-desc">{{ $category->description }}</div>
                        <div class="cat-cnt">{{ $category->active_products_count }} Produk</div>
                    </div>
                </a>
            @empty
                <p style="grid-column:1/-1;color:var(--g400);">Belum ada kategori brand yang aktif.</p>
            @endforelse
        </div>
    </section>
@endsection
