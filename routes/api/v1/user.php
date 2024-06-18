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

        Route::get('/crypto/info', 'CryptoApiController@getCryptoInfo')->name("{$prefix}.crypto.info");

        Route::middleware(['auth:sanctum', 'auth-permission:user'])
            ->group(function () use ($prefix) {
                Route::post('/auth/change-password', 'AuthController@changePassword')
                    ->name("{$prefix}.auth.change-password");
                Route::post('/auth/logout', 'AuthController@logout')
                    ->name("{$prefix}.auth.logout");

                add_api_module_routes($prefix, 'user', [
                    'prefix' => 'users',
                    'name' => 'users',
                ]);

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
