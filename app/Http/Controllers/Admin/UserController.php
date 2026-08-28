<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->when(
                auth()->user()?->role !== 'developer',
                fn ($query) => $query->where('role', '!=', 'developer')
            )
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in($this->assignableRoles())],
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorizeDeveloperAccount($user);

        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeDeveloperAccount($user);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => ['required', Rule::in($this->assignableRoles())],
        ]);
        if (empty($data['password'])) unset($data['password']);
        else $data['password'] = Hash::make($data['password']);
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeDeveloperAccount($user);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Tidak bisa menghapus akun sendiri.']);
        }
        $user->delete();
        return back()->with('success', 'User dihapus.');
    }

    private function assignableRoles(): array
    {
        return auth()->user()?->role === 'developer'
            ? ['admin', 'editor', 'developer']
            : ['admin', 'editor'];
    }

    private function authorizeDeveloperAccount(User $user): void
    {
        if ($user->role === 'developer') {
            abort_unless(auth()->user()?->role === 'developer', 403);
        }
    }
}
