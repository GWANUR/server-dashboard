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

    protected function handleAuth(
        AgentSocketConnection $connection,
        array $message
    ): void {

        Log::info("Agent auth", $message);

        $connection->send([
            'type' => 'auth_ok'
        ]);
    }

    protected function handleStats(
        AgentSocketConnection $connection,
        array $message
    ): void {

        Log::info("Agent stats", [
            'agent' => $message['agent_id'] ?? null,
            'stats' => $message['stats'] ?? [],
        ]);
    }
}