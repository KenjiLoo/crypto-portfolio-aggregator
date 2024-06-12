<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Exceptions\AuthPermissionDeniedException;
use Closure;

class CheckSuperadmin
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next, $type)
    {
        $user = $request->user();
        if (!$user instanceof Admin || !$user->is_superadmin) {
            throw new AuthPermissionDeniedException();
        }

        return $next($request);
    }
}
