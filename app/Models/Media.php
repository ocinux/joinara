<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = ['event_id', 'guest_id', 'path', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
