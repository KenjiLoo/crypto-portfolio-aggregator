<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;

class ModuleController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Module';
        $this->resource = 'App\Resources\Admin\Module';
        $this->moduleKey = 'module';
        $this->moduleName = 'modules';
    }
}
