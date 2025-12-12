<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\HttpStatus;
use Illuminate\Support\Facades\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::check() || !Auth::user()->is_admin) {
            return Response::apiResponse(HttpStatus::FORBIDDEN, null, 'Unauthorized: Admin access required.');
        }

        return $next($request);
    }
}
