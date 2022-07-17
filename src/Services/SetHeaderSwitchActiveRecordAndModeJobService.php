<?php

namespace Litermi\Cache\Services;


use Litermi\Cache\Models\ModelCacheConst;

/**
 *
 */
class SetHeaderSwitchActiveRecordAndModeJobService
{

    public static function execute($disableQuery, $typeJob): void
    {
        if (empty($disableQuery) === false && $disableQuery !== ModelCacheConst::ENABLE_ACTIVE_RECORD) {
            request()->headers->set(ModelCacheConst::HEADER_ACTIVE_RECORD, ModelCacheConst::DISABLE_ACTIVE_RECORD);
        }

        request()->headers->set(ModelCacheConst::HEADER_MODE_JOB, $typeJob);
    }
}
