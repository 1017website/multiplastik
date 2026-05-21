@extends('admin.layout')
@section('title', 'User Admin')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">User Admin</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td><strong>{{ $u->name }}</strong> @if($u->id === auth()->id())<span class="badge bg-info">Anda</span>@endif</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($u->role) }}</span></td>
                        <td>
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
