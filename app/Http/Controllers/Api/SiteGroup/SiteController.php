<?php

namespace App\Http\Controllers\Api\SiteGroup;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;

class SiteController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Site';
        $this->resource = 'App\Resources\SiteGroup\Site';
        $this->moduleKey = 'site';
        $this->moduleName = 'sites';
    }
}
