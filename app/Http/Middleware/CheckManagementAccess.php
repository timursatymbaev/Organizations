<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckManagementAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $management = $request->route('management');

        if (!Auth::user()->managements()->where('id', $management->id)->exists()) {
            abort(403, 'У вас нет доступа к данному управлению.');
        }

        return $next($request);
    }
}
