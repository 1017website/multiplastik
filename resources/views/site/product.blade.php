@extends('site.layout')
@section('title', $product->name . ' – ' . $brand->name)
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

    <div class="pd-body">
        <div class="pd-grid">

            {{-- ===== GALLERY ===== --}}
            <div class="pd-gallery">
                @php $gallery = $product->gallery; @endphp
                <img src="{{ $gallery[0] ?? '' }}" class="pd-main" id="pdMainImg" alt="{{ $product->name }}" />
                @if (count($gallery) > 1)
                    <div class="pd-thumbs">
                        @foreach ($gallery as $g)
                            <img src="{{ $g }}" class="pd-thumb {{ $loop->first ? 'active' : '' }}"
                                onclick="changeThumb(this,'{{ $g }}')" alt="thumb" />
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ===== INFO ===== --}}
            <div class="pd-info">
                {{-- Brand tag --}}
                <div class="pd-brand-tag">
                    @if ($brand->logo)
                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" />
                    @endif
                    <span>{{ $brand->name }}</span>
                </div>

                {{-- Category badge --}}
                <div><span class="pd-catbadge">{{ $category->name }}</span></div>

                {{-- Nama --}}
                <div class="pd-name">{{ $product->name }}</div>

                {{-- Deskripsi --}}
                <div class="pd-desc">{{ $product->description }}</div>

                {{-- Specs --}}
                @if (is_array($product->specs) && count($product->specs))
                    <div class="pd-specs">
                        <div class="pd-specs-title"><i class="fas fa-list-ul" style="margin-right:8px;"></i>Spesifikasi
                            Produk</div>
                        @foreach ($product->specs as $spec)
                            <div class="pd-spec-row">
                                <div class="pd-spec-k">{{ $spec[0] ?? '' }}</div>
                                <div class="pd-spec-v">{{ $spec[1] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- CTA --}}
                <div class="pd-actions">
                    <a href="{{ wa_link('Halo, saya tertarik dengan produk *' . $product->name . '* (' . $brand->name . '). Mohon info harga & ketersediaan stok.') }}"
                        target="_blank" class="btn-wa">
                        <i class="fab fa-whatsapp"></i> Tanya Harga / Order via WhatsApp
                    </a>
                    <button class="btn-share" onclick="shareProduct()">
                        <i class="fas fa-share-alt"></i> Bagikan Produk
                    </button>
                </div>
            </div>

        </div>

        {{-- ===== PRODUK TERKAIT ===== --}}
        @if ($related->count())
            <div class="pd-related-sec">
                <div class="pd-related-title">Produk Lain di {{ $category->name }}</div>
                <div class="prods-grid" style="padding:0;">
                    @foreach ($related as $r)
                        <a href="{{ route('site.product', [$brand->slug, $category->slug, $r->slug]) }}" class="pc"
                            style="text-decoration:none;">
                            @if ($r->image)
                                <img src="{{ $r->image_url }}" alt="{{ $r->name }}" />
                            @endif
                            <div class="pc-body">
                                <div
                                    style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--red);margin-bottom:4px;">
                                    {{ $brand->name }}</div>
                                <div class="pc-name">{{ $r->name }}</div>
                                <div class="pc-desc">{{ Str::limit($r->description, 60) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function changeThumb(el, src) {
            document.getElementById('pdMainImg').src = src;
            document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }

        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $product->name }}',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link produk berhasil disalin!');
            }
        }
    </script>
@endpush
