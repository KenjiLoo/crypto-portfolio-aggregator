<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    protected $namespace;
    protected $duration;

    protected $enableLog = false;
    protected $channel;
    protected $logPrefix = '[CACHE]';

    public function __construct($namespace, $duration = 60)
    {
        $this->namespace = $namespace;
        $this->duration = $duration;
    }

    public function enableLog($channel, $prefix = '[CACHE]')
    {
        $this->enableLog = true;
        $this->logPrefix = $prefix;
        $this->channel = $channel;

        return $this;
    }

    public function forgetable($key)
    {
        if (request()->has('no_cache')) {
            $key = $this->getKey($key);
            $this->log('forget ' . $key);
            Cache::forget($key);
        }

        return $this;
    }

    public function forget($key)
    {
        $key = $this->getKey($key);
        $this->log('manual forget ' . $key);
        cache::forget($key);

        return $this;
    }

    public function has($key)
    {
        $key = $this->getKey($key);
        return Cache::has($key);
    }

    public function get($key)
    {
        $key = $this->getKey($key);
        return Cache::get($key);
    }

    public function hit($key, $duration = null)
    {
        $key = $this->getKey($key);
        Cache::put($key, '1', $this->getDuration($duration));
    }

    public function increment($key, $amount = 1)
    {
        $key = $this->getKey($key);
        Cache::increment($key, $amount);
    }

    public function store($key, $value, $duration = null)
    {
        $key = $this->getKey($key);
        Cache::put($key, $value, $this->getDuration($duration));
    }

    public function remember($key, $fn, $duration = null)
    {
        // $cacheKey = $this->getKey($key);

        // if (Cache::has($cacheKey)) {
        //     $this->log('cache hit: ' . $cacheKey);
        //     return Cache::get($cacheKey);
        // }

        $return = $fn();

        if (!empty($return)) {
            return $this->cacheAndReturn($key, $return, $duration);
        }

        return $return;
    }

    public function cacheAndResponse($key, $return, $duration = null)
    {
        $key = $this->getKey($key);
        $this->log('response cache saved: ' . $key);
        Cache::put($key, $return, $this->getDuration($duration));

        return api()->ok()->data($return)->flush();
    }

    public function cacheAndReturn($key, $return, $duration = null)
    {
        $key = $this->getKey($key);
        $this->log('return cache saved: ' . $key);
        Cache::put($key, $return, $this->getDuration($duration));
        
        return $return;
    }

    private function getKey($key)
    {
        return $this->namespace . '::' . $key;
    }

    private function getDuration($duration)
    {
        if (!$duration) {
            return $this->duration;
        }

        return $duration;
    }

    private function log($message)
    {
        if ($this->enableLog) {
            $this->channel->debug($this->logPrefix . ' ' . $message);
        }
    }
}
