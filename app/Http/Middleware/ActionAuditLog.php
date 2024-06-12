<?php

namespace App\Http\Middleware;

use App\Models\SiteGroupAdmin;
use Closure;

use App\Models\AuditLog;
use App\Models\Admin;

class ActionAuditLog
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function handle($request, Closure $next, $module, $action)
    {
        $response = $next($request);
        $user = $request->user();
        $userType = $userId = $username = null;

        if (!empty($user)) {
            if ($user instanceof Admin) {
                $userType = 'admin';
                $userId = $user->id;
                $username = $user->username;
            } else if ($user instanceof SiteGroupAdmin) {
                $userType = 'site_group';
                $userId = $user->id;
                $username = $user->username;
            } else {
                $userType = 'user';
                $userId = $user->id;
                $username = $user->name . '(' . $user->fullname . ')';
            }
        }

        $audit = new AuditLog;

        $audit->module = $module;
        $audit->action = $action;
        $audit->url = $request->url();
        $audit->status = $response->status();
        $audit->request = json_encode($request->all());
        $audit->response = $response->content();
        $audit->user_type = $userType;
        $audit->user_id = $userId;
        $audit->username = $username;
        $audit->ip = $request->ip();

        $audit->save();

        return $response;
    }
}
