<?php

namespace Litermi\Cache\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Litermi\Cache\Classes\CacheConst;

/**
 * Class GetCacheService
 */
class GetCacheService
{

    /**
     * @param      $customKey
     * @param null $tag
     * @return array|mixed
     * @throws Exception
     */
    public static function execute($customKey, $tag = null)
    {
        $tag       = GetTagCacheService::execute(null, $tag);
        $customKey = GetKeyCacheBySystemService::execute($customKey);
        $customKey = self::getDataFromPagination($customKey);
        $headerName = config('cache-query.header_force_not_cache_name');
        $headerName = empty($headerName) ?  'force-not-cache' : $headerName;
        if (request()->header($headerName) != null) {
            return null;
        }
        return Cache::tags($tag)
            ->get($customKey);
    }

    /**
     * @param $customKey
     * @return mixed|string
     */
    private static function getDataFromPagination($customKey)
    {

        [$perPage, $page] = GetParametersPaginationService::execute();
        if ($page != CacheConst::PAGE) {
            $customKey .= '_' . $page;
        }
        if ($perPage != CacheConst::PER_PAGE) {
            $customKey .= '_' . $perPage;
        }
        return $customKey;
    }


}
