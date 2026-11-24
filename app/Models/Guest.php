<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['event_id', 'name', 'phone', 'email', 'unique_code'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }
}
