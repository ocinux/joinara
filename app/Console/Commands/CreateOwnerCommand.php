<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class CreateOwnerCommand extends Command
{
    protected $signature = 'joinara:owner {--demo : Buat owner demo + event contoh, password acak dicetak sekali}';
    protected $description = 'Buat/update owner pertama untuk Joinara (MVP). Tidak menulis kredensial ke file/config.';

    public function handle(): int
    {
        if ($this->option('demo')) {
            return $this->demo();
        }

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

    // ponytail: demo data hanya untuk uji lokal; hapus bila event demo dibuat manual
    private function demo(): int
    {
        $email = 'demo@joinara.test';
        $password = Str::random(10);

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Owner Demo', 'password' => Hash::make($password)]
        );

        if ($user->events()->count() === 0) {
            $user->events()->create([
                'name' => 'Pernikahan Demo',
                'partner_1' => 'Rina',
                'partner_2' => 'Bima',
                'starts_at' => now()->addDays(30)->setTime(10, 0),
                'location' => 'Balai Kartini, Jakarta',
            ]);
        }

        $event = $user->events()->first();

        $this->info('=== JOINARA DEMO ===');
        $this->line('Email:    ' . $user->email);
        $this->line('Password: ' . $password);
        $this->line('Event:    ' . $event->name . ' (undangan: /u/' . $event->invitation_token . ')');
        $this->warn('Password ini hanya ditampilkan sekali. Reset dengan jalankan ulang --demo.');
        return Command::SUCCESS;
    }
}
