<?php

use Illuminate\Support\Facades\Route;

$category = 'admins';

if (!isset($version)) {
    $version = 'v1';
}

Route::namespace('App\Http\Controllers\Api\Admin')
    ->prefix("{$version}/{$category}")
    ->group(function () use ($prefix, $category) {
        $prefix .= $category;

        Route::post('/auth/login', 'AuthController@login')->name("{$prefix}.auth.login");

        Route::middleware(['auth:sanctum', 'auth-permission:admin', 'auth-status'])
            ->group(function () use ($prefix) {

                Route::get('/me', "AdminController@getMeData")
                    ->name("{$prefix}.auth.get-me-data");
                Route::get('/get-my-profile', "AdminController@getProfileData")
                    ->name("{$prefix}.auth.get-my-profile");
                Route::post('/auth/change-password', 'AuthController@changePassword')
                    ->name("{$prefix}.auth.change-password");
                Route::post('/auth/logout', 'AuthController@logout')
                    ->name("{$prefix}.auth.logout");

                add_api_module_routes($prefix, 'admin', [
                    'prefix' => '',
                    'name' => '',
                ], function () use ($prefix) {
                    Route::post('/{action}/{id}', "AdminController@adminAction")
                        ->where('action', 'activate|deactivate')
                        ->middleware("audit:admin,admin-action")
                        ->name('admin-action');
                });

                add_api_module_routes($prefix, 'audit-log', [
                    'prefix' => 'audit-logs',
                    'name' => 'audit-logs',
                    'middleware' => ['superadmin'],
                    'exclude' => ['create', 'update', 'delete', 'show'],
                ]);

                add_api_module_routes($prefix, 'module', [
                    'prefix' => 'modules',
                    'name' => 'modules',
                    'controller' => 'ModuleController',
                ]);

                add_api_module_routes($prefix, 'admin-profile', [
                    'prefix' => 'admin-profiles',
                    'name' => 'admin-profiles',
                    'controller' => 'AdminProfileController',
                ]);

                add_api_module_routes($prefix, 'site-group', [
                    'prefix' => 'site-groups',
                    'name' => 'site-groups',
                    'exclude' => ['delete']
                ]);

                add_api_module_routes($prefix, 'site', [
                    'prefix' => 'sites',
                    'name' => 'sites',
                    'exclude' => ['delete']
                ]);
            });
    });
