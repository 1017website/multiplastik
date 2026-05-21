@extends('admin.layout')
@section('title', $slide->exists ? 'Edit Slide' : 'Tambah Slide')
@section('content')

<form method="POST" action="{{ $slide->exists ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" enctype="multipart/form-data">
    @csrf
    @if($slide->exists) @method('PUT') @endif

    <div class="card p-4">
        <h6 class="mb-3 text-uppercase text-muted">Konten Slide</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tag (label kecil di atas judul)</label>
                <input type="text" name="tag" class="form-control" value="{{ old('tag', $slide->tag) }}" placeholder="contoh: Distributor Resmi Terpercaya">
            </div>
            <div class="col-md-4">
                <label class="form-label">Judul Baris 1</label>
                <input type="text" name="title_top" class="form-control" value="{{ old('title_top', $slide->title_top) }}" placeholder="Solusi Plastik">
            </div>
            <div class="col-md-4">
                <label class="form-label">Judul Baris 2 <small class="text-danger">(merah)</small></label>
                <input type="text" name="title_em" class="form-control" value="{{ old('title_em', $slide->title_em) }}" placeholder="& Kemasan">
            </div>
            <div class="col-md-4">
                <label class="form-label">Judul Baris 3</label>
                <input type="text" name="title_bottom" class="form-control" value="{{ old('title_bottom', $slide->title_bottom) }}" placeholder="Terlengkap">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="subtitle" class="form-control" rows="2">{{ old('subtitle', $slide->subtitle) }}</textarea>
            </div>
            <div class="col-md-6">
                @include('admin.partials.image-input', ['name' => 'background_image', 'label' => 'Background Image', 'value' => $slide->background_image, 'folder' => 'slides'])
            </div>
            <div class="col-md-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $slide->sort_order ?? 0) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $slide->is_active ?? 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <h6 class="mb-3 text-uppercase text-muted">Tombol Utama (Primary)</h6>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Teks Tombol</label>
                <input type="text" name="btn_primary_text" class="form-control" value="{{ old('btn_primary_text', $slide->btn_primary_text) }}" placeholder="Lihat Produk">
            </div>
            <div class="col-md-5">
                <label class="form-label">Link</label>
                <input type="text" name="btn_primary_link" class="form-control" value="{{ old('btn_primary_link', $slide->btn_primary_link) }}" placeholder="nav:brands atau nav:brand:hok-cup atau wa">
            </div>
            <div class="col-md-2">
                <label class="form-label">Icon</label>
                <input type="text" name="btn_primary_icon" class="form-control" value="{{ old('btn_primary_icon', $slide->btn_primary_icon) }}" placeholder="fas fa-box-open">
            </div>
        </div>

        <hr class="my-4">

        <h6 class="mb-3 text-uppercase text-muted">Tombol Sekunder (Outline) — opsional</h6>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Teks Tombol</label>
                <input type="text" name="btn_secondary_text" class="form-control" value="{{ old('btn_secondary_text', $slide->btn_secondary_text) }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">Link</label>
                <input type="text" name="btn_secondary_link" class="form-control" value="{{ old('btn_secondary_link', $slide->btn_secondary_link) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Icon</label>
                <input type="text" name="btn_secondary_icon" class="form-control" value="{{ old('btn_secondary_icon', $slide->btn_secondary_icon) }}">
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0 small">
            <strong>Format link:</strong>
            <code>nav:brands</code> → halaman semua brand,
            <code>nav:brand:hok-cup</code> → halaman brand tertentu,
            <code>wa</code> → WhatsApp utama,
            atau URL biasa (<code>https://...</code>).
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
