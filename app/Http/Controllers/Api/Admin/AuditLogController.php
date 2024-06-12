<?php

namespace App\Http\Controllers\Api\Admin;

class AuditLogController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\AuditLog';
        $this->resource = 'App\Resources\Admin\AuditLog';
        $this->moduleKey = 'audit-log';
        $this->moduleName = 'audit-logs';
    }
}
