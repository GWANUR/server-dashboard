<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'agent_id',
        'hostname',
        'os',
        'arch',
        'output',
        'last_seen',
    ];

    protected $casts = [
        'output' => 'array',
    ];
}