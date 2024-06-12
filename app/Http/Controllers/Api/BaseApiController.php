<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Traits\QueryParser;
use App\Helpers\Traits\RequestParser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;

class BaseApiController extends Controller
{
    use RequestParser, QueryParser;

    const ALLPAGE = 10000;

    protected $moduleKey;
    protected $moduleName;

    protected $model;
    protected $resource;
    protected $excel;

    protected $cache = null;

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $model = new $this->model;

        $data = $this->parseQueryRequest($request);
        extract($data);

        $query = $this->parseQuery($model, $model->getQuery(), $filters, $orders);
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

    public function downloadable(Request $request)
    {
        $model = new $this->model;

        $data = $this->parseQueryRequest($request);
        extract($data);

        $query = $this->parseQuery($model, $model->getQuery(), $filters, $orders);
        $includes = isset($request->includes) ? explode(',', $request->includes) : [];

        if (!empty($includes)) {
            $keys = $model->filterIncludes($includes);
            $query->with($keys);
        }

        $model->apply($query);

        $data = $query->get();
        $excel = new $this->excel($data);

        return Excel::download($excel, $this->moduleName . '_' . date('YmdHis') . '.xlsx');
    }

    public function store(Request $request)
    {
        $data = parse_request_data($request->all());

        $model = new $this->model;
        if (!$model->validate($data)) {
            return api()->formError()
                ->message(__('api.general.form_error'))
                ->setFormErrors($model->errors)
                ->flush();
        }

        $model->isStoring();
        $model->fillFromRequest($request, $data);
        $model->onBeforeSave($request);

        if ($model->save()) {
            $model->onAfterSave($request);
            $data = new $this->resource($model);

            return api()->created()
                ->message(__('api.general.created'))
                ->data($data)
                ->flush();
        }

        return api()->exception()
            ->message(__('api.general.internal_error'))
            ->flush();
    }

    public function show($id, Request $request, $jsonResponse = true)
    {
        $model = new $this->model;

        $query = $model->getQuery()->where($model->getKeyName(), $id);
        $model->apply($query);

        $result = $query->first();

        if (!$result) {
            return api()->notFound()
                ->message(__('api.general.not_found'))
                ->flush();
        }

        if ($jsonResponse) {
            $data = new $this->resource($result);
            return api()->ok()
                ->data($data)
                ->flush();
        }

        return $result;
    }

    public function update($id, Request $request)
    {
        $data = parse_request_data($request->all());

        $model = new $this->model;
        if (!$model->validate($data, $id, true)) {
            return api()->formError()
                ->message(__('api.general.form_error'))
                ->errors($model->errors)
                ->flush();
        }

        $query = $model->getQuery()->where($model->getKeyName(), $id);
        $model->apply($query);
        $item = $query->first();

        if (!$item) {
            return api()->notFound()
                ->message(__('api.general.not_found'))
                ->flush();
        }

        $item->isUpdate();
        $item->fillFromRequest($request, $data);
        $item->onBeforeSave($request);

        if ($item->save()) {
            $item->onAfterSave($request);

            $data = new $this->resource($item);
            return api()->ok()
                ->data($data)
                ->message(__('api.general.updated'))
                ->flush();
        }

        return api()->exception()
            ->message(__('api.general.internal_error'))
            ->flush();
    }

    public function delete($id, Request $request)
    {
        $model = new $this->model;
        $query = $this->model::query();

        $ids = array_map('trim', explode(',', $id));
        $model->apply($query);
        $items = $query->whereIn('id', $ids)->get();

        if (!$items) {
            return api()->notFound()
                ->message(__('api.general.not_found'))
                ->flush();
        }

        foreach ($items as $item) {
            if (!$item->isDeleteable()) {
                return api()->badRequest()
                    ->message($item->errorMsg)
                    ->flush();
            }
        }

        foreach ($items as $item) {
            $item->delete();
        };

        return api()->ok()->flush();
    }
}
