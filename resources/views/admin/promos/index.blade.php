@extends('admin.layout')
@section('title', 'Promo Bar')
@section('content')

<div class="alert alert-info">
    Teks ini muncul di <strong>marquee promo bar</strong> di bawah hero slider, berjalan terus dari kanan ke kiri.
</div>

<div class="card p-4 mb-3">
    <h6>Tambah Promo Baru</h6>
    <form method="POST" action="{{ route('admin.promos.store') }}">
        @csrf
        <div class="row g-2 align-items-end">
            <div class="col-md-7">
                <label class="form-label">Teks Promo</label>
                <input type="text" name="text" class="form-control" required placeholder="🔥 Stok Hok Cup N-Series tersedia lengkap...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>
            <div class="col-md-2">
                <div class="form-check form-switch mt-3">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="add_active" checked>
                    <label class="form-check-label" for="add_active">Aktif</label>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="fas fa-plus"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th width="50%">Teks</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($promos as $p)
                    <tr>
                        <form method="POST" action="{{ route('admin.promos.update', $p) }}">
                            @csrf @method('PUT')
                            <td><input type="text" name="text" class="form-control form-control-sm" value="{{ $p->text }}" required></td>
                            <td><input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $p->sort_order }}" style="width:80px;"></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $p->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
                        </form>
                        <form action="{{ route('admin.promos.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus promo ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada promo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
