@extends('site.layout')
@section('title', $category->name.' – '.$brand->name)
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

<section style="padding:56px 8% 72px;">
  <div class="sec-label">{{ $brand->name }}</div>
  <h1 class="sec-title">{{ $category->name }}</h1>
  <div class="sec-div"></div>
  <p style="max-width:600px;color:var(--g600);line-height:1.7;margin-bottom:36px;">{{ $category->description }}</p>

  <div class="products-grid">
    @forelse($category->activeProducts as $p)
      <a href="{{ route('site.product', [$brand->slug, $category->slug, $p->slug]) }}" class="pc" style="text-decoration:none;">
        @if($p->image)<img src="{{ $p->image_url }}" alt="{{ $p->name }}"/>@endif
        <div class="pc-body">
          <div style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--red);margin-bottom:4px;">{{ $brand->name }}</div>
          <div class="pc-name">{{ $p->name }}</div>
          <div class="pc-desc">{{ Str::limit($p->description, 72) }}</div>
        </div>
      </a>
    @empty
      <p class="text-muted">Belum ada produk di kategori ini.</p>
    @endforelse
  </div>
</section>
@endsection
