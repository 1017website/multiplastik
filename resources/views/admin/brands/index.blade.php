@extends('admin.layout')
@section('title', 'Brand')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Daftar Brand</h4>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Brand</a>
</div>

<p class="text-muted small mb-3">
    Gunakan tombol <strong>Tampil di frontend</strong> untuk mengatur tampilan brand di beranda, daftar brand, dan footer.
    Kategori dan produk tetap dapat diakses selama statusnya aktif.
</p>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Logo</th><th>Nama</th><th>Slug</th><th>Tagline</th><th>Kategori</th><th>Urut</th><th>Status</th><th>Tampil di frontend</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $b)
                    <tr>
                        <td>
                            @if($b->logo)<img src="{{ $b->logo_url }}" style="height:40px;object-fit:contain;">@endif
                        </td>
                        <td class="text-nowrap"><strong>{{ $b->name }}</strong></td>
                        <td class="text-nowrap"><code>{{ $b->slug }}</code></td>
                        <td>{{ Str::limit($b->tagline, 50) }}</td>
                        <td>{{ $b->categories_count }}</td>
                        <td>{{ $b->sort_order }}</td>
                        <td>
                            @if($b->is_active)<span class="badge-soft success">Aktif</span>@else<span class="badge-soft danger">Nonaktif</span>@endif
                        </td>
                        <td>
                            <form action="{{ route('admin.brands.visibility', $b) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="show_on_frontend" value="{{ $b->show_on_frontend ? '0' : '1' }}">
                                <button type="submit" class="btn btn-sm {{ $b->show_on_frontend ? 'btn-outline-success' : 'btn-outline-secondary' }} text-nowrap"
                                    role="switch" aria-checked="{{ $b->show_on_frontend ? 'true' : 'false' }}"
                                    aria-label="Tampilkan brand {{ $b->name }} di frontend">
                                    <i class="fas {{ $b->show_on_frontend ? 'fa-toggle-on' : 'fa-toggle-off' }} me-1" aria-hidden="true"></i>
                                    {{ $b->show_on_frontend ? 'Ya' : 'Tidak' }}
                                </button>
                            </form>
                            @if(!$b->is_active)<small class="text-muted d-block mt-1">Brand nonaktif</small>@endif
                        </td>
                        <td>
                            <a href="{{ route('admin.brands.edit', $b) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.brands.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus brand ini? Semua kategori & produk di bawahnya ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada brand.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $brands->links() }}</div>
@endsection
