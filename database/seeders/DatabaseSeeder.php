<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
public function run(): void
{
    // 2. Buat User Mahasiswa
    \App\Models\User::factory()->create([
        'name' => 'admin',
        'nik' => null,
        'nim' => '212121',    // Login pakai ini
        'role' => 'admin',
        'password' => bcrypt('password'),
    ]);
}
}
