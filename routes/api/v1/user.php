<?php

use Illuminate\Support\Facades\Route;

$category = 'users';

if (!isset($version)) {
    $version = 'v1';
}

Route::namespace('App\Http\Controllers\Api\User')
    ->prefix("{$version}/{$category}")
    ->group(function () use ($prefix, $category) {
        $prefix .= $category;

        Route::post('/auth/login', 'AuthController@login')->name("{$prefix}.auth.login");

        Route::middleware(['auth:sanctum', 'auth-permission:user'])
            ->group(function () use ($prefix) {
                add_api_module_routes($prefix, 'portfolio', [
                    'prefix' => 'portfolios',
                    'name' => 'portfolios'
                ]);

                add_api_module_routes($prefix, 'watchlist', [
                    'prefix' => 'watchlists',
                    'name' => 'watchlists'
                ]);
            });
    });
