<?php

namespace Litermi\Cache\Services;

use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;

/**
 *
 */
class GenerateNameCacheService
{
    /**
     * @param CacheBuilder $query
     * @param              $columns
     * @param              $extras
     * @return string
     */
    public static function execute($query, $columns, $extras = '', $paginationValues = []): string
    {
        $perPage = null;
        $page    = null;

        if (empty($paginationValues) == false) {
            $perPage = array_key_exists('perPage', $paginationValues) == false ? $paginationValues['perPage'] : null;
            $page    = array_key_exists('page', $paginationValues) == false ? $paginationValues['page'] : null;
        }

        [$perPage, $page] = GetParametersPaginationService::execute($perPage, $page);
        $extras .= "_" . $perPage . '-' . $page;

        $querySql = $query->toSql();
        if (is_array($columns)) {
            $columns = array_values($columns);
            $columns = json_encode($columns);
        }

        $relationShip       = array_keys($query->getEagerLoads());
        $queryRelationsShip = GetQueryRelationShipService::execute($query, $relationShip);
        $relationShip       = json_encode($relationShip);
        $parameters         = json_encode($query->getBindings());
        $nameCache          = $querySql;
        $nameCache          .= $parameters;
        $nameCache          .= $relationShip;
        $nameCache          .= $columns;
        $nameCache          .= $queryRelationsShip;

        $charactersToRemove = [
            ' ',
            '?',
            '`',
            ',',
            '"',
            '*',
            '.',
            '[',
            ']',
            '(',
            ')',
        ];

        $nameCache .= '-' . $extras;
        $nameCache = str_replace($charactersToRemove, '', $nameCache);
        $nameCache = htmlentities($nameCache, ENT_QUOTES, 'UTF-8');
        $nameCache = utf8_encode($nameCache);
        $nameCache = md5($nameCache);

        return $nameCache;

    }
}
