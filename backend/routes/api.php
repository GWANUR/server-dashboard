<?php
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);