@extends('admin.layout')
@section('title', 'Kemitraan')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1">Calon Mitra</h4>
        <div class="text-muted small">Pengajuan usaha toko plastik dari website.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.settings.index', 'partnership') }}" class="btn btn-outline-primary"><i class="fas fa-sliders-h"></i> Atur Form</a>
        <a href="{{ route('site.partnership') }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-external-link-alt"></i> Lihat Form</a>
    </div>
</div>

<div class="row g-3 mb-3">
    @foreach($statuses as $key => $label)
        <div class="col-md col-6">
            <a href="{{ route('admin.partnerships.index', ['status' => $key]) }}" class="text-decoration-none">
                <div class="stat-card {{ $activeStatus === $key ? 'border border-danger' : '' }}">
                    <div class="label">{{ $label }}</div>
                    <div class="value">{{ number_format($statusCounts[$key] ?? 0) }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('admin.partnerships.index') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label">Cari Prospek</label>
            <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Nama, WhatsApp, email, atau kota">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected($activeStatus === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-search"></i> Terapkan</button>
            <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Tanggal</th><th>Calon Mitra</th><th>Lokasi</th><th>Profil Usaha</th><th>Modal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td class="text-nowrap"><strong>{{ $application->created_at->format('d M Y') }}</strong><br><small class="text-muted">{{ $application->created_at->format('H:i') }}</small></td>
                        <td><strong>{{ $application->name }}</strong><br><a href="https://wa.me/{{ preg_replace('/\D+/', '', $application->whatsapp) }}" target="_blank" class="small text-decoration-none"><i class="fab fa-whatsapp text-success"></i> {{ $application->whatsapp }}</a></td>
                        <td>{{ $application->city }}</td>
                        <td><small>{{ $application->business_stage_label }}</small></td>
                        <td><small>{{ $application->capital_range_label }}</small></td>
                        <td><span class="badge bg-{{ ['new'=>'danger','contacted'=>'info','qualified'=>'warning','completed'=>'success','rejected'=>'secondary'][$application->status] ?? 'secondary' }}">{{ $application->status_label }}</span></td>
                        <td><a href="{{ route('admin.partnerships.show', $application) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Belum ada pengajuan kemitraan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $applications->links() }}</div>
@endsection
