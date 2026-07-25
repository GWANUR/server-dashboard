<?php

namespace App\Http\Controllers;

use App\Services\AgentWebSocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Agent;
use App\Models\ServerAgent;
use App\Http\Resources\AgentResource;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $agent = Agent::first();

        if (!$agent) {
            return response()->json([
                'message' => 'No agents found'
            ], 404);
        }

        return new AgentResource($agent);
    }

    public function getAll(Request $request)
    {
        $agents = ServerAgent::all();

        return response()->json(['allAgents' => $agents]);
    }

    public function saveAgent(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'token' => 'required|string',
        ]);

        $agent=new Agent();

        ServerAgent::create([
            'user_id'  => Auth::id(),
            'name'     => $data['name'],
            'token'    => Hash::make($data['token']),
            'enabled'  => false,
        ]);

        return response()->json([
            'message' => 'Server Agent Created'
        ]);
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
