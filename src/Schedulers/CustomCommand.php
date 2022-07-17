<?php

namespace Litermi\Cache\Schedulers;

use Illuminate\Console\Command;
use Litermi\Cache\Models\ModelCacheConst;

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
                request()->headers->set(ModelCacheConst::HEADER_MODE_JOB, 'sync');
                break;
            default:
                dispatch($job)->onQueue($queue);
        }


    }

    public function disableQuery(): void
    {
        $disableQuery = $this->argument('disable_query');
        if (empty($disableQuery) === false && $disableQuery !== ModelCacheConst::ENABLE_ACTIVE_RECORD) {
            request()->headers->set(ModelCacheConst::HEADER_ACTIVE_RECORD, ModelCacheConst::DISABLE_ACTIVE_RECORD);
        }
    }
}
