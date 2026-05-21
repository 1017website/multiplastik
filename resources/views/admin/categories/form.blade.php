@extends('admin.layout')
@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')

<form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
    @csrf
    @if($category->exists) @method('PUT') @endif

    <div class="card p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Brand *</label>
                <select name="brand_id" class="form-select" required>
                    <option value="">— Pilih Brand —</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(old('brand_id', $category->brand_id)==$b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}" placeholder="auto dari nama">
            </div>
            <div class="col-md-6">
                <label class="form-label">Icon FontAwesome</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="fas fa-coffee">
                <small class="text-muted">Lihat ikon di <a href="https://fontawesome.com/search" target="_blank">fontawesome.com</a></small>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="col-md-6">
                @include('admin.partials.image-input', ['name' => 'image', 'label' => 'Gambar Kategori', 'value' => $category->image, 'folder' => 'categories'])
            </div>
            <div class="col-md-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $category->is_active ?? 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
