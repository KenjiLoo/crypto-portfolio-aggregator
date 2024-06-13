<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\User';
        $this->resource = 'App\Resources\User\User';
        // $this->moduleKey = 'admin';
        // $this->moduleName = 'admins';
    }
}
