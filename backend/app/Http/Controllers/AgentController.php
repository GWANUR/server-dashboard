<?php

namespace App\Http\Controllers;

use App\Services\AgentWebSocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Agent;
use App\Http\Resources;

class AgentController extends Controller
{
    public function index()
    {
        return new AgentResource(Agent::first());
    }

    public function heartbeat(Request $request)
    {
        $payload = $request->all();
        Cache::put('agent:'.($payload['agent_id'] ?? 'unknown'), $payload, now()->addMinutes(5));

        return response()->json(['ok' => true]);
    }

    public function dispatchCommand(Request $request, AgentWebSocketService $service)
    {
        $payload = $request->validate([
            'agent_id' => ['required', 'string'],
            'type' => ['required', 'string'],
            'payload' => ['nullable', 'array'],
        ]);

        $service->sendCommand($payload['agent_id'], [
            'type' => $payload['type'],
            'payload' => $payload['payload'] ?? [],
        ]);

        return response()->json(['ok' => true]);
    }
}
