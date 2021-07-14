<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByDesc('id')
            ->paginate(20);

        return response(['data' => $users], 200);
    }
}
