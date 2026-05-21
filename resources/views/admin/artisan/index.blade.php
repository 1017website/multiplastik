@extends('admin.layout')
@section('title', 'Artisan Console')
@section('content')

<div class="alert alert-warning mb-3">
    <i class="fas fa-exclamation-triangle me-1"></i>
    <strong>Perhatian:</strong> Beberapa command seperti <code>migrate</code> bersifat permanen. Pastikan sudah backup database sebelum menjalankannya.
</div>

@if(session('artisan_success'))
    @php $res = session('artisan_success'); @endphp
    <div class="card mb-4" style="border-left:4px solid #059669;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <code style="color:#059669;font-size:14px;"><i class="fas fa-check-circle me-1"></i>{{ $res['command'] }}</code>
                <span class="text-muted small">{{ $res['duration'] }}s</span>
            </div>
            <pre style="background:#0f1117;color:#6ee7b7;padding:16px;border-radius:6px;font-size:12px;margin:0;white-space:pre-wrap;max-height:300px;overflow-y:auto;">{{ $res['output'] }}</pre>
        </div>
    </div>
@endif

@if(session('artisan_error'))
    @php $res = session('artisan_error'); @endphp
    <div class="card mb-4" style="border-left:4px solid #dc3545;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <code style="color:#dc3545;font-size:14px;"><i class="fas fa-times-circle me-1"></i>{{ $res['command'] }}</code>
                <span class="text-muted small">{{ $res['duration'] }}s</span>
            </div>
            <pre style="background:#0f1117;color:#fca5a5;padding:16px;border-radius:6px;font-size:12px;margin:0;white-space:pre-wrap;max-height:300px;overflow-y:auto;">{{ $res['output'] }}</pre>
        </div>
    </div>
@endif

{{-- Command Grid --}}
<div class="row g-3 mb-4">
    @php
        $groups = [
            'Cache & Optimize' => ['optimize','optimize:clear','cache:clear','view:clear','config:clear','route:clear','view:cache','config:cache','route:cache'],
            'Database'         => ['migrate','migrate:status','db:seed'],
            'Storage & Queue'  => ['storage:link','queue:restart'],
        ];
    @endphp

    @foreach($groups as $groupName => $cmds)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body p-3">
                    <h6 class="mb-3" style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;">{{ $groupName }}</h6>
                    <div class="d-flex flex-column gap-2">
                        @foreach($cmds as $cmd)
                            @if(isset($allowed[$cmd]))
                                <form method="POST" action="{{ route('admin.artisan.run') }}" onsubmit="return confirmRun('{{ $cmd }}')">
                                    @csrf
                                    <input type="hidden" name="command" value="{{ $cmd }}">
                                    <button type="submit" class="btn btn-sm w-100 text-start
                                        {{ in_array($cmd, ['migrate','db:seed']) ? 'btn-outline-danger' : 'btn-outline-secondary' }}"
                                        style="font-family:monospace;font-size:12px;padding:7px 12px;">
                                        <i class="fas fa-play me-2" style="font-size:10px;opacity:.6;"></i>
                                        {{ $cmd }}
                                        <span class="float-end text-muted" style="font-size:10px;font-family:'Inter',sans-serif;font-weight:400;">{{ $allowed[$cmd][1] ?? '' }}</span>
                                    </button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Log History --}}
<div class="card">
    <div class="card-body p-3">
        <h6 class="mb-3">History (50 terakhir)</h6>
        <div style="max-height:400px;overflow-y:auto;">
            <table class="table table-sm mb-0">
                <thead>
                    <tr><th>Waktu</th><th>Command</th><th>Durasi</th><th>User</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="white-space:nowrap;font-size:12px;">{{ $log->created_at->format('d M H:i') }}</td>
                            <td><code style="font-size:12px;">{{ $log->command }}</code></td>
                            <td style="font-size:12px;">{{ $log->duration }}s</td>
                            <td style="font-size:12px;">{{ $log->user->name ?? '-' }}</td>
                            <td>
                                @if($log->success)
                                    <span class="badge-soft success">OK</span>
                                @else
                                    <span class="badge-soft danger">Error</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" style="font-size:11px;padding:2px 8px;"
                                    onclick="toggleOutput('log-{{ $log->id }}')">Output</button>
                            </td>
                        </tr>
                        <tr id="log-{{ $log->id }}" style="display:none;">
                            <td colspan="6" style="padding:0;">
                                <pre style="background:#0f1117;color:{{ $log->success ? '#6ee7b7' : '#fca5a5' }};padding:12px 16px;margin:0;font-size:11px;white-space:pre-wrap;max-height:200px;overflow-y:auto;">{{ $log->output }}</pre>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Belum ada history.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmRun(cmd) {
    const dangerous = ['migrate', 'db:seed'];
    if (dangerous.includes(cmd)) {
        return confirm('⚠️ Command "' + cmd + '" bersifat permanen dan tidak bisa dibatalkan.\n\nLanjutkan?');
    }
    return true;
}
function toggleOutput(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endpush
@endsection
