<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TerminalController extends Controller
{
    public function execute(Request $request)
    {
        $command = $request->input('command', '');

        if (empty($command)) {
            return response()->json(['ok' => false, 'message' => 'Empty command'], 400);
        }

        $response = Http::timeout(10)->post('http://127.0.0.1:8080/terminal', [
            'command' => $command,
        ]);

        return response()->json([
            'ok' => $response->successful(),
            'output' => $response->json('output') ?? $response->body(),
            'status' => $response->status(),
        ]);
    }
}