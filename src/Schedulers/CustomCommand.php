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

        $this->disableActiveRecord();
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

    public function disableActiveRecord(): void
    {
        request()->headers->set(ModelCacheConst::HEADER_ACTIVE_RECORD, null);
        $modeRecord = $this->argument('mode_record');
        if (empty($modeRecord) === false && $modeRecord !== ModelCacheConst::ENABLE_ACTIVE_RECORD) {
            request()->headers->set(ModelCacheConst::HEADER_ACTIVE_RECORD, ModelCacheConst::DISABLE_ACTIVE_RECORD);
        }
    }
}
