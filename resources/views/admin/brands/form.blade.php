@extends('admin.layout')
@section('title', $brand->exists ? 'Edit Brand' : 'Tambah Brand')
@section('content')

<form method="POST" action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" enctype="multipart/form-data">
    @csrf
    @if($brand->exists) @method('PUT') @endif

    <div class="card p-4">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Brand *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug (URL)</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}" placeholder="auto dari nama jika kosong">
            </div>
            <div class="col-12">
                <label class="form-label">Tagline</label>
                <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $brand->tagline) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $brand->description) }}</textarea>
            </div>
            <div class="col-md-6">
                @include('admin.partials.image-input', ['name' => 'logo', 'label' => 'Logo Brand', 'value' => $brand->logo, 'folder' => 'brands'])
            </div>
            <div class="col-md-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand->sort_order ?? 0) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $brand->is_active ?? 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
