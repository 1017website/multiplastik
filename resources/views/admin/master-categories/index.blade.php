@extends('admin.layout')
@section('title', 'Master Kategori')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <div>
        <h4 class="mb-1">Master Kategori</h4>
        <p class="text-muted mb-0">Kelompok utama yang tampil di frontend, misalnya Gelas, Kresek, atau Sedotan.</p>
    </div>
    <a href="{{ route('admin.master-categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Master Kategori
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Kategori Brand</th>
                    <th>Produk</th>
                    <th>Urut</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($masterCategories as $masterCategory)
                    <tr>
                        <td>
                            @if ($masterCategory->image)
                                <img src="{{ $masterCategory->image_url }}"
                                    style="height:36px;width:36px;object-fit:cover;border-radius:4px;">
                            @elseif ($masterCategory->icon)
                                <i class="{{ $masterCategory->icon }} text-danger"></i>
                            @endif
                        </td>
                        <td><strong>{{ $masterCategory->name }}</strong></td>
                        <td><code>{{ $masterCategory->slug }}</code></td>
                        <td>{{ $masterCategory->categories_count }}</td>
                        <td>{{ $masterCategory->products_count }}</td>
                        <td>{{ $masterCategory->sort_order }}</td>
                        <td>
                            @if ($masterCategory->is_active)
                                <span class="badge-soft success">Aktif</span>
                            @else
                                <span class="badge-soft danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.master-categories.edit', $masterCategory) }}"
                                class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.master-categories.destroy', $masterCategory) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Hapus master kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" @disabled($masterCategory->categories_count > 0)
                                    title="{{ $masterCategory->categories_count > 0 ? 'Masih digunakan kategori brand' : 'Hapus' }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada master kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $masterCategories->links() }}</div>
@endsection
