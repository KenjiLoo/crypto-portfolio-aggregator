<?php

namespace App\Http\Controllers\Api\SiteGroup;

use App\Http\Controllers\Api\BaseApiController;

class SiteProfileController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\SiteProfile';
        $this->resource = 'App\Resources\SiteGroup\SiteProfile';
        $this->moduleKey = 'site-profile';
        $this->moduleName = 'site-profiles';
    }
}
