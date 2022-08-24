<?php

namespace Litermi\Cache\Services;


use Litermi\Cache\Models\ModelCacheConst;

/**
 *
 */
class SetHeaderSwitchActiveRecordAndModeJobService
{

    public static function execute($enableActiveRecord, $typeJob): void
    {
        if (empty($enableActiveRecord) === false && $enableActiveRecord !== ModelCacheConst::ENABLE_ACTIVE_RECORD) {
            request()->headers->set(ModelCacheConst::HEADER_ACTIVE_RECORD, ModelCacheConst::DISABLE_ACTIVE_RECORD);
        }

        request()->headers->set(ModelCacheConst::HEADER_MODE_JOB, $typeJob);
    }
}
