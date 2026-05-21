@extends('site.layout')
@section('title', $product->name.' – '.$brand->name)
@section('meta_description', Str::limit($product->description, 155))

@section('content')
<div class="bc">
  <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <a href="{{ route('site.brand', $brand->slug) }}">{{ $brand->name }}</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <a href="{{ route('site.category', [$brand->slug, $category->slug]) }}">{{ $category->name }}</a>
  <span class="bcsep"><i class="fas fa-chevron-right"></i></span>
  <span>{{ $product->name }}</span>
</div>

<section style="padding:56px 8% 72px;">
  <div class="product-detail-grid">
    <!-- Gallery -->
    <div class="pd-gallery">
      @php $gallery = $product->gallery; @endphp
      <div class="pd-main-img">
        <img src="{{ $gallery[0] ?? '' }}" id="pdMainImg" alt="{{ $product->name }}"/>
      </div>
      @if(count($gallery) > 1)
        <div class="pd-thumbs">
          @foreach($gallery as $g)
            <img src="{{ $g }}" class="pd-thumb {{ $loop->first ? 'active' : '' }}" onclick="changeMainImg(this, '{{ $g }}')" alt="thumb"/>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Info -->
    <div class="pd-info">
      <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--red);margin-bottom:8px;">{{ $brand->name }} · {{ $category->name }}</div>
      <h1 class="pd-title">{{ $product->name }}</h1>
      <p class="pd-desc">{{ $product->description }}</p>

      @if(is_array($product->specs) && count($product->specs))
        <table class="pd-specs">
          <tbody>
            @foreach($product->specs as $spec)
              <tr><td>{{ $spec[0] ?? '' }}</td><td>{{ $spec[1] ?? '' }}</td></tr>
            @endforeach
          </tbody>
        </table>
      @endif

      <div class="pd-cta">
        <a href="{{ wa_link('Halo, saya tertarik dengan produk *'.$product->name.'* ('.$brand->name.'). Mohon info harga & ketersediaan stok.') }}" target="_blank" class="btn-p">
          <i class="fab fa-whatsapp"></i> Tanya Harga via WhatsApp
        </a>
      </div>
    </div>
  </div>

  @if($related->count())
    <div style="margin-top:72px;">
      <h2 style="font-family:'Barlow Condensed';font-weight:800;font-size:24px;text-transform:uppercase;margin-bottom:24px;">Produk Lain di {{ $category->name }}</h2>
      <div class="products-grid">
        @foreach($related as $r)
          <a href="{{ route('site.product', [$brand->slug, $category->slug, $r->slug]) }}" class="pc" style="text-decoration:none;">
            @if($r->image)<img src="{{ $r->image_url }}" alt="{{ $r->name }}"/>@endif
            <div class="pc-body">
              <div class="pc-name">{{ $r->name }}</div>
              <div class="pc-desc">{{ Str::limit($r->description, 60) }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif
</section>

@push('styles')
<style>
  .product-detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:56px;}
  .pd-main-img{width:100%;background:var(--g50);border:1px solid var(--g200);border-radius:6px;overflow:hidden;aspect-ratio:1;display:flex;align-items:center;justify-content:center;}
  .pd-main-img img{width:100%;height:100%;object-fit:contain;}
  .pd-thumbs{display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;}
  .pd-thumb{width:72px;height:72px;object-fit:cover;border:2px solid var(--g200);border-radius:4px;cursor:pointer;}
  .pd-thumb.active{border-color:var(--red);}
  .pd-title{font-family:'Barlow Condensed';font-weight:900;font-size:clamp(28px,3.5vw,42px);text-transform:uppercase;line-height:1.05;margin-bottom:18px;}
  .pd-desc{color:var(--g600);line-height:1.8;margin-bottom:28px;}
  .pd-specs{width:100%;border-collapse:collapse;margin-bottom:28px;}
  .pd-specs td{padding:11px 14px;border-bottom:1px solid var(--g200);font-size:14px;}
  .pd-specs td:first-child{color:var(--g600);font-weight:500;width:45%;}
  .pd-specs td:last-child{font-weight:600;color:var(--g800);}
  .pd-specs tr:nth-child(odd){background:var(--g50);}
  @media(max-width:768px){.product-detail-grid{grid-template-columns:1fr;gap:32px;}}
</style>
@endpush

@push('scripts')
<script>
  function changeMainImg(el, src){
    document.getElementById('pdMainImg').src = src;
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
  }
</script>
@endpush
@endsection
