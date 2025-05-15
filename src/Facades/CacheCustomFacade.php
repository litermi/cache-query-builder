<?php

namespace Litermi\Cache\Facades;

use Illuminate\Support\Facades\Facade;
use Litermi\Cache\Services\CacheCustomService;

/**
 * @method static CacheCustomService tags()
 * @method static CacheCustomService get()
 * @method static CacheCustomService put()
 * @method static CacheCustomService flush()
 */
class CacheCustomFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cache-custom-service';
    }
}
