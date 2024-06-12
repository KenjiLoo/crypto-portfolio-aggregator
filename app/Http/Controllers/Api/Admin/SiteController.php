<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;

class SiteController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Site';
        $this->resource = 'App\Resources\Admin\Site';
        $this->moduleKey = 'site';
        $this->moduleName = 'sites';
    }
}
