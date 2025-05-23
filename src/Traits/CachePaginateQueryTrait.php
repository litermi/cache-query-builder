<?php

namespace Litermi\Cache\Traits;

use Litermi\Cache\Classes\CacheConst;
use Litermi\Cache\Facades\CacheCustomFacade;
use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;
use Litermi\Cache\Services\GenerateNameCacheService;
use Litermi\Cache\Services\GetParametersPaginationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
trait CachePaginateQueryTrait
{
    /**
     * @param int $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginateByRequest(
        $columns = [ '*' ],
        $perPage = CacheConst::PER_PAGE,
        $pageName = 'page',
        $page = null
    ): LengthAwarePaginator {

        /** @var CacheBuilder $this */
        [$perPage, $page] = GetParametersPaginationService::execute( $page, $perPage);
        return $this->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param array    $columns
     * @param          $tag
     * @param int|null $time
     * @param          $perPage
     * @param          $pageName
     * @param          $page
     * @return LengthAwarePaginator
     */
    public function paginateFromCacheByRequest(
        array $columns = [ '*' ],
              $tag = null,
        int $time = null,
              $perPage = CacheConst::PER_PAGE,
              $pageName = 'page',
              $page = null
    ): LengthAwarePaginator {
        /** @var CacheBuilder $this */
        $query     = $this;
        [$perPage, $page] = GetParametersPaginationService::execute( $page, $perPage);
        $paginationValues = ['perPage'=> $perPage, 'page'=> $page];
        $nameCache = GenerateNameCacheService::execute($query, $columns, paginationValues: $paginationValues);
        if ($time === null) {
            $time = config('cache-query.cache_default_time_seconds');
        }

        $headerName = config('cache-query.header_force_not_cache_name');
        $headerName = empty($headerName) ?  'force-not-cache' : $headerName;
        if (request()->header($headerName) != null) {
            return $this->paginateByRequest($columns, $perPage, $pageName, $page);
        }

        return CacheCustomFacade::tags($tag)->remember(
            $nameCache, $time, function () use ($page, $pageName, $columns, $perPage) {
            return $this->paginateByRequest($columns,$perPage,  $pageName, $page);
        }
        );
    }
}
