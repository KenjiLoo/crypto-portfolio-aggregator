<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\BaseController;
use App\Models\Admin;
use App\Resources\Admin\Admin as AdminResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, Hash};

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Admin';
        $this->resource = 'App\Resources\Admin';
        $this->moduleKey = 'admin';
        $this->moduleName = 'admins';
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|max:20',
            'password' => 'required|string',
        ]);

        $fields = $request->only(['username', 'password']);

        //Check username
        $admin = Admin::where('username', $fields['username'])->first();

        //Check password
        if (!$admin || !Hash::check($fields['password'], $admin->password)) {
            return api()->unauthenticated()
                ->message(__('api.admin.invalid_credential'))
                ->flush();
        }

        if ($admin->status != Admin::STATUS_ACTIVE) {
            return api()->unauthenticated()
                ->message(__('api.admin.account_deactivated'))
                ->flush();
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        $admin->last_login_at = Carbon::now();
        $admin->save();

        return api()->ok()
            ->data([
                'user' => new AdminResource($admin),
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

        Cache::forget("personal-access-token:{$id}");

        return api()->noContent()->flush();
    }
}
