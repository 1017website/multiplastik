@extends('site.layout')
@section('title', 'Pencarian: '.$q.' – '.setting('site_title'))

@section('content')
<div class="bc">
  <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <span>Pencarian</span>
</div>

<section style="padding:56px 8% 72px;">
  <div class="sec-label">Hasil Pencarian</div>
  <h1 class="sec-title">"{{ $q }}"</h1>
  <div class="sec-div"></div>
  <p style="color:var(--g600);margin-bottom:36px;">
    {{ $results->count() ? $results->count().' produk ditemukan' : 'Tidak ada produk ditemukan' }}
  </p>

  @if($results->count())
    <div class="products-grid">
      @foreach($results as $p)
        <a href="{{ route('site.product', [$p->category->brand->slug, $p->category->slug, $p->slug]) }}" class="pc" style="text-decoration:none;">
          @if($p->image)<img src="{{ $p->image_url }}" alt="{{ $p->name }}"/>@endif
          <div class="pc-body">
            <div style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--red);margin-bottom:4px;">{{ $p->category->brand->name }}</div>
            <div class="pc-name">{{ $p->name }}</div>
            <div class="pc-desc">{{ Str::limit($p->description, 72) }}</div>
          </div>
        </a>
      @endforeach
    </div>
  @else
    <div style="text-align:center;padding:60px 20px;color:var(--g400);">
      <i class="fas fa-search" style="font-size:48px;margin-bottom:16px;opacity:.4;"></i>
      <p>Coba kata kunci lain, misal: "gelas", "tusuk", "sendok", "styrofoam"</p>
    </div>
  @endif
</section>
@endsection
