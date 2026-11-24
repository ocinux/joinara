<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = ['event_id', 'guest_id', 'method', 'checked_in_at'];

    protected function casts(): array
    {
        return ['checked_in_at' => 'datetime'];
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
