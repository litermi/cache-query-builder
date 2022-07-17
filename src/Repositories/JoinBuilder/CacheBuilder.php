<?php

namespace Litermi\Cache\Repositories\JoinBuilder;

use Litermi\Cache\Traits\CacheOrderQueryTrait;
use Litermi\Cache\Traits\CachePaginateQueryTrait;
use Litermi\Cache\Traits\CacheQueryTrait;
use Litermi\Cache\Traits\DisableActiveRecordsByHeaderTraits;
use Litermi\Cache\Traits\PurgeCacheBeforeActiveRecordTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class CacheBuilder extends Builder
{
    use CacheQueryTrait;
    use CachePaginateQueryTrait;
    use CacheOrderQueryTrait;
    use PurgeCacheBeforeActiveRecordTrait;
    use DisableActiveRecordsByHeaderTraits;
}
