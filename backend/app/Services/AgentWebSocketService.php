<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AgentWebSocketService
{
    /**
     * @var AgentSocketConnection[]
     */
    protected array $connections = [];

    public function registerConnection(AgentSocketConnection $connection): void
    {
        $this->connections[$connection->id] = $connection;

        Log::info("Agent connected", [
            'connection' => $connection->id,
        ]);
    }

    public function disconnect(AgentSocketConnection $connection): void
    {
        unset($this->connections[$connection->id]);

        Log::info("Agent disconnected", [
            'connection' => $connection->id,
        ]);

        $connection->close();
    }

    public function handleMessage(
        AgentSocketConnection $connection,
        string $payload
    ): void {

        $message = json_decode($payload, true);

        if (!is_array($message)) {
            return;
        }

        switch ($message['type'] ?? '') {

            case 'auth':
                $this->handleAuth($connection, $message);
                break;

            case 'stats':
                $this->handleStats($connection, $message);
                break;

            case 'heartbeat':
                break;

            default:
                Log::warning("Unknown message", $message);
        }
    }

    protected function handleAuth(AgentSocketConnection $connection, array $message): void
    {
        $payload = $message['payload'];

        Agent::updateOrCreate(
            [
                'agent_id' => $payload['agent_id'],
            ],
            [
                'status' => 'online',
                'last_seen' => now(),
            ]
        );

        $connection->send([
            'type' => 'auth_ok',
        ]);
    }

    protected function handleStats(AgentSocketConnection $connection, array $message): void
    {
        $payload = $message['payload'];
        $stats = $payload['stats'];

        Agent::where('agent_id', $payload['agent_id'])
            ->update([
                'hostname' => $stats['hostname'],
                'os' => $stats['os'],
                'arch' => $stats['arch'],

                'cpu_usage' => $stats['cpu']['usage'],
                'memory_percent' => $stats['memory']['percent'],
                'disk_percent' => $stats['disk']['percent'],

                'status' => 'online',
                'last_seen' => now(),
            ]);
    }
}