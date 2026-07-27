<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ServerAgent;

class AgentAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $payload = $message['payload'];

        $token = $message['payload']['token'] ?? null;

        if (!$token) {
            return response()->json(['error' => 'Missing Agent-Token'], 403);
        }

        $agent = ServerAgent::where('token', $token)->first();

        if (!$agent) {
            return response()->json(['error' => 'Not found agent'], 403);
        }

        $request->attributes->add(['agent' => $agent]);

        return $next($request);
    }

}
