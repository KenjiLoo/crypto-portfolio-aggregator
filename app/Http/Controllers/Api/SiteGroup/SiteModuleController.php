<?php

namespace App\Http\Controllers\Api\SiteGroup;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class SiteModuleController extends BaseApiController
{
    public function listSiteModule(Request $request)
    {
        $this->model = 'App\Models\Module';
        $this->resource = 'App\Resources\Admin\Module';

        $model = new $this->model;

        $data = $this->parseQueryRequest($request);
        extract($data);

        $query = $this->parseQuery($model, $model->getQuery(), $filters, $orders);

        /*Only listing API is need, no need to let site group admin to create, update, and delete the module. (These will be done by admin) */
        $query->where('type', 'site_group');

        $includes = isset($request->includes) ? explode(',', $request->includes) : [];

        if (!empty($includes)) {
            $keys = $model->filterIncludes($includes);
            $query->with($keys);
        }

        $model->apply($query);

        Paginator::currentPageResolver(function () use ($pagination) {
            return $pagination['page'];
        });

        if ($pagination['perpage'] == 'all') {
            $d = $query->paginate(self::ALLPAGE);
        } else {
            $d = $query->paginate($pagination['perpage']);
        }

        $data = $d->items();
        $collections = $this->resource::collection($data);

        return api()->ok()
            ->data($collections)
            ->meta([
                'page'    => $d->currentPage(),
                'pages'   => $d->lastPage(),
                'perpage' => $d->perPage(),
                'total'   => $d->total(),
                'sort'    => 'desc',
                'field'   => 'meta.created',
            ])->flush();
    }
}
