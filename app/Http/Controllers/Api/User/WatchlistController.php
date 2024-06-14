<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;

class WatchlistController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Watchlist';
        $this->resource = 'App\Resources\User\Watchlist';
    }
}
