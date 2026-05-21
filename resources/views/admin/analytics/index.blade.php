@extends('admin.layout')
@section('title', 'Analytics')
@section('content')

<form method="GET" class="mb-3">
    <div class="btn-group">
        @foreach([7 => '7 Hari', 14 => '14 Hari', 30 => '30 Hari', 90 => '90 Hari'] as $val => $lbl)
            <button name="range" value="{{ $val }}" class="btn btn-sm {{ $range == $val ? 'btn-primary' : 'btn-outline-primary' }}">{{ $lbl }}</button>
        @endforeach
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="stat-card"><div class="label">Total Kunjungan ({{ $range }} hari)</div><div class="value">{{ number_format($total) }}</div></div></div>
    <div class="col-md-4"><div class="stat-card"><div class="label">Pengunjung Unik (by IP)</div><div class="value">{{ number_format($unique) }}</div></div></div>
    <div class="col-md-4"><div class="stat-card"><div class="label">Rata-rata / Hari</div><div class="value">{{ $range ? number_format($total / $range, 1) : 0 }}</div></div></div>
</div>

<div class="card p-3 mb-4">
    <h5 class="mb-3">Grafik Kunjungan</h5>
    <canvas id="visitChart" height="80"></canvas>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="mb-3">Per Tipe Halaman</h6>
            @forelse($byPage as $p)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ ucfirst($p->page_type ?? '-') }}</span><strong>{{ $p->total }}</strong></div>
            @empty <p class="text-muted small">Belum ada data.</p> @endforelse
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="mb-3">Per Device</h6>
            @forelse($byDevice as $d)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ ucfirst($d->device ?? '-') }}</span><strong>{{ $d->total }}</strong></div>
            @empty <p class="text-muted small">Belum ada data.</p> @endforelse
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="mb-3">Top Referrer</h6>
            @forelse($byReferrer as $r)
                <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-truncate" style="max-width:180px;">{{ parse_url($r->referrer, PHP_URL_HOST) ?? $r->referrer }}</span><strong>{{ $r->total }}</strong></div>
            @empty <p class="text-muted small">Belum ada referrer (kunjungan langsung).</p> @endforelse
        </div>
    </div>
</div>

<div class="card p-3 mt-3">
    <h6 class="mb-3"><i class="fas fa-bullseye"></i> Traffic dari Iklan (UTM)</h6>
    @if($byUtm->count())
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th>Source</th><th>Medium</th><th>Campaign</th><th>Klik</th></tr></thead>
                <tbody>
                    @foreach($byUtm as $u)
                        <tr>
                            <td><span class="badge bg-dark">{{ $u->utm_source }}</span></td>
                            <td>{{ $u->utm_medium ?? '-' }}</td>
                            <td>{{ $u->utm_campaign ?? '-' }}</td>
                            <td><strong>{{ $u->total }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted small mb-0">
            Belum ada traffic ber-UTM. Pasang parameter UTM di link iklan Meta Ads / Google Ads, contoh:<br>
            <code>{{ url('/') }}?utm_source=meta&utm_medium=cpc&utm_campaign=promo-lebaran</code>
        </p>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('visitChart'), {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{ label: 'Kunjungan', data: @json($chartValues), borderColor: '#C0272D', backgroundColor: 'rgba(192,39,45,.08)', fill: true, tension: .3 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
@endsection
