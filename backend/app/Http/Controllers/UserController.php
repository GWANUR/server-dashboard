<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select(
            'id',
            'fullname',
            'name',
            'email',
            'type',
            'created_at'
        )->get();

        return response()->json($users);
    }
}