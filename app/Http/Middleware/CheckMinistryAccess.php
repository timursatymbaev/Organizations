<?php

namespace App\Http\Middleware;

use App\Models\Ministry;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMinistryAccess
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
        $ministry = $request->route('ministry');

        if (!Auth::user()->ministries()->where('id', $ministry->id)->exists()) {
            abort(403, 'У вас нет доступа к данному министерству.');
        }

        return $next($request);
    }
}
