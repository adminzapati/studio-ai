<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleSlug): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!\App\Services\ModuleService::hasAccess(auth()->id(), $moduleSlug)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Module access denied'], 403);
            }
            abort(403, 'You do not have access to this module. Please upgrade your plan.');
        }

        return $next($request);
    }
}
