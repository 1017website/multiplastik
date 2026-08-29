@extends('admin.layout')
@section('title', 'Detail Calon Mitra')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('admin.partnerships.index') }}" class="small text-decoration-none"><i class="fas fa-arrow-left"></i> Kembali ke daftar</a>
        <h4 class="mb-0 mt-2">{{ $partnership->name }}</h4>
    </div>
    <span class="badge bg-{{ ['new'=>'danger','contacted'=>'info','qualified'=>'warning','completed'=>'success','rejected'=>'secondary'][$partnership->status] ?? 'secondary' }} fs-6">{{ $partnership->status_label }}</span>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card p-4 mb-3">
            <h6 class="text-uppercase text-muted mb-3">Data Calon Mitra</h6>
            <div class="row g-4">
                <div class="col-md-6"><small class="text-muted d-block">Nama</small><strong>{{ $partnership->name }}</strong></div>
                <div class="col-md-6"><small class="text-muted d-block">WhatsApp</small><a href="https://wa.me/{{ preg_replace('/\D+/', '', $partnership->whatsapp) }}" target="_blank"><i class="fab fa-whatsapp"></i> {{ $partnership->whatsapp }}</a></div>
                <div class="col-md-6"><small class="text-muted d-block">Email</small><span>{{ $partnership->email ?: '-' }}</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Kota/Kabupaten</small><span>{{ $partnership->city }}</span></div>
                <div class="col-12"><small class="text-muted d-block">Rencana Lokasi/Alamat</small><span>{{ $partnership->address ?: '-' }}</span></div>
            </div>
        </div>

        <div class="card p-4">
            <h6 class="text-uppercase text-muted mb-3">Rencana Usaha</h6>
            <div class="row g-4">
                <div class="col-md-4"><small class="text-muted d-block">Kondisi Usaha</small><strong>{{ $partnership->business_stage_label }}</strong></div>
                <div class="col-md-4"><small class="text-muted d-block">Kisaran Modal</small><strong>{{ $partnership->capital_range_label }}</strong></div>
                <div class="col-md-4"><small class="text-muted d-block">Target Mulai</small><strong>{{ $partnership->start_timeline_label }}</strong></div>
                <div class="col-12">
                    <small class="text-muted d-block mb-2">Produk yang Diminati</small>
                    @forelse($partnership->preferred_product_labels as $product)
                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $product }}</span>
                    @empty
                        <span>-</span>
                    @endforelse
                </div>
                <div class="col-12"><small class="text-muted d-block">Pertanyaan/Catatan Calon Mitra</small><div class="bg-light border rounded p-3 mt-1" style="white-space:pre-wrap;">{{ $partnership->message ?: '-' }}</div></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-4">
            <h6 class="mb-3">Tindak Lanjut</h6>
            <form method="POST" action="{{ route('admin.partnerships.update', $partnership) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $partnership->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan Internal</label>
                    <textarea name="admin_notes" class="form-control" rows="8" maxlength="5000" placeholder="Hasil telepon, kebutuhan khusus, jadwal follow-up...">{{ old('admin_notes', $partnership->admin_notes) }}</textarea>
                    <small class="text-muted">Catatan ini tidak tampil di website.</small>
                </div>
                <button class="btn btn-primary w-100"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </form>
            <hr>
            <small class="text-muted d-block">Dikirim</small><span>{{ $partnership->created_at->format('d M Y, H:i') }}</span>
            <small class="text-muted d-block mt-3">Persetujuan data</small><span>{{ $partnership->consent_at?->format('d M Y, H:i') ?? '-' }}</span>
        </div>
    </div>
</div>
@endsection
