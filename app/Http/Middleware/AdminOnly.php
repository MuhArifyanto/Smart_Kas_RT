<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Jika sudah login tapi bukan admin, arahkan ke dashboard warga
            if (auth()->check()) {
                return redirect()->route('warga.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
