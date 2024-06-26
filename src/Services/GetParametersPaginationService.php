<?php

namespace Litermi\Cache\Services;

use Litermi\Cache\Classes\CacheConst;

/**
 * Class GetParametersPaginationService
 * @package Litermi\Cache\Services
 */
class GetParametersPaginationService
{

    /**
     * @param int $perPage
     * @param     $page
     * @return array
     */
    public static function execute($page = CacheConst::PAGE, $perPage = CacheConst::PER_PAGE): array
    {
        $sizeRequest = request()->size_pagination;
        $perPage     = $sizeRequest === null ? $perPage : $sizeRequest;

        $pageRequest = request()->page_pagination;
        $page        = $pageRequest === null ? $page : $pageRequest;

        return [ $perPage, $page ];
    }

}
