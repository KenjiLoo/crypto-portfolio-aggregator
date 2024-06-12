<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\DB;

trait QueryParser
{

    protected function evaluate($query, $field, $filter, $forceEqual = false)
    {
        $isArray = false;
        if (!is_array($filter)) {
            $value = $filter;
        } else if (is_array($filter)) {
            $isArray = true;
            $value = false;
        }

        if (is_string($value) && strpos($value, ',') !== false) {
            $query->whereIn(
                $field,
                explode(',', $value)
            );
        } else if ($forceEqual) {
            $query->where($field, $value);
        } else if ($value === null) {
            $query->whereNull($field);
        } else if ($isArray) {
            if (isset($filter['type']) && isset($filter['value'])) {
                $timezone = isset($filter['timezone']) ? $filter['timezone'] : null;

                if ($timezone) {
                    $query->where(
                        DB::raw('DATE(DATE_ADD(' . $field . ', INTERVAL \'' . $timezone . '\' HOUR_MINUTE))'),
                        $filter['type'],
                        $filter['value']
                    );
                } else {
                    $query->where($field, $filter['type'], $filter['value']);
                }
            } else {
                $start = $filter[0];
                $end = $filter[1];

                if (strtotime($start) !== false) {
                    if ($start == date('Y-m-d H:i:s', strtotime($start))) {
                        $query->where($field, '>=', $start)
                            ->where($field, '<=', $end);
                    } else {
                        $query->where($field, '>=', $start . ' 00:00:00')
                            ->where($field, '<=', $end . ' 23:59:59');
                    }
                } else {
                    $query->where($field, '>=', $start)
                        ->where($field, '<=', $end);
                }
            }
        } else if (is_numeric($value) || is_bool($value)) {
            $query->where($field, $value);
        } else if (in_array($value, ['true', 'false'])) {
            if ($value == 'true') {
                $query->where($field, true);
            } else {
                $query->where($field, false);
            }
        } else {
            $query->where($field, 'like', '%' . $value . '%');
        }

        return $query;
    }

    public function parseQuery($model, $query, $filters = [], $orders = [])
    {
        $self = $this;
        $leftFilters = [];
        $leftOrders = [];
        $parsedIncludes = [];

        foreach ($filters as $field => $filter) {
            $isTimezone = false;
            $absoluteField = explode('.', $field)[0];

            if ($model->isSpecialFilterField($absoluteField)) {
                $model->filterByField($query, $field, $filter);
                continue;
            }

            if (!$model->isFilterable($absoluteField) && !$model->isEqualable($absoluteField)) {
                continue;
            }

            if ($model->isTimezoneField($absoluteField)) {
                $isTimezone = true;
            }

            if (($pos = strrpos($field, '.from')) !== false) {
                $field = substr($field, 0, $pos);
                $query = $this->evaluate($query, $field, [
                    'timezone' => $isTimezone ? config('luluchat.current.timezone') : null,
                    'type' => '>=',
                    'value' => $filter . ' 00:00:00',
                ]);
            } else if (($pos = strrpos($field, '.to')) !== false) {
                $field = substr($field, 0, $pos);
                $query = $this->evaluate($query, $field, [
                    'timezone' => $isTimezone ? config('luluchat.current.timezone') : null,
                    'type' => '<=',
                    'value' => $filter . ' 23:59:59',
                ]);
            } else if (($pos = strrpos($field, '.')) !== false) {
                $entity = substr($field, 0, $pos);
                $entityField = substr($field, $pos + 1);

                if (!array_key_exists($entity, $leftFilters)) {
                    $leftFilters[$entity] = [];
                }

                $leftFilters[$entity][$entityField] = $filter;
            } else if (($pos = strrpos($field, '.min')) !== false) {
                $field = substr($field, 0, $pos);
                $query = $this->evaluate($query, $field, [
                    'type' => '<=',
                    'value' => $filter,
                ]);
            } else if (($pos = strrpos($field, '.max')) !== false) {
                $field = substr($field, 0, $pos);
                $query = $this->evaluate($query, $field, [
                    'type' => '<=',
                    'value' => $filter,
                ]);
            } else {
                $query = $this->evaluate($query, $field, $filter, $model->isEqualable($field));
            }
        }

        foreach ($leftFilters as $entity => $filters) {
            $query->whereHas($entity, function ($q) use ($model, $self, $filters) {
                foreach ($filters as $field => $filter) {
                    $q = $self->evaluate($q, $field, $filter);
                }

                return $q;
            });
        }

        foreach ($orders as $column => $direction) {
            if ($model->isSpecialFilterField($column)) {
                $model->sortByField($query, $column, $direction);
                continue;
            }

            if (isset($model->sortMap[$column])) {
                $field = $model->sortMap[$column];

                $query->orderBy($field, $direction);
                continue;
            }

            if (($pos = strrpos($column, '.')) !== false) {
                $entity = substr($column, 0, $pos);
                $field = substr($column, $pos + 1);

                if (array_key_exists($entity, $model->joins)) {
                    $settings = $model->joins[$entity];
                    $method = $settings[0];
                    $query->$method($entity, $entity . '.id', '=', $settings[1]);
                    $query->orderBy($column, $direction);
                }
            } else {
                $query->orderBy($column, $direction);
            }
        }

        return $query;
    }

    public function parseElasticsearchQuery($searchable, $filters = [], $order = null, $options = [])
    {
        $defaults = [
            'limit' => config('hashfit.defaults.limit'),
            'offset' => 0,
        ];

        $options = array_merge($defaults, $options);

        $query = [
            'query' => [
                'match_all' => [],
            ],
            'size' => $options['limit'],
            'from' => $options['offset'],
        ];

        if (array_key_exists('query', $filters) && strlen($filters['query']) > 0) {
            $query['query'] = [
                'query_string' => [
                    'query' => $filters['query']
                ]
            ];
        }

        $searchFilter = $searchable::buildSearch($filters, $options);

        if ($searchFilter) {
            $query['filter'] = $searchFilter;
        }

        if ($order && $order['key'] !== 'relavance') {
            $query['sort'] = $searchable::buildSort($order['key'], $order['direction'], $options);
        }

        return $query;
    }
}
