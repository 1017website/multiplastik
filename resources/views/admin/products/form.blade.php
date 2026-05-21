@extends('admin.layout')
@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('content')

<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="card p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">— Pilih Kategori —</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id)==$c->id)>{{ $c->brand->name }} – {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}" placeholder="auto dari nama">
            </div>
            <div class="col-md-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $product->is_active ?? 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="col-md-6">
                @include('admin.partials.image-input', ['name' => 'image', 'label' => 'Gambar Utama', 'value' => $product->image, 'folder' => 'products'])
            </div>

            <div class="col-md-6">
                <label class="form-label">Spesifikasi</label>
                <textarea name="specs_text" class="form-control" rows="8" placeholder="Format per baris: Label|Value
Contoh:
Ukuran|6 Oz
Diameter Atas|9,2 cm
Material|PP Food Grade, BPA Free">{{ old('specs_text', collect($product->specs ?? [])->map(fn($s) => is_array($s) ? ($s[0] ?? '').'|'.($s[1] ?? '') : '')->implode("\n")) }}</textarea>
                <small class="text-muted">Satu baris satu spesifikasi. Pisahkan label dan value dengan tanda <code>|</code></small>
            </div>

            <div class="col-12"><hr></div>

            <div class="col-12">
                <h6>Gallery Tambahan (opsional)</h6>
                @if($product->exists && $product->images->count())
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($product->images as $img)
                            <div style="position:relative;">
                                <img src="{{ str_starts_with($img->path, 'http') ? $img->path : asset('uploads/products/'.$img->path) }}" style="height:80px;border:1px solid #ddd;border-radius:4px;">
                                <form action="{{ route('admin.product-images.destroy', $img) }}" method="POST" style="position:absolute;top:2px;right:2px;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" style="padding:1px 6px;font-size:11px;" onclick="return confirm('Hapus gambar ini?')"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Upload Multiple</label>
                        <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">...atau Tempel URL (satu per baris)</label>
                        <textarea name="gallery_urls" class="form-control" rows="3" placeholder="https://...&#10;https://..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
