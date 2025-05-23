<?php

namespace Litermi\Cache\Traits;

use Litermi\Cache\Facades\CacheCustomFacade;
use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;
use Litermi\Cache\Services\FirstDataFromCacheOrDatabaseService;
use Litermi\Cache\Services\GenerateNameCacheService;
use Litermi\Cache\Services\GetDataFromCacheOrDatabaseService;
use Litermi\Cache\Services\GetTagCacheService;
use Litermi\Cache\Services\GetTimeFromModelService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Trait CacheQueryTrait
 * @package Litermi\Cache\Repositories\UtilsBuilder
 */
trait CacheQueryTrait
{
    /**
     * @param string[]    $columns
     * @param string|null $tag
     * @param int         $time
     * @return Collection
     * @throws \Exception
     */
    public function getFromCache(
        array $columns = [ '*' ],
        $tag = null,
        int $time = null
    ): Collection
    {
        /** @var CacheBuilder $this */
        $query     = $this;
        $tag       = GetTagCacheService::execute($query, $tag);
        $nameCache = GenerateNameCacheService::execute($query, $columns);
        [
            $data,
            $dataIsFromCache,
        ] = GetDataFromCacheOrDatabaseService::execute($query, $columns, $nameCache, $tag);
        $this->setCache($nameCache, $data, $time, $dataIsFromCache, $tag);

        return $data;

    }

    /**
     * @param string[] $columns
     * @param null     $tag
     * @param null     $time
     * @return Model|null
     * @throws \Exception
     */
    public function firstFromCache(
        array $columns = [ '*' ],
              $tag = null,
        int   $time = null
    ): ?Model {
        /** @var CacheBuilder $this */
        $query     = $this;
        $tag       = GetTagCacheService::execute($query, $tag);
        $nameCache = GenerateNameCacheService::execute($query, $columns);
        [
            $data,
            $dataIsFromCache,
        ] = FirstDataFromCacheOrDatabaseService::execute($query, $columns, $nameCache, $tag);
        $this->setCache($nameCache, $data, $time, $dataIsFromCache, $tag);

        return $data === '' ? null : $data;
    }

    /**
     * @param       $nameCache
     * @param       $data
     * @param       $time
     * @param       $dataIsFromCache
     * @param array $tag
     * @return void
     */
    public function setCache(
        $nameCache,
        $data,
        $time,
        $dataIsFromCache,
        array $tag
    ): void {
        if ($dataIsFromCache === false) {
            if ($time === null) {
                $time = GetTimeFromModelService::execute($this);
            }
            CacheCustomFacade::tags($tag)->put($nameCache, $data, $time);
        }
    }

}
