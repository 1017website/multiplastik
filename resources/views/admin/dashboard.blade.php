@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-2 col-6"><div class="stat-card"><div class="label">Visit Hari Ini</div><div class="value">{{ number_format($stats['visit_today']) }}</div></div></div>
    <div class="col-md-2 col-6"><div class="stat-card"><div class="label">Visit 7 Hari</div><div class="value">{{ number_format($stats['visit_7d']) }}</div></div></div>
    <div class="col-md-2 col-6"><div class="stat-card"><div class="label">Visit 30 Hari</div><div class="value">{{ number_format($stats['visit_30d']) }}</div></div></div>
    <div class="col-md-2 col-6"><div class="stat-card"><div class="label">Brand</div><div class="value">{{ $stats['total_brands'] }}</div></div></div>
    <div class="col-md-2 col-6"><div class="stat-card"><div class="label">Produk</div><div class="value">{{ $stats['total_products'] }}</div></div></div>
    <div class="col-md-2 col-6"><div class="stat-card"><div class="label">Artikel</div><div class="value">{{ $stats['total_news'] }}</div></div></div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card p-3">
            <h5 class="mb-3">Pengunjung 7 Hari Terakhir</h5>
            <canvas id="visitChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 mb-3">
            <h6 class="mb-3">Top UTM Source</h6>
            @forelse($topSources as $s)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>{{ $s->utm_source }}</span>
                    <strong>{{ $s->total }}</strong>
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada traffic dari kampanye iklan. Pasang UTM di link iklan Meta/Google Ads.</p>
            @endforelse
        </div>
        <div class="card p-3">
            <h6 class="mb-3">Device</h6>
            @foreach($deviceStats as $dev => $cnt)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><i class="fas fa-{{ $dev === 'mobile' ? 'mobile-alt' : ($dev === 'tablet' ? 'tablet-alt' : 'desktop') }}"></i> {{ ucfirst($dev) }}</span>
                    <strong>{{ $cnt }}</strong>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('visitChart'), {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Pengunjung',
            data: @json($chartValues),
            borderColor: '#C0272D',
            backgroundColor: 'rgba(192,39,45,.08)',
            fill: true,
            tension: .3,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
@endsection
