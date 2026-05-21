@extends('admin.layout')
@section('title', $user->exists ? 'Edit User' : 'Tambah User')
@section('content')

<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
    @csrf
    @if($user->exists) @method('PUT') @endif

    <div class="card p-4" style="max-width:560px;">
        <div class="mb-3">
            <label class="form-label">Nama *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role *</label>
            <select name="role" class="form-select" required>
                <option value="admin" @selected(old('role', $user->role)=='admin')>Admin (akses penuh)</option>
                <option value="editor" @selected(old('role', $user->role)=='editor')>Editor</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Password {{ $user->exists ? '(kosongkan jika tidak diubah)' : '*' }}</label>
            <input type="password" name="password" class="form-control" {{ $user->exists ? '' : 'required' }}>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
