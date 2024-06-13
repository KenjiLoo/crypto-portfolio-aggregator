<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Exceptions\AuthPermissionDeniedException;
use Closure;

class CheckAuthPermission
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next, $type)
    {
        //temporary bypass
        $user = $request->user();
        switch ($type) {
            case 'user':
                if (!$user instanceof User) {
                    throw new AuthPermissionDeniedException();
                }
                break;
            default:
                // throw new AuthPermissionDeniedException();
                break;
        }

        return $next($request);
    }
}
