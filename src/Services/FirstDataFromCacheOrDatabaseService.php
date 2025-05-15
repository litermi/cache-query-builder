<?php

namespace Litermi\Cache\Services;

use Litermi\Cache\Facades\CacheCustomFacade;
use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
class FirstDataFromCacheOrDatabaseService
{
    /**
     * @param CacheBuilder $query
     * @param              $columns
     * @param              $nameCache
     * @param array        $tag
     * @return array
     */
    public static function execute(
        $query,
        $columns,
        $nameCache,
        array $tag
    ): array {
        $dataIsFromCache = true;
        $dataFromCache   = CacheCustomFacade::tags($tag)->get($nameCache);
        $headerName = config('cache-query.header_force_not_cache_name');
        $headerName = empty($headerName) ?  'force-not-cache' : $headerName;
        if (request()->header($headerName) != null) {
            $dataFromCache = null;
        }

        if ($dataFromCache !== null) {
            return [ $dataFromCache, $dataIsFromCache ];
        }

        $dataIsFromCache  = false;
        $dataFromDatabase = $query->first($columns);
        $dataFromDatabase = $dataFromDatabase === null ? '' : $dataFromDatabase;

        return [ $dataFromDatabase, $dataIsFromCache ];
    }
}
