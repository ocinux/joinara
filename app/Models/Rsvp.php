<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = ['guest_id', 'event_id', 'status', 'plus_ones', 'message'];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
