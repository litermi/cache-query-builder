<?php

namespace Litermi\Cache\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
class CacheCustomService
{

    /**
     * @var
     */
    private $tag = [];

    /**
     * @var
     */
    private $key = "";

    /**
     * @var
     */
    private $ttl = null;
    /**
     * @var mixed
     */
    private $database = null;

    public function __construct()
    {
    }

    public function put($key, $value, $time)
    {
        if (env('CACHE_DRIVER') != 'redis') {
            Cache::put($key, $value, $time);
        }
        if (env('CACHE_DRIVER') == 'redis') {
            Cache::tags($this->tag)->put($key, $value, $time);
        }
    }

    /**
     * @return $this
     */
    public function tags($tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function flush()
    {
        if (env('CACHE_DRIVER') != 'redis') {
            Cache::flush();
        }
        if (env('CACHE_DRIVER') == 'redis') {
            Cache::tags($this->tag)->flush();
        }
    }

    public function forget($key)
    {
        if (env('CACHE_DRIVER') != 'redis') {
            Cache::forget($key);
        }
        if (env('CACHE_DRIVER') == 'redis') {
            Cache::tags($this->tag)->forget($key);
        }
    }

    public function remember($key, $ttl, Closure $callback)
    {
        if (env('CACHE_DRIVER') != 'redis') {
            return Cache::remember($key, $ttl, $callback);
        }
        if (env('CACHE_DRIVER') == 'redis') {
            return Cache::tags($this->tag)->remember($key, $ttl, $callback);
        }
    }

    public function has($key)
    {
        if (env('CACHE_DRIVER') != 'redis') {
            return Cache::get($key);
        }
        if (env('CACHE_DRIVER') == 'redis') {
            return Cache::tags($this->tag)->get($key);
        }
    }

    public function get($key)
    {
        if (env('CACHE_DRIVER') != 'redis') {
            return Cache::get($key);
        }
        if (env('CACHE_DRIVER') == 'redis') {
            return Cache::tags($this->tag)->get($key);
        }
    }
}
