<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'starts_at', 'location'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime'];
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
