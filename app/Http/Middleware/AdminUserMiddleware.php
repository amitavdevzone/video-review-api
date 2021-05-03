<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AdminUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::find($request->user()->id);

        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => __('auth.not_allowed')], 401);
        }

        return $next($request);
    }
}
