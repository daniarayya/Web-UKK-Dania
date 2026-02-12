<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder akun ADMIN
        User::create([
            'username' => 'admin',
            'nama'     => 'Administrator',
            'password' => Hash::make('admin123'), // password login
            'nisn'     => null,                    // admin tidak punya nisn
            'role'     => 'admin'
        ]);
    }
}
