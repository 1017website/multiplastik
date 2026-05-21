@extends('admin.layout')
@section('title', 'CS WhatsApp')
@section('content')

<div class="alert alert-info mb-3">
    <i class="fas fa-info-circle me-1"></i>
    <strong>Sistem Round-Robin:</strong> Setiap pengunjung klik tombol WA, sistem otomatis mendistribusikan ke CS dengan jumlah click paling sedikit agar beban merata.
</div>

{{-- Tambah CS --}}
<div class="card p-4 mb-4">
    <h6 class="mb-3">Tambah CS Baru</h6>
    <form method="POST" action="{{ route('admin.cs.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Nama CS *</label>
                <input type="text" name="name" class="form-control" placeholder="Rina, Budi, CS 1..." required>
            </div>
            <div class="col-md-3">
                <label class="form-label">No. WhatsApp * <small class="text-muted">(format: 628xxx)</small></label>
                <input type="text" name="whatsapp" class="form-control" placeholder="6281234567890" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tampilan Nomor</label>
                <input type="text" name="display_number" class="form-control" placeholder="+62 812-xxx">
            </div>
            <div class="col-md-2">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <div class="form-check form-switch mt-2">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="add_active" checked>
                    <label class="form-check-label" for="add_active">Aktif</label>
                </div>
                <button class="btn btn-primary"><i class="fas fa-plus"></i></button>
            </div>
            <div class="col-12">
                <label class="form-label">Pesan Greeting WA</label>
                <input type="text" name="greeting" class="form-control" placeholder="Halo, saya ingin bertanya tentang produk Multi Plastik.">
                <small class="text-muted">Pesan yang otomatis muncul saat pengunjung klik tombol WA.</small>
            </div>
        </div>
    </form>
</div>

{{-- List CS --}}
<div class="card mb-4">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Nama CS</th>
                    <th>WhatsApp</th>
                    <th>Greeting</th>
                    <th>Urut</th>
                    <th>Total Click</th>
                    <th>30 Hari</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $a)
                    <tr>
                        <form method="POST" action="{{ route('admin.cs.update', $a) }}">
                            @csrf @method('PUT')
                            <td><input type="text" name="name" class="form-control form-control-sm" value="{{ $a->name }}" required></td>
                            <td>
                                <input type="text" name="whatsapp" class="form-control form-control-sm" value="{{ $a->whatsapp }}" required style="width:140px;">
                                <input type="text" name="display_number" class="form-control form-control-sm mt-1" value="{{ $a->display_number }}" placeholder="tampilan">
                            </td>
                            <td><input type="text" name="greeting" class="form-control form-control-sm" value="{{ $a->greeting }}" placeholder="pesan greeting" style="min-width:200px;"></td>
                            <td><input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $a->sort_order }}" style="width:64px;"></td>
                            <td>
                                <span class="fw-bold fs-5">{{ number_format($a->click_count) }}</span>
                            </td>
                            <td>{{ number_format($stats[$a->id] ?? 0) }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $a->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
                        </form>
                        <form method="POST" action="{{ route('admin.cs.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus CS {{ $a->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada CS. Tambahkan di atas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Chart distribusi --}}
@if($agents->count() > 0)
<div class="card p-4">
    <h6 class="mb-3">Distribusi Click per CS (Total)</h6>
    <div style="max-width:480px;">
        <canvas id="csChart" height="200"></canvas>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($agents->count() > 0)
new Chart(document.getElementById('csChart'), {
    type: 'doughnut',
    data: {
        labels: @json($agents->pluck('name')),
        datasets: [{
            data: @json($agents->pluck('click_count')),
            backgroundColor: ['#C0272D','#e05b60','#f0a0a2','#222','#555','#888','#bbb'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'right' } }
    }
});
@endif
</script>
@endpush
@endsection
