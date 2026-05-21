@extends('admin.layout')
@section('title', 'Site Settings')
@section('content')

@php
    $tabLabels = [
        'general' => ['Umum & SEO', 'fas fa-cog'],
        'contact' => ['Kontak & Sosmed', 'fas fa-phone'],
        'about' => ['Section Tentang', 'fas fa-info-circle'],
        'keunggulan' => ['Section Keunggulan', 'fas fa-trophy'],
        'hero_stats' => ['Hero Stats Bar', 'fas fa-chart-bar'],
        'sosmed_embed' => ['Embed Sosmed', 'fab fa-instagram'],
        'analytics' => ['Analytics (GA, GTM, Pixel)', 'fas fa-chart-line'],
        'ads' => ['Iklan & Custom Script', 'fas fa-bullseye'],
    ];
@endphp

<ul class="nav nav-pills mb-3 flex-wrap">
    @foreach($tabLabels as $key => $info)
        <li class="nav-item">
            <a class="nav-link {{ $group === $key ? 'active' : '' }}" href="{{ route('admin.settings.index', $key) }}" style="{{ $group === $key ? 'background:#C0272D;' : '' }}">
                <i class="{{ $info[1] }}"></i> {{ $info[0] }}
            </a>
        </li>
    @endforeach
</ul>

<form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data">
    @csrf

    <div class="card p-4">
        <div class="row g-3">
            @foreach($fields as $key => $config)
                @php
                    $type = $config['type'] ?? 'text';
                    $val = $values[$key] ?? '';
                    $col = in_array($type, ['textarea']) ? 'col-12' : 'col-md-6';
                @endphp
                <div class="{{ $col }}">
                    <label class="form-label">{{ $config['label'] }}</label>

                    @if($type === 'textarea')
                        <textarea name="{{ $key }}" class="form-control" rows="3">{{ $val }}</textarea>
                    @elseif($type === 'image')
                        @if($val)
                            <div class="mb-2">
                                <img src="{{ str_starts_with($val, 'http') ? $val : asset($val) }}" style="max-height:60px;border:1px solid #ddd;border-radius:4px;padding:3px;background:#fff;">
                            </div>
                        @endif
                        <input type="file" name="{{ $key }}" class="form-control mb-2" accept="image/*">
                        <input type="text" name="{{ $key }}_url_manual" class="form-control" placeholder="...atau paste URL gambar">
                    @else
                        <input type="text" name="{{ $key }}" class="form-control" value="{{ $val }}">
                    @endif

                    @if(!empty($config['help']))
                        <small class="text-muted">{{ $config['help'] }}</small>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
    </div>
</form>

@if($group === 'analytics')
    <div class="alert alert-info mt-4">
        <h6 class="mb-2"><i class="fas fa-lightbulb"></i> Cara Pasang</h6>
        <ul class="mb-0 small">
            <li><strong>Google Analytics 4</strong>: cari Measurement ID di GA4 → Admin → Data Streams. Format: <code>G-XXXXXXX</code></li>
            <li><strong>Google Tag Manager</strong>: cari Container ID di GTM. Format: <code>GTM-XXXXX</code></li>
            <li><strong>Meta Pixel</strong>: dari Meta Events Manager → ambil Pixel ID (angka 15-16 digit)</li>
            <li><strong>TikTok Pixel</strong>: dari TikTok Events Manager</li>
        </ul>
    </div>
@elseif($group === 'ads')
    <div class="alert alert-info mt-4">
        <h6 class="mb-2"><i class="fas fa-lightbulb"></i> Tips Tracking Konversi</h6>
        <ul class="mb-0 small">
            <li><strong>Google Ads Conversion ID</strong>: dari Google Ads → Tools → Conversions. Format: <code>AW-XXXXXXXXX</code></li>
            <li>Pasang UTM di link iklan agar bisa dilihat di menu <strong>Analytics</strong>: <code>?utm_source=meta&utm_medium=cpc&utm_campaign=promo-2025</code></li>
            <li><strong>Custom Script</strong>: untuk script tambahan yang belum tersedia di field di atas</li>
        </ul>
    </div>
@endif
@endsection
