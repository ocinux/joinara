<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class CreateOwnerCommand extends Command
{
    protected $signature = 'joinara:owner';
    protected $description = 'Buat/update owner pertama untuk Joinara (MVP). Tidak menulis kredensial ke file/config.';

    public function handle(): int
    {
        $this->info('=== JOINARA OWNER SETUP ===');
        $this->line('Isi email dan password untuk owner. Password ditulis aman (hashed) saja.');

        $email = $this->ask('Email owner', null, fn (string $e) => Validator::make(['e' => $e], ['e' => 'required|email'])->errors()->isEmpty() ? $e : null);
        if ($email === null) {
            $this->error('Email tidak valid. Abort.');
            return Command::FAILURE;
        }

        // Support existing user — allow overwrite with new password
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $email,
                'password' => '',
            ]
        );

        $password = $this->secret('Password');
        if ($password === null || strlen($password) < 6) {
            $this->error('Password minimal 6 karakter. Abort.');
            return Command::FAILURE;
        }

        $confirmed = $this->secret('Konfirmasi password');
        if ($confirmed !== $password) {
            $this->error('Password tidak cocok. Abort.');
            return Command::FAILURE;
        }

        $user->update([
            'name' => Str::finish($this->ask('Nama lengkap owner', 'Owner'), ''),
            'password' => Hash::make($password),
        ]);

        $this->line("Owner '{$user->email}' tersimpan.");
        $this->warn('Simpan kredensial di tempat aman (password manager). Jangan commit kredensial.');
        return Command::SUCCESS;
    }
}
