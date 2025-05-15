<?php

namespace Litermi\Cache\Services;

use Illuminate\Support\Facades\Cache;
use Litermi\Cache\Facades\CacheCustomFacade;
use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;

/**
 *
 */
class GetDataFromCacheOrDatabaseService
{
    /**
     * @param CacheBuilder $query
     * @param              $columns
     * @param              $nameCache
     * @param array $tag
     * @return array
     */
    public static function execute(
        $query,
        $columns,
        $nameCache,
        array $tag
    ): array {
        $dataIsFromCache = true;

        $dataFromCache = CacheCustomFacade::tags($tag)->get($nameCache);
        $headerName    = config('cache-query.header_force_not_cache_name');
        $headerName    = empty($headerName) ? 'force-not-cache' : $headerName;
        if (request()->header($headerName) != null) {
            $dataFromCache = null;
        }

        if ($dataFromCache !== null) {
            return [$dataFromCache, $dataIsFromCache];
        }

        $dataIsFromCache  = false;
        $dataFromDatabase = $query->get($columns);

        return [$dataFromDatabase, $dataIsFromCache];
    }
}
