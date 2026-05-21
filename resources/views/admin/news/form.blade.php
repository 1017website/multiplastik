@extends('admin.layout')
@section('title', $news->exists ? 'Edit Artikel' : 'Tambah Artikel')
@section('content')

<form method="POST" action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}" enctype="multipart/form-data">
    @csrf
    @if($news->exists) @method('PUT') @endif

    <div class="card p-4">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Judul *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $news->slug) }}" placeholder="auto dari judul">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" class="form-control" value="{{ old('category', $news->category) }}" placeholder="Promo, Produk Baru, Tips & Info, dll" list="catlist">
                <datalist id="catlist">
                    <option value="Promo"><option value="Produk Baru"><option value="Tips & Info"><option value="Update Stok"><option value="Produk">
                </datalist>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Publish</label>
                <input type="date" name="published_at" class="form-control" value="{{ old('published_at', $news->published_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $news->is_active ?? 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Excerpt (ringkasan singkat)</label>
                <textarea name="excerpt" class="form-control" rows="2" maxlength="500">{{ old('excerpt', $news->excerpt) }}</textarea>
            </div>

            <div class="col-md-6">
                @include('admin.partials.image-input', ['name' => 'image', 'label' => 'Gambar Utama', 'value' => $news->image, 'folder' => 'news'])
            </div>

            <div class="col-12">
                <label class="form-label">Konten (HTML)</label>
                <textarea name="content" id="contentEditor" class="form-control" rows="14">{{ old('content', $news->content) }}</textarea>
                <small class="text-muted">Boleh isi HTML. Tag yang umum: <code>&lt;p&gt;</code>, <code>&lt;h3&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;ul&gt;&lt;li&gt;</code>, <code>&lt;a href&gt;</code>.</small>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
