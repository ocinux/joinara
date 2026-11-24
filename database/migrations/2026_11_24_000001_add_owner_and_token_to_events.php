<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('owner_id')->after('id')->constrained('users')->cascadeOnDelete();
            $table->string('partner_1')->nullable();
            $table->string('partner_2')->nullable();
            $table->string('invitation_token')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
            $table->dropColumn(['partner_1', 'partner_2', 'invitation_token']);
        });
    }
};
