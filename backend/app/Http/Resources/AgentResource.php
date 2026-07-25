<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'cpu'      => $this->output['cpu'] ?? [],
            'memory'   => $this->output['memory'] ?? [],
            'disk'     => $this->output['disk'] ?? [],
            'network'  => $this->output['network'] ?? [],
            'uptime'   => $this->output['uptime'] ?? 0,
            'status'   => $this->output['status'] ?? 'offline',

            'hostname' => $this->hostname,
            'os'       => $this->os,
            'arch'     => $this->arch,
            'last_seen'=> $this->last_seen,
        ];
    }
}