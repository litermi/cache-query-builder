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
    public static function execute($job, $queue): void
    {
        $typeJob = request()->header('X80GEjobr3fwFWON6gn4egXsyncd9mode3y');
        switch ($typeJob) {
            case 'sync':
                dispatch_sync($job);
                break;
            default:
                dispatch($job)->onQueue($queue);
        }
    }
}
