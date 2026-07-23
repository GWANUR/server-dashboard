<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentMetric extends Model
{
    protected $fillable = [
        'agent_id',
        'metrics',
    ];

    protected $casts = [
        'metrics' => 'array',
    ];
}
