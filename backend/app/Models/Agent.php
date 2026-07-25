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
        'cpu_usage',
        'memory_percent',
        'disk_percent',
        'status',
        'last_seen',
    ];
}