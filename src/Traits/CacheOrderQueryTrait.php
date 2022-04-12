<?php

namespace Litermi\Cache\Traits;

use Litermi\Cache\Repositories\JoinBuilder\CacheBuilder;
use Litermi\Cache\Services\GetParametersOrderService;

/**
 *
 */
trait CacheOrderQueryTrait
{
    /**
     * @param $direction
     * @param $column
     * @return $this
     */
    public function orderByRequest(
        $direction = null,
        $column = null,
        $customAcceptColumns = [],
    ): CacheBuilder {
        /** @var CacheBuilder $this */
        [ $direction, $column ] = GetParametersOrderService::execute(
            $this,
            $direction,
            $column,
            $customAcceptColumns
        );
        if ($column === null || $direction === null) {
            return $this;
        }
        return $this->orderBy($column, $direction);
    }

}
