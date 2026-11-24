<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Anti-duplikat MVP-4: satu RSVP per tamu (update, bukan baris baru).
        Schema::table('rsvps', function (Blueprint $table) {
            $table->unique('guest_id');
        });
    }

    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropUnique(['guest_id']);
        });
    }
};
