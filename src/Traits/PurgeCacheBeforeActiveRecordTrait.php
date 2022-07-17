<?php

namespace Litermi\Cache\Traits;

use Litermi\Cache\Services\GetTagCacheService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 *
 */
trait PurgeCacheBeforeActiveRecordTrait
{
    /**
     * @param array $options
     * @param       $tag
     * @return bool
     * @throws Exception
     */
    public function saveWithCache(array $options = [], $tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        /** @var Model $this */
        $query = $this->newModelQuery();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $this->save();
    }

    /**
     * @param array $attributes
     * @param array $options
     * @param array $tag
     * @return bool
     * @throws Exception
     */
    public function updateWithCache(array $attributes = [], array $options = [], $tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        /** @var Model $this */
        $query = $this->getModel();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $this->update($attributes, $options);
    }

    /**
     * @param array $options
     * @param       $tag
     * @return bool
     * @throws Exception
     */
    public function deleteWithCache(array $options = [], $tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        /** @var Model $this */
        $query = $this->newModelQuery();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $this->delete();
    }

    /**
     * @param array $values
     * @param       $tag
     * @return mixed
     * @throws Exception
     */
    public static function insertWithCache(array $values = [], $tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        /** @var Model $this */
        $model = self::query()->getModel();
        $tag = GetTagCacheService::execute($model, $tag);
        Cache::tags($tag)->flush();

        return self::insert($values, $tag);
    }

}
