<?php

namespace App\Helpers\Traits;

use Illuminate\Http\Request;

trait RequestParser
{
    public function parseSearchQueryRequest(Request $request, &$validator = null)
    {
        $data = $request->only(['filters', 'query', 'limit', 'offset', 'order']);

        $validator = $this->validator->make($data, [
            'filters' => 'json',
            'limit' => 'integer',
            'offset' => 'integer',
            'order' => 'json'
        ]);

        if ($validator->fails()) {
            return null;
        }

        $data = [
            'filters' => $data['filters'] ? json_decode($data['filters'], true) : [],
            'limit' => $data['limit'] ? $data['limit'] : 0,
            'offset' => $data['offset'] ? $data['offset'] : 0,
            'order' => $data['order'] ? $data['order'] : ['key' => 'distance', 'direction' => 'asc'],
        ];

        if ($validator->fails()) {
            return null;
        }

        return $data;
    }

    public function parseQueryRequest(Request $request)
    {
        $keys = [/*'filters'*/'limit', 'sort_field', 'sort_direction', 'page', 'perpage'];
        $data = $request->only($keys);

        foreach ($keys as $k) {
            if (!array_key_exists($k, $data)) {
                $data[$k] = null;
            }
        }

        $return = [
            'limit' => $data['limit'] ? $data['limit'] : 1000,
            'pagination' => [
                'page' => $data['page'] ?: 1,
                'perpage' => $data['perpage'] ?: 50,
            ],
        ];

        if (!empty($data['sort_field'])) {
            $direction = !empty($data['sort_direction']) && $data['sort_direction'] == 'desc' ? 'desc' : 'asc';
            $return['orders'] = [
                $data['sort_field'] => $direction,
            ];
        } else {
            $return['orders'] = ['created_at' => 'desc'];
        }

        $return['filters'] = [];
        $parsed = [];
        foreach ($request->all() as $k => $v) {
            if (in_array($k, $keys) || in_array($k, ['includes', 'access_token', 'x_team_id', 'x_channel_id'])) {
                continue;
            }

            if (strlen($v) == 0) {
                continue;
            }

            $parsed[$k] = $v;
        }

        foreach ($parsed as $k => $v) {
            $return['filters'][str_replace('-', '.', $k)] = $v;
        }

        /*
        foreach (['filters' => 'filters'] as $k => $name) {
            if ($data[$k]) {
                if (is_array($data[$k])) {
                    $return[$name] = $data[$k];
                } else if (json_decode($data[$k], true) !== false) {
                    $return[$name] = json_decode($data[$k], true);
                }
            } else {
                if ($k == 'orders') {
                    $return[$name] = ['created_at' => 'desc'];
                } else {
                    $return[$name] = [];
                }
            }

            $parsed = [];
            foreach ($return[$name] as $k => $v) {
                $parsed[str_replace('-', '.', $k)] = $v;
            }

            $return[$name] = $parsed;
        }*/

        return $return;
    }
}
