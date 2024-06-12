<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\SiteGroupAdmin;
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
            case 'admin':
                if (!$user instanceof Admin) {
                    throw new AuthPermissionDeniedException();
                }
                break;
            case 'site-group-admin':
                if (!$user instanceof SiteGroupAdmin) {
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
