<?php

namespace Litermi\Cache\Services;


/**
 *
 */
class SwitchSyncModeService
{

    /**
     * @param $job
     * @param $queue
     * @return void
     */
    public static function dispatchJob($job, $queue): void
    {
        $typeJob = request()->headers->get('X80GEjobr3fwFWON6gn4egXsyncd9mode3y');
        switch ($typeJob) {
            case 'sync':
                dispatch_sync($job);
                request()->headers->set('X80GEjobr3fwFWON6gn4egXsyncd9mode3y', $typeJob);
                break;
            default:
                dispatch($job)->onQueue($queue);
        }
    }
}
