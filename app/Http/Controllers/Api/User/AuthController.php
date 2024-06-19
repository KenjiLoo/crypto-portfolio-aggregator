<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\User\BaseController;
use App\Models\User;
use App\Resources\User\User as UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, Hash};

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\User';
        $this->resource = 'App\Resources\User\User';
        // $this->moduleKey = 'admin';
        // $this->moduleName = 'admins';
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|max:20',
            'password' => 'required|string',
        ]);

        $fields = $request->only(['username', 'password']);

        //Check username
        $user = User::where('username', $fields['username'])->first();

        //Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return api()->unauthenticated()
                ->message(__('api.admin.invalid_credential'))
                ->flush();
        }

        $token = $user->createToken('user-token')->plainTextToken;

        $user->save();

        return api()->ok()
            ->data([
                'user' => new UserResource($user),
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
