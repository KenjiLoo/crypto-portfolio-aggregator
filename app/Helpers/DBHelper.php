<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DBHelper
{
    const ALLPAGE = 10000;

    public static $connection = null;
    

    public static function buildArrayParams($prefix, $arr)
    {
        $stmt = [];
        $params = [];
        $i = 0;

        foreach ($arr as $v) {
            $pkey = "{$prefix}{$i}";
            $stmt[] = ":{$pkey}";
            $params[$pkey] = $v;

            $i++;
        }

        return [
            implode(',', $stmt),
            $params,
        ];
    }

    public static function raw($v)
    {
        return DB::raw($v);
    }

    public static function query($sql, $params = [], $keyBy = null)
    {
        $parsedParams = [];

        foreach ($params as $k => $v) {
            if (is_array($v)) {
                list($stmt, $p) = self::buildArrayParams($k, $v);
                $sql = str_replace(":" . $k, $stmt, $sql);

                foreach ($p as $kk => $vv) {
                    $parsedParams[$kk] = $vv;
                }
            } else {
                $parsedParams[$k] = $v;
            }
        }

        if (self::$connection) {
            $results =  DB::connection(self::$connection)
                ->select(DB::raw($sql), $parsedParams);
        } else {
            $results =  DB::select(DB::raw($sql), $parsedParams);
        }

        if ($keyBy != null) {
            $parsed = [];

            foreach ($results as $r) {
                $parsed[$r->$keyBy] = $r;
            }

            return $parsed;
        }

        return $results;
    }

    public static function querySingle($sql, $params = [])
    {
        $results = self::query($sql, $params, null);

        if (!empty($results)) {
            return collect($results)->first();
        }

        return null;
    }

    public static function getPaginationInfo($sql, $params = [], $page = 1, $limit = 50)
    {
        if ($page == 'all') {
            $limit = self::ALLPAGE;
        }

        $result = self::querySingle("SELECT COUNT(*) as total FROM ($sql) as _u", $params);

        $meta = [
            'page' => $page == 'all' ? 1 : $page,
            'pages' => ceil(($result->total * 1) / $limit),
            'perpage' => $limit,
            'total' => $result->total * 1,
            'sort' => 'desc',
            'field' => 'meta.created',
        ];

        $offset = ($meta['page'] - 1) * $limit;
        $sql .= " LIMIT {$limit} OFFSET {$offset}";

        return [$sql, $meta];
    }
}
