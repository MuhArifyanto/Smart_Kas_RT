<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Update last_seen_at via query builder to avoid triggering model events if not needed
            // and for slightly better performance on every request.
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['last_seen_at' => now()]);
        }

        return $next($request);
    }
}
