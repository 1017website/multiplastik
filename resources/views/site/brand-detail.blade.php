@extends('site.layout')
@section('title', $brand->name.' – '.setting('site_title'))
@section('meta_description', $brand->tagline)

@section('content')
<div class="bc">
  <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <a href="{{ route('site.brands') }}">Brand</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <span>{{ $brand->name }}</span>
</div>

<section style="padding:56px 8% 40px;">
  <div class="brand-hero">
    @if($brand->logo)<img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="brand-hero-logo"/>@endif
    <div>
      <div class="sec-label">Brand</div>
      <h1 class="sec-title">{{ $brand->name }}</h1>
      <div class="sec-div"></div>
      <p style="max-width:600px;color:var(--g600);line-height:1.7;">{{ $brand->description }}</p>
    </div>
  </div>
</section>

<section style="padding:0 8% 72px;">
  <h2 style="font-family:'Barlow Condensed';font-weight:800;font-size:24px;text-transform:uppercase;margin-bottom:24px;">Kategori Produk</h2>
  <div class="cat-grid">
    @forelse($brand->activeCategories as $c)
      <a href="{{ route('site.category', [$brand->slug, $c->slug]) }}" class="cat-card" style="text-decoration:none;">
        <div class="cat-card-img">
          @if($c->image)<img src="{{ $c->image_url }}" alt="{{ $c->name }}"/>
          @elseif($c->icon)<i class="{{ $c->icon }}"></i>@endif
        </div>
        <div class="cat-card-body">
          <h3>{{ $c->name }}</h3>
          <p>{{ $c->description }}</p>
          <span class="cat-card-meta">{{ $c->active_products_count }} produk <i class="fas fa-arrow-right"></i></span>
        </div>
      </a>
    @empty
      <p class="text-muted">Belum ada kategori untuk brand ini.</p>
    @endforelse
  </div>
</section>
@endsection
