<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;

class PortfolioController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Portfolio';
        $this->resource = 'App\Resources\User\Portfolio';
    }
}
