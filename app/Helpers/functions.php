<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

function api()
{
    return new App\Helpers\ResponseHelper;
}

function myarr($data)
{
    return new App\Helpers\ArrayHelper($data);
}

function mycache($namespace, $duration = 60)
{
    return new App\Helpers\CacheHelper('api-' . $namespace, $duration);
}

function get_utc_time($timezone, $format = 'Y-m-d H:i:s', $now = null)
{
    if ($now == null) {
        $now = time();
    }

    list($hour, $min) = explode(':', $timezone);
    return date($format, $now - ($hour * 60 * 60) + ($min * 60));
}

function get_local_time($timezone, $format = 'Y-m-d H:i:s', $now = null)
{
    if ($now == null) {
        $now = time();
    }

    list($hour, $min) = explode(':', $timezone);
    return date($format, $now + ($hour * 60 * 60) + ($min * 60));
}

function get_request_data($keys = [])
{
    if (empty($keys)) {
        return [];
    }

    return parse_request_data(request()->only($keys));
}

function parse_request_data($data)
{
    $parsed = [];

    foreach ($data as $k => $v) {
        if (is_numeric($v) || is_array($v) || is_bool($v)) {
            $parsed[$k] = $v;
        } else {
            if (!empty($v)) {
                $parsed[$k] = $v;
            }
        }
    }

    return $parsed;
}

function slugify($text, $join = '-')
{
    $text = preg_replace('/[[:punct:]]/', '', $text);
    $text = trim(str_replace(["\t"], '', $text));
    $text = trim(str_replace([' ', "\t"], $join, $text));
    return mb_strtolower($text, 'utf-8');
}

function generate_otp($length = 6)
{
    $otp = '';

    for ($i = 0; $i < $length; $i++) {
        $otp .= rand(0, 9);
    }

    return $otp;
}

function add_api_module_routes($prefix, $moduleKey, $options, $extraRoutes = null)
{
    $ctrl = str_replace('-', ' ', $moduleKey);

    $defaults = [
        'prefix' => '',
        'middleware' => [],
        'key' => $moduleKey,
        'name' => '',
        'exclude' => [],
        'downloadable' => false,
        'controller' => str_replace(' ', '', ucwords($ctrl)) . 'Controller',
        'submethod' => false,
        'where' => [],
    ];

    $options = array_merge($defaults, $options);
    $subprefix = $options['submethod'] ? 'sub' : '';
    $options['where'] = $options['where'] ?: 'id';

    $moduleName = strlen($options['name']) > 0 ? $options['name'] . '.' : '';
    if (!in_array('listing', $options['exclude'])) {
        if ($options['downloadable']) {
            Route::get($options['prefix'] . '.xlsx', ['uses' => "{$options['controller']}@{$subprefix}downloadable"])
                ->name(trim($prefix . ".{$moduleName}download", '.'));
        }
    }

    Route::name($prefix . '.')
        ->prefix($options['prefix'])
        ->group(function () use ($options, $extraRoutes, $subprefix) {
            $module = $options['key'];
            $moduleName = strlen($options['name']) > 0 ? $options['name'] . '.' : '';
            $controller = $options['controller'];

            //Listing
            if (!in_array('listing', $options['exclude'])) {
                Route::get('/', ['uses' => "{$controller}@{$subprefix}index"])
                    ->name("{$moduleName}list");
            }

            //show
            if (!in_array('show', $options['exclude'])) {
                //show
                Route::get('/{id}', ['uses' => "{$controller}@{$subprefix}show"])
                    ->name("{$moduleName}show")
                    ->where($options['where'], '\d+');
            }

            //Create
            if (!in_array('create', $options['exclude'])) {
                Route::post('/', ['uses' => "{$controller}@{$subprefix}store"])
                    ->name("{$moduleName}store");
            }

            //Update
            if (!in_array('update', $options['exclude'])) {
                Route::put('/{id}', ['uses' => "{$controller}@{$subprefix}update"])
                    ->name("{$moduleName}update")
                    ->where($options['where'], '\d+');
            }

            //Delete
            if (!in_array('delete', $options['exclude'])) {
                Route::delete('/{id}', ['uses' => "{$controller}@{$subprefix}delete"])
                    ->name("{$moduleName}delete");
            }

            if ($extraRoutes) {
                $extraRoutes();
            }
        });
}

function is_date_format($value, $format = 'Y-m-d')
{
    return $value == date($format, strtotime($value));
}

function build_date_range_array($from, $to, $type = 'daily')
{
    if ($type == 'monthly') {
        $format = 'Y-m';
        $increment = 'month';
    } else {
        $format = 'Y-m-d';
        $increment = 'day';
    }

    $parsed = [];
    for (; $from <= $to;) {
        $formatted = date($format, strtotime($from));
        $parsed[] = $formatted;

        $from = date('Y-m-d', strtotime("+1 {$increment}", strtotime($from)));
    }

    return $parsed;
}

function generate_uuid()
{
    return Str::uuid();
}

function do_log($type, $message, $channel = null)
{
    if (!empty($channel) && is_array($channel)) {
        return Log::stack($channel)->{$type}($message);
    } else if (!empty($channel)) {
        return Log::channel($channel)->{$type}($message);
    }

    return Log::{$type}($message);
}

function log_debug($msg, $channel = null)
{
    return do_log('debug', $msg, $channel);
}

function log_error($msg, $channel = 'error')
{
    return do_log('error', $msg, $channel);
}

function log_notice($msg, $channel = 'absent')
{
    return do_log('notice', $msg, $channel);
}

function log_warning($msg, $channel = 'absent')
{
    return do_log('warning', $msg, $channel);
}
