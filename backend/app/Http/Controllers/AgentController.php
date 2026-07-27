<?php

namespace App\Http\Controllers;

use App\Services\AgentWebSocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Agent;
use App\Models\ServerAgent;
use App\Http\Resources\AgentResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    
    public function heartbeat(Request $request) 
    {
        
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

    public function handle(Request $request, Closure $next)
    {
        // Приоритет: заголовок, потом query, потом тело
        $token = $request->header('X-Agent-Token')
            ?? $request->query('token')
            ?? $request->input('panel.token');

        $uuid = $request->header('X-Agent-UUID')
            ?? $request->query('uuid')
            ?? $request->input('agent.uuid');

        if (!$token || !$uuid) {
            return response()->json(['error' => 'Missing token or UUID'], 401);
        }

        $agent = \App\Models\ServerAgent::where('uuid', $uuid)
            ->where('token', $token)
            ->first();

        if (!$agent || !$agent->is_active) {
            // Не раскрывай причину ошибки
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $agent->update(['last_seen_at' => now()]);
        $request->merge(['agent' => $agent]);

        return $next($request);
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
