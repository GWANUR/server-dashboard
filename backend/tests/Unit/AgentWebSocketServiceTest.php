<?php

namespace Tests\Unit;

use App\Services\AgentConnectionInterface;
use App\Services\AgentWebSocketService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AgentWebSocketServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.agent.auth_token', 'super-secret-token');
    }

    public function test_valid_auth_registers_the_agent_and_returns_auth_ok(): void
    {
        $service = new AgentWebSocketService();
        $connection = new TestConnection();

        $service->registerConnection($connection);
        $service->handleMessage($connection, json_encode([
            'type' => 'auth',
            'agent_id' => 'server-agent-01',
            'token' => 'super-secret-token',
        ], JSON_THROW_ON_ERROR));

        $this->assertTrue($service->hasAuthenticatedAgent('server-agent-01'));
        $this->assertSame([
            json_encode(['type' => 'auth_ok']),
        ], $connection->sent); 
    }

    public function test_stats_are_persisted_to_cache_and_database(): void
    {
        $service = new AgentWebSocketService();
        $connection = new TestConnection();

        $service->registerConnection($connection);
        $service->handleMessage($connection, json_encode([
            'type' => 'auth',
            'agent_id' => 'server-agent-02',
            'token' => 'super-secret-token',
        ], JSON_THROW_ON_ERROR));

        $service->handleMessage($connection, json_encode([
            'type' => 'stats',
            'payload' => ['cpu' => 12.5, 'memory' => 66],
        ], JSON_THROW_ON_ERROR));

        $cached = Cache::get('agent:server-agent-02');

        $this->assertNotNull($cached);
        $this->assertSame(12.5, $cached['payload']['cpu']);
    }
}

class TestConnection implements AgentConnectionInterface
{
    public string $resourceId = 'test-conn';

    public array $sent = [];

    public bool $closed = false;

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function send(string $data): void
    {
        $this->sent[] = $data;
    }

    public function close(): void
    {
        $this->closed = true;
    }
}
