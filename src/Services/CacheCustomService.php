<?php

namespace Litermi\Cache\Services;

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

    /**
     * @return $this
     */
    public function tags($tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function get($key)
    {
        if(env('CACHE_DRIVER')!='redis') {
            return Cache::get($key);
        }
        if(env('CACHE_DRIVER')=='redis') {
            return Cache::tags($this->tag)->get($key);
        }
    }

    public function put($get, $time)
    {
        if(env('CACHE_DRIVER')!='redis'){
            Cache::put($get, $time);
        }
        if(env('CACHE_DRIVER')=='redis'){
            Cache::tags($this->tag)->put($get, $time);
        }
    }

    public function flush()
    {
        if(env('CACHE_DRIVER')!='redis'){
            Cache::flush();
        }
        if(env('CACHE_DRIVER')=='redis'){
            Cache::tags($this->tag)->flush();
        }
    }

    public function forget($key)
    {
        if(env('CACHE_DRIVER')!='redis') {
            Cache::get($key);
        }
        if(env('CACHE_DRIVER')=='redis') {
            Cache::tags($this->tag)->forget($key);
        }
    }

    public function remember($key)
    {
        if(env('CACHE_DRIVER')!='redis') {
            return Cache::get($key);
        }
        if(env('CACHE_DRIVER')=='redis') {
            return Cache::tags($this->tag)->get($key);
        }
    }
    public function has($key)
    {
        if(env('CACHE_DRIVER')!='redis') {
            return Cache::get($key);
        }
        if(env('CACHE_DRIVER')=='redis') {
            return Cache::tags($this->tag)->get($key);
        }
    }
}
