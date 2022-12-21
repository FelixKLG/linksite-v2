<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasDiscord
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->discord_id) {
            return redirect()->route('linked');
        }
        return $next($request);
    }
}
