@extends('admin.layout')
@section('title', $masterCategory->exists ? 'Edit Master Kategori' : 'Tambah Master Kategori')
@section('content')

<form method="POST"
    action="{{ $masterCategory->exists ? route('admin.master-categories.update', $masterCategory) : route('admin.master-categories.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if ($masterCategory->exists) @method('PUT') @endif

    <div class="card p-4">
        <div class="mb-4">
            <h5 class="mb-1">{{ $masterCategory->exists ? 'Edit' : 'Tambah' }} Master Kategori</h5>
            <p class="text-muted mb-0">Data ini menjadi kartu kategori utama yang dilihat pengunjung di beranda.</p>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama *</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $masterCategory->name) }}" placeholder="Contoh: Gelas" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control"
                    value="{{ old('slug', $masterCategory->slug) }}" placeholder="Otomatis dari nama">
            </div>
            <div class="col-md-6">
                <label class="form-label">Icon FontAwesome</label>
                <input type="text" name="icon" class="form-control"
                    value="{{ old('icon', $masterCategory->icon) }}" placeholder="fas fa-glass-water">
                <small class="text-muted">Dipakai jika gambar belum diisi.</small>
            </div>
            <div class="col-md-6">
                @include('admin.partials.image-input', [
                    'name' => 'image',
                    'label' => 'Gambar Master Kategori',
                    'value' => $masterCategory->image,
                    'folder' => 'master-categories'
                ])
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $masterCategory->description) }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control"
                    value="{{ old('sort_order', $masterCategory->sort_order ?? 0) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                        {{ old('is_active', $masterCategory->is_active ?? 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.master-categories.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
