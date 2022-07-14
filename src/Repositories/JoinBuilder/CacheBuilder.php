<?php

namespace Litermi\Cache\Repositories\JoinBuilder;

use Litermi\Cache\Traits\CacheOrderQueryTrait;
use Litermi\Cache\Traits\CachePaginateQueryTrait;
use Litermi\Cache\Traits\CacheQueryTrait;
use Litermi\Cache\Traits\DisableActiveRecordsTraits;
use Litermi\Cache\Traits\PurgeCacheBeforeActiveRecord;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class CacheBuilder extends Builder
{
    use CacheQueryTrait;
    use CachePaginateQueryTrait;
    use CacheOrderQueryTrait;
    use PurgeCacheBeforeActiveRecord;
    use DisableActiveRecordsTraits;
}
