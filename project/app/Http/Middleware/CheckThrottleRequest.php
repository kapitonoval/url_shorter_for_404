<?php

namespace App\Http\Middleware;

use App\Services\Links\LinkThrottleRequest;
use Closure;
use \Symfony\Component\HttpFoundation\Response;

class CheckThrottleRequest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $LinkThrottleRequest = new LinkThrottleRequest();
        if ($LinkThrottleRequest
            ->setParam($request->userAgent())
            ->setParam($request->ip())
            ->isBlocked()) {
            abort(Response::HTTP_TOO_MANY_REQUESTS);
        }
        return $next($request);
    }
}
