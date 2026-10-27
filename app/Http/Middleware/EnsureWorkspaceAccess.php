<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspaceAccess
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $workspace = $request->route('workspace');
        $user = $request->user();

        if (
            ! $user ||
            ! $workspace ||
            ! $user->workspaces()
                ->where('slug', $workspace)
                ->exists()
        ) {
            abort(404);
        }

        return $next($request);
    }
}