<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $adminExists = User::where('role', 'admin')->exists();

        if (!$adminExists) {
            return $next($request);
        }
        
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Não autenticado'], 401);
        }

        return $next($request);
    }
}
