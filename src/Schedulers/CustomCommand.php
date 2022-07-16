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
    public function dispatchJob($job): void
    {
        $typeJob = $this->argument('type_job');

        $this->disableQuery();
        switch ($typeJob) {
            case 'sync':
                dispatch_sync($job);
                break;
            default:
                dispatch($job)->onQueue(config('queue-names.general'));
        }


    }

    public function disableQuery(): void
    {
        $disableQuery = $this->argument('disable_query');
        if (empty($disableQuery) === false && $disableQuery !== 'insert_active') {
            request()->headers->set('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU', 1);
        }
    }
}
