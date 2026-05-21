@extends('site.layout')
@section('title', 'Brand & Produk – '.setting('site_title'))
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
    @foreach($brands as $b)
      <a href="{{ route('site.brand', $b->slug) }}" class="brand-card" style="text-decoration:none;">
        <div class="brand-card-logo">@if($b->logo)<img src="{{ $b->logo_url }}" alt="{{ $b->name }}"/>@endif</div>
        <div class="brand-card-body">
          <h3>{{ $b->name }}</h3>
          <p>{{ $b->tagline }}</p>
          <span class="brand-card-meta">{{ $b->active_categories_count }} kategori</span>
        </div>
      </a>
    @endforeach
  </div>
</section>
@endsection
