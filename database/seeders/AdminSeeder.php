<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Buat/perbarui satu akun Admin dari .env — bukan registrasi publik
     * (docs/PRD.md §3.1). Idempotent: aman dijalankan ulang.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@email.com');
        $password = env('ADMIN_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin FIF',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );
    }
}
