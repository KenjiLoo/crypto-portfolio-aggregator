<?php

use Illuminate\Support\Facades\Route;

if (!isset($version)) {
    $version = 'v1';
}

Route::namespace('App\Http\Controllers\Api\SiteGroup')
    ->prefix($version . '/groups')
    ->group(function () use ($prefix) {

        $prefix .= 'groups';

        Route::post('/auth/login', 'AuthController@login')->name("{$prefix}.auth.login");

        Route::middleware(['auth:sanctum', 'auth-permission:site-group-admin', 'auth-status'])
            ->group(function () use ($prefix) {

                Route::get('/me', 'SiteGroupAdminController@getMeData')
                    ->name("{$prefix}.auth.get-me-data");
                Route::get('/get-my-profile', 'SiteGroupAdminController@getProfileData')
                    ->name("{$prefix}.auth.get-my-profile");
                Route::get('/site-modules', 'SiteModuleController@listSiteModule')
                    ->name("{$prefix}.site-modules");
                Route::post('/auth/change-password', 'AuthController@changePassword')
                    ->name("{$prefix}.auth.change-password");
                Route::post('/auth/logout', 'AuthController@logout')
                    ->name("{$prefix}.auth.logout");

                add_api_module_routes($prefix, 'site-group-admin', [
                    'prefix' => 'site-group-admins',
                    'name' => 'site-group-admins',
                    'controller' => 'SiteGroupAdminController',
                ]);

                add_api_module_routes($prefix, 'site-profile', [
                    'prefix' => 'site-profiles',
                    'name' => 'site-profiles',
                    'controller' => 'SiteProfileController',
                ]);

                add_api_module_routes($prefix, 'site', [
                    'prefix' => 'sites',
                    'name' => 'sites',
                    'controller' => 'SiteController',
                ]);
            });
    });
