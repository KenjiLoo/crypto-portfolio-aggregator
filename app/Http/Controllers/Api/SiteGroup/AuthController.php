<?php

namespace App\Http\Controllers\Api\SiteGroup;

use App\Models\SiteGroupAdmin;
use App\Resources\SiteGroup\SiteGroupAdmin as SiteGroupAdminResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, Hash};

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|max:20',
            'password' => 'required|string',
        ]);

        $fields = $request->only('username', 'password');

        //Check username
        $siteGroupAdmin = SiteGroupAdmin::where('username', $fields['username'])->first();

        //Check password
        if (!$siteGroupAdmin || !Hash::check($fields['password'], $siteGroupAdmin->password)) {
            return api()->unauthenticated()
                ->message(__('api.admin.invalid_credential'))
                ->flush();
        }

        //Check site group & site status
        if (!$siteGroupAdmin->isSiteGroupActive() || !$siteGroupAdmin->isSiteActive()) {
            return api()->forbidden()
                ->message(__('api.site.status_inactive'))
                ->flush();
        }

        $token = $siteGroupAdmin->createToken('group-token')->plainTextToken;

        $siteGroupAdmin->last_login_at = Carbon::now();
        $siteGroupAdmin->save();

        return api()->ok()
            ->data([
                'user' => new SiteGroupAdminResource($siteGroupAdmin),
                'token' => $token,
            ])->flush();
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|password_match:' . $user->password,
            'new_password' => 'required|string',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user->password = Hash::make($request->new_password);

        if ($user->save()) {
            return api()->ok()
                ->message(__('api.admin.password_success'))
                ->flush();
        }

        return api()->exception()
            ->message(__('api.general.internal_error'))
            ->flush();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $currentToken = trim(str_replace('Bearer', '', $request->header('Authorization')));
        $id = explode('|', $currentToken)[0];

        Cache::forget("personal-access-token: {$id}");

        return api()->ok()->flush();
    }
}
