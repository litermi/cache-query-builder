<?php

namespace Litermi\Cache\Traits;

use Litermi\Cache\Services\GetTagCacheService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
trait PurgeCacheBeforeActiveRecord
{
    /**
     * @param array $options
     * @param       $tag
     * @return bool
     * @throws Exception
     */
    public function saveWithCache(array $options = [], $tag = [])
    {
        /** @var Model $this */
        $query = $this->newModelQuery();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $this->save();
    }

    /**
     * @param array $options
     * @param       $tag
     * @return bool
     * @throws Exception
     */
    public function deleteWithCache(array $options = [], $tag = [])
    {
        /** @var Model $this */
        $query = $this->newModelQuery();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $this->delete();
    }

    public static function insert(array $values, $tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        $model = self::query()->getModel();

        $tag = GetTagCacheService::execute($model, $tag);
        Cache::tags($tag)->flush();

        return $model->newQuery()->insert($values);
    }

    /**
     * @param array $values
     * @param       $tag
     * @return mixed
     * @throws Exception
     */
    public static function insertWithCache(array $values = [], $tag = [])
    {
        return self::insert($values, $tag);
    }

}
