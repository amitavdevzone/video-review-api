<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response('', 204);
    }
}
