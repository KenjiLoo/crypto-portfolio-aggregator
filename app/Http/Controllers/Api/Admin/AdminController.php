<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->model = 'App\Models\Admin';
        $this->resource = 'App\Resources\Admin\Admin';
        $this->moduleKey = 'admin';
        $this->moduleName = 'admins';
    }

    public function adminAction(Request $request, $action, $id)
    {
        $admin = $this->model::find($id);

        if (!$admin) {
            return api()->notFound()
                ->message(__('api.general.not_found'))
                ->flush();
        }

        if ($action === 'activate') {
            $admin->status = $this->model::STATUS_ACTIVE;
        } else {
            $admin->status = $this->model::STATUS_INACTIVE;
        }

        $admin->save();

        return api()->ok()
            ->flush();
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

        $model->apply($query);
        $dataOutput = $query->get();

        return api()->ok()
            ->data($dataOutput)
            ->meta([
                'field' => 'meta.created',
            ])->flush();
    }
}
