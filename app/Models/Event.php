<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = ['owner_id', 'name', 'starts_at', 'location', 'partner_1', 'partner_2'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (blank($event->invitation_token)) {
                $event->invitation_token = self::generateToken();
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Unguessable, non-sequential random token (62^32 combinations).
     * Retries on the (astronomically unlikely) unique-index collision.
     */
    public static function generateToken(): string
    {
        for ($i = 0; $i < 5; $i++) {
            $token = Str::random(32);
            if (! static::where('invitation_token', $token)->exists()) {
                return $token;
            }
        }

        abort(500, 'Gagal membuat token undangan.');
    }

    public function findByToken(string $token): ?static
    {
        return static::where('invitation_token', $token)->first();
    }
}
