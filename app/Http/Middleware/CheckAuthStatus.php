<?php

namespace App\Http\Middleware;

use App\Models\{SiteGroupAdmin};
use Closure;

class CheckAuthStatus
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user instanceof SiteGroupAdmin) {    
            if (!$user->isSiteGroupActive() || !$user->isSiteActive()) {
                return api()->forbidden()->message(__('api.site.status_inactive'))->flush();
            }
        }

        switch ($user->status) {
            case 'INACTIVE':
                return api()->forbidden()->errors(__('api.general.unauthorized_request'))->flush();
                break;
            default:
                break;
        }

        return $next($request);
    }
}
