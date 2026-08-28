<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DeveloperUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('developer.email');
        $password = config('developer.password');

        if (! $email || ! $password) {
            throw new RuntimeException('DEVELOPER_EMAIL dan DEVELOPER_PASSWORD wajib diisi.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('developer.name'),
                'password' => Hash::make($password),
                'role' => 'developer',
            ]
        );
    }
}
