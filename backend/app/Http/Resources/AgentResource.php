<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'cpu' => [
                'usage' => $this->cpu_usage,
            ],

            'ram' => [
                'percent' => $this->memory_percent,
            ],

            'disk' => [
                'percent' => $this->disk_percent,
            ],

            'network' => [
                'received_bytes' => $this->network_rx,
                'sent_bytes' => $this->network_tx,
            ],

            'uptime' => $this->uptime,
            'status' => $this->status,
            'hostname' => $this->hostname,
        ];
    }
}