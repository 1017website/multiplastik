@extends('admin.layout')
@section('title', 'Hero Slider')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Daftar Slide</h4>
    <a href="{{ route('admin.slides.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Slide</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>BG</th><th>Tag</th><th>Title</th><th>Urut</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($slides as $s)
                    <tr>
                        <td>@if($s->background_image)<img src="{{ $s->background_url }}" style="height:50px;width:90px;object-fit:cover;border-radius:4px;">@endif</td>
                        <td>{{ $s->tag }}</td>
                        <td><strong>{{ $s->title_top }} <span class="text-primary">{{ $s->title_em }}</span> {{ $s->title_bottom }}</strong></td>
                        <td>{{ $s->sort_order }}</td>
                        <td>@if($s->is_active)<span class="badge-soft success">Aktif</span>@else<span class="badge-soft danger">Nonaktif</span>@endif</td>
                        <td>
                            <a href="{{ route('admin.slides.edit', $s) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.slides.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus slide ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada slide.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $slides->links() }}</div>
@endsection
