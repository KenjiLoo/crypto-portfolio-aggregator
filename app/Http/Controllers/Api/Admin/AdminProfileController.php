<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;

class AdminProfileController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\AdminProfile';
        $this->resource = 'App\Resources\Admin\AdminProfile';
        $this->moduleKey = 'admin-profile';
        $this->moduleName = 'admin-profiles';
    }
}
