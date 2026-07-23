<?php

namespace App\Services;

use App\Models\AgentMetric;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AgentWebSocketService
{
    /** @var array<string, AgentConnectionInterface> */
    protected array $connections = [];

    /** @var array<string, string> */
    protected array $agents = [];

    /** @var array<string, array> */
    protected array $authenticatedAgents = [];

    public function registerConnection(AgentConnectionInterface $conn): void
    {
        $this->connections[$conn->getResourceId()] = $conn;
    }

    public function handleMessage(AgentConnectionInterface $from, string $message): void
    {
        $payload = json_decode($message, true);

        if (!is_array($payload)) {
            $from->send(json_encode(['type' => 'error', 'message' => 'Invalid message format']));
            $from->close();

            return;
        }

        if (($payload['type'] ?? null) === 'auth') {
            $this->handleAuth($from, $payload);

            return;
        }

        if (!isset($this->authenticatedAgents[$from->getResourceId()])) {
            $from->send(json_encode(['type' => 'auth_required']));
            $from->close();

            return;
        }

        if (($payload['type'] ?? null) === 'stats') {
            $this->handleStats($from, $payload);

            return;
        }

        $from->send(json_encode(['type' => 'unsupported', 'message' => 'Unsupported message type']));
    }

    public function disconnect(AgentConnectionInterface $conn): void
    {
        unset($this->connections[$conn->getResourceId()]);
        unset($this->authenticatedAgents[$conn->getResourceId()]);
    }

    public function hasAuthenticatedAgent(string $agentId): bool
    {
        foreach ($this->authenticatedAgents as $agent) {
            if (($agent['agent_id'] ?? null) === $agentId) {
                return true;
            }
        }

        return false;
    }

    protected function handleAuth(AgentConnectionInterface $from, array $payload): void
    {
        $agentId = (string) ($payload['agent_id'] ?? '');
        $token = (string) ($payload['token'] ?? '');
        $expectedToken = (string) config('services.agent.auth_token', env('AGENT_AUTH_TOKEN'));

        if ($agentId === '' || $token === '' || $token !== $expectedToken) {
            $from->send(json_encode(['type' => 'auth_failed']));
            $from->close();

            return;
        }

        $this->authenticatedAgents[$from->getResourceId()] = [
            'agent_id' => $agentId,
            'connected_at' => now()->toDateTimeString(),
        ];

        $this->agents[$agentId] = $from->getResourceId();

        $from->send(json_encode(['type' => 'auth_ok']));
    }

    protected function handleStats(AgentConnectionInterface $from, array $payload): void
    {
        $agentId = $this->authenticatedAgents[$from->getResourceId()]['agent_id'] ?? null;

        if (!$agentId) {
            return;
        }

        $stats = [
            'agent_id' => $agentId,
            'payload' => $payload['payload'] ?? [],
            'received_at' => now()->toDateTimeString(),
        ];

        Cache::put('agent:'.$agentId, $stats, now()->addMinutes(15));

        try {
            $metric = AgentMetric::query()->where('agent_id', $agentId)->first();

            if ($metric) {
                $metric->metrics = $stats;
                $metric->save();

                return;
            }

            AgentMetric::query()->create([
                'agent_id' => $agentId,
                'metrics' => $stats,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Agent metrics persistence failed', [
                'agent_id' => $agentId,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function sendCommand(string $agentId, array $message): void
    {
        if (!isset($this->agents[$agentId])) {
            return;
        }

        $connection = $this->connections[$this->agents[$agentId]] ?? null;

        if ($connection instanceof AgentConnectionInterface) {
            $connection->send(json_encode($message));
        }
    }
}
