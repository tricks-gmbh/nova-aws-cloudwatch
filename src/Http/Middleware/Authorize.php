<?php

namespace Tricks\NovaAwsCloudwatch\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Tricks\NovaAwsCloudwatch\NovaAwsCloudwatch;

class Authorize
{
    /**
     * @param  Closure(Request):mixed  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        return optional($tool)->authorize($request) ? $next($request) : abort(403);
    }

    public function matchesTool(Tool $tool): bool
    {
        return $tool instanceof NovaAwsCloudwatch;
    }
}
