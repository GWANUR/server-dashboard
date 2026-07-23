<?php
use Illuminate\Http\Request;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\UserController;
use App\Services\SystemMonitorService;

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->middleware();

Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index'])->middleware();

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/system', [SystemMonitorService::class, 'getStats']);

Route::middleware('auth:sanctum')->post('/terminal', [TerminalController::class, 'execute']);
Route::post('/agent/heartbeat', [AgentController::class, 'heartbeat']);
Route::post('/agent/command', [AgentController::class, 'dispatchCommand'])->middleware('auth:sanctum');