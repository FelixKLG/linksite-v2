<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsLinked
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->discord_id) {
            return redirect()->route('auth.discord');
        }
        return $next($request);
    }
}
