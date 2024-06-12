<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;

class SiteGroupController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = "App\Models\SiteGroup";
        $this->resource = "App\Resources\Admin\SiteGroup";
        $this->moduleKey = "site-group";
        $this->moduleName = "site-groups";
    }
}
