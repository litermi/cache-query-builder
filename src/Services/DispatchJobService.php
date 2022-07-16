<?php

namespace Litermi\Cache\Services;


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
    public static function execute($job, $queue, $mode): void
    {
        $typeJob = $mode;
        request()->headers->set('X80GEjobr3fwFWON6gn4egXsyncd9mode3y', $typeJob);
        switch ($typeJob) {
            case 'sync':
                dispatch_sync($job);
                break;
            default:
                dispatch($job)->onQueue($queue);
        }
    }
}
