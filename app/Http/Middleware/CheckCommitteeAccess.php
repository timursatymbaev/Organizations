<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCommitteeAccess
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
        $committee = $request->route('committee');

        if (!Auth::user()->committees()->where('id', $committee->id)->exists()) {
            abort(403, 'У вас нет доступа к данному министерству.');
        }

        return $next($request);
    }
}
