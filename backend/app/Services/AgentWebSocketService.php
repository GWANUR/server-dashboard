<?php

namespace App\Services;

use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel;
use BeyondCode\LaravelWebSockets\WebSockets\Server;
use Illuminate\Support\Facades\Log;
use App\Models\Agent;
use App\Models\ServerAgent;

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
        Log::info('Incoming message', $message);

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
        Log::info('AUTH MESSAGE', $message);

        
        $payload = $message['payload'];

        $token = $message['payload']['token'] ?? null;

        if (is_null($token)) {
            $connection->send([
                'type' => 'auth_no',
                'message' => 'Token is null',
            ]);
            return;
        }

        $agent = ServerAgent::where('token', $token)->first();

        if (!$agent) {
            Log::warning('Auth rejected: agent not found');
            $connection->close();
            return;
        }
            
        $payload = $message['payload'] ?? [];

        $data = [
            'agent_id'   => $payload['agent_id'],
        ];

        $agent->update($data);

        $connection->send([
            'type' => 'auth_ok',
            'agent_id' => $agent->agent_id,
        ]);
    }

    protected function handleStats(AgentSocketConnection $connection, array $message): void
    {
        $payload = $message['payload'];
        $stats = $payload['stats'];

    Agent::where('agent_id', $payload['agent_id'])->update([
        'output'    => $payload['stats'],
        'last_seen' => now(),
    ]);
    }
}