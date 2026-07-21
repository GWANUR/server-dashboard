<?php
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login'])->middleware();

Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index'])->middleware();