<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Tidak ada kredensial default yang dibuat di sini (klarifikasi founder
     * issue #2). Akun owner dibuat via artisan: php artisan joinara:owner
     */
    public function run(): void
    {
        $this->command?->info('Tidak ada kredensial default. Buat akun owner via: php artisan joinara:owner');
    }
}
