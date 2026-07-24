<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController
{
    public function userSettings()
    {
        $user = Auth::user();

        return response()->json([
            'settings' => $user->settings,
        ]);
    }
    public function dashboardSettings()
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            
        }

        return response()->json([
            'settings' => $user->settings,
        ]);
    }
}