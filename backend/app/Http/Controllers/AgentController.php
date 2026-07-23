<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgentController extends Controller
{
    public function heartbeat(Request $request)
    {
        $payload = $request->all();
        Cache::put('agent:'.$payload['agent_id'] ?? 'unknown', $payload, now()->addMinutes(5));

        return response()->json(['ok' => true]);
    }
}
