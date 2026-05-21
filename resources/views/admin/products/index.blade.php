@extends('admin.layout')
@section('title', 'Produk')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Daftar Produk</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
</div>

<form class="card p-3 mb-3" method="GET">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Filter Kategori</label>
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">— Semua Kategori —</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->brand->name }} – {{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Cari Nama</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="cari produk...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100">Filter</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Gambar</th><th>Brand / Kategori</th><th>Nama</th><th>Specs</th><th>Urut</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>@if($p->image)<img src="{{ $p->image_url }}" style="height:50px;width:50px;object-fit:cover;border-radius:4px;">@endif</td>
                        <td><small class="text-muted">{{ $p->category->brand->name }}<br>{{ $p->category->name }}</small></td>
                        <td><strong>{{ $p->name }}</strong><br><code class="small">{{ $p->slug }}</code></td>
                        <td><small>{{ is_array($p->specs) ? count($p->specs) : 0 }} item</small></td>
                        <td>{{ $p->sort_order }}</td>
                        <td>@if($p->is_active)<span class="badge-soft success">Aktif</span>@else<span class="badge-soft danger">Nonaktif</span>@endif</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
