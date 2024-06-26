<?php

namespace Litermi\Cache\Models;

use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;
use Litermi\Cache\Traits\DisableActiveRecordsByHeaderTraits;
use Litermi\Cache\Traits\PurgeCacheBeforeActiveRecordTrait;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

/**
 *
 */
class CacheModel extends Model
{
    use PurgeCacheBeforeActiveRecordTrait;
    use DisableActiveRecordsByHeaderTraits;
    use PowerJoins;

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new CacheBuilder($query);
    }

}
