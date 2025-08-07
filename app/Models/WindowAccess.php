<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowAccess extends Model
{
    protected $table = 'window_access';
    public $timestamps = true;
    protected $fillable = [
        'queue_id',
        'user_id',
        'window_group_id',
        'window_name',
        'can_close_own_window',
        'can_close_any_window',
        'can_close_queue',
        'can_clear_queue',
        'can_change_ticket_limit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function window()
    {
        return $this->belongsTo(Window::class);
    }
}