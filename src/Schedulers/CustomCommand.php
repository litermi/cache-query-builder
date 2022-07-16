<?php

namespace Litermi\Cache\Schedulers;

use Illuminate\Console\Command;

/**
 *
 */
class CustomCommand extends Command
{

    /**
     * @param $job
     * @return void
     */
    public function dispatchJob($job, $queue): void
    {
        $typeJob = $this->argument('type_job');

        $this->disableQuery();
        switch ($typeJob) {
            case 'sync':
                dispatch_sync($job);
                request()->headers->set('X80GEjobr3fwFWON6gn4egXsyncd9mode3y', 'sync');
                break;
            default:
                dispatch($job)->onQueue($queue);
        }


    }

    public function disableQuery(): void
    {
        $disableQuery = $this->argument('disable_query');
        if (empty($disableQuery) === false && $disableQuery !== 'record_active') {
            request()->headers->set('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU', 1);
        }
    }
}
