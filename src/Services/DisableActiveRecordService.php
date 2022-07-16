<?php

namespace Litermi\Cache\Services;


/**
 *
 */
class DisableActiveRecordService
{

    public static function execute($disableQuery): void
    {
        if (empty($disableQuery) === false && $disableQuery !== 'record_active') {
            request()->headers->set('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU', 1);
        }
    }
}
