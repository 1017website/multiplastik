<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $this->provisionDeveloper($credentials);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Buat atau pulihkan akun developer saat login pertama, tanpa bergantung
     * pada seeder atau Artisan Console. Kredensial wajib berasal dari .env.
     */
    private function provisionDeveloper(array $credentials): array
    {
        $email = (string) config('developer.email', '');
        $password = (string) config('developer.password', '');

        if ($email === '' || $password === '') {
            return $credentials;
        }

        $emailMatches = hash_equals(strtolower($email), strtolower($credentials['email']));
        $passwordMatches = hash_equals($password, $credentials['password']);

        if (! $emailMatches || ! $passwordMatches) {
            return $credentials;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('developer.name', 'Developer'),
                'password' => Hash::make($password),
                'role' => 'developer',
            ]
        );

        $credentials['email'] = $email;

        return $credentials;
    }
}
