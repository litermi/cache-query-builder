<?php

namespace Litermi\Cache\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Litermi\Cache\Facades\CacheCustomFacade;

/**
 * Class SetCacheService
 */
class SetCacheService
{

    /**
     * @param       $customKey
     * @param array $data
     * @param null  $tag
     * @param int   $time
     * @throws Exception
     */
    public static function execute($customKey, $data = [], $tag = null, $time = 0, $disabled = false): void
    {
        $time      = $time === 0 ? config('cache-query.cache_time_seconds') : $time;
        $tag       = GetTagCacheService::execute(null, $tag);
        $customKey = GetKeyCacheBySystemService::execute($customKey, $disabled);
        CacheCustomFacade::tags($tag)
            ->put($customKey, $data, $time);
    }
}
