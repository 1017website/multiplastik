@extends('admin.layout')
@section('title', 'News & Artikel')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Daftar Artikel</h4>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Artikel</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($news as $n)
                    <tr>
                        <td>@if($n->image)<img src="{{ $n->image_url }}" style="height:50px;width:80px;object-fit:cover;border-radius:4px;">@endif</td>
                        <td><strong>{{ $n->title }}</strong><br><code class="small">{{ $n->slug }}</code></td>
                        <td><span class="badge bg-secondary">{{ $n->category ?? '-' }}</span></td>
                        <td>{{ $n->published_at?->format('d M Y') ?? '-' }}</td>
                        <td>@if($n->is_active)<span class="badge-soft success">Aktif</span>@else<span class="badge-soft danger">Nonaktif</span>@endif</td>
                        <td>
                            <a href="{{ route('admin.news.edit', $n) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.news.destroy', $n) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $news->links() }}</div>
@endsection
