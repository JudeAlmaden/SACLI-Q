<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Queue extends Model
{
    protected $table = 'queues';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'status'
    ];

    public function windows(): HasMany
    {
        return $this->hasMany(Window::class);
    }

    public function queued()
    {
        return $this->hasMany(Ticket::class);
    }
}
