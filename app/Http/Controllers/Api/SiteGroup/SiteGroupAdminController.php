<?php

namespace App\Http\Controllers\Api\SiteGroup;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;

class SiteGroupAdminController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\SiteGroupAdmin';
        $this->resource = 'App\Resources\SiteGroup\SiteGroupAdmin';
        $this->moduleKey = 'site-group-admin';
        $this->moduleName = 'site-group-admins';
    }

    public function getMeData(Request $request)
    {
        $dataOutput = new $this->resource($request->user());

        return api()->ok()
            ->data($dataOutput)
            ->meta([
                'field' => 'meta.created',
            ])->flush();
    }

    public function getProfileData(Request $request)
    {
        $id = $request->user()->id;

        $model = new $this->model;
        $query = $model->getQuery()->where('id', $id);

        $keys = $model->filterIncludes(['site_group']);
        $query->with($keys);

        $model->apply($query);
        $dataOutput = $query->get();

        return api()->ok()
            ->data($dataOutput)
            ->meta([
                'field' => 'meta.created',
            ])->flush();
    }
}
