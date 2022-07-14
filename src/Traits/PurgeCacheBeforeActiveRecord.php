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

    public function saveWithCache(array $options = [], $tag = [])
    {
        return $this->save($options, $tag);
    }

    public function save(array $options = [], $tag = [])
    {
        $model = $this->query()->getModel();

        /** @var Model $this */
        $query = $this->newModelQuery();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $model->newQuery()->save($options, $tag);
    }

    public function deleteWithCache($tag = [])
    {
        return $this->delete($tag);
    }

    public function delete($tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        $model = self::query()->getModel();
        /** @var Model $this */
        $query = $this->newModelQuery();
        $tag   = GetTagCacheService::execute($query, $tag);
        Cache::tags($tag)->flush();

        return $model->newQuery()->delete();
    }

    public static function create(array $attributes = [], array $joining = [], $touch = true, $tag = [])
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if ($queryActive !== null) {
            return false;
        }

        $model = self::query()->getModel();
        $tag   = GetTagCacheService::execute($model, $tag);
        Cache::tags($tag)->flush();

        return $model->newQuery()->create($attributes, $joining, $touch);
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
}
