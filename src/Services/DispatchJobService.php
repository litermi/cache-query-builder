<?php

namespace Litermi\Cache\Services;


use Litermi\Cache\Models\ModelCacheConst;

/**
 *
 */
class DispatchJobService
{

    /**
     * @param $job
     * @param $queue
     * @param $mode
     * @return void
     */
    public static function execute($job, $queue): void
    {
        $typeJob = request()->header(ModelCacheConst::HEADER_MODE_JOB);
        switch ($typeJob) {
            case 'sync':
                request()->headers->set(ModelCacheConst::HEADER_MODE_JOB, 'sync');
                dispatch_sync($job);
                request()->headers->set(ModelCacheConst::HEADER_MODE_JOB, 'sync');
                break;
            default:
                dispatch($job)->onQueue($queue);
        }
    }
}
