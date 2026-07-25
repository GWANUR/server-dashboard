<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ServerAgent extends Model
{
    protected $fillable = [
        'user_id',
        'agent_id',
        'name',
        'token',
        'enabled',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
