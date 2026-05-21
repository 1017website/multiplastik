@extends('admin.layout')
@section('title', 'Kategori')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Daftar Kategori</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kategori</a>
</div>

<form class="card p-3 mb-3" method="GET">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Filter Brand</label>
            <select name="brand_id" class="form-select" onchange="this.form.submit()">
                <option value="">— Semua Brand —</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" @selected(request('brand_id')==$b->id)>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Gambar</th><th>Brand</th><th>Nama</th><th>Slug</th><th>Produk</th><th>Urut</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($categories as $c)
                    <tr>
                        <td>@if($c->image)<img src="{{ $c->image_url }}" style="height:36px;width:36px;object-fit:cover;border-radius:4px;">@endif</td>
                        <td><small class="text-muted">{{ $c->brand->name }}</small></td>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td><code>{{ $c->slug }}</code></td>
                        <td>{{ $c->products_count }}</td>
                        <td>{{ $c->sort_order }}</td>
                        <td>@if($c->is_active)<span class="badge-soft success">Aktif</span>@else<span class="badge-soft danger">Nonaktif</span>@endif</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $categories->links() }}</div>
@endsection
