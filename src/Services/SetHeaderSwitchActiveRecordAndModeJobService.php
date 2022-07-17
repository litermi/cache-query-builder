<?php

namespace Litermi\Cache\Services;


/**
 *
 */
class SetHeaderSwitchActiveRecordAndModeJobService
{

    public static function execute($disableQuery, $typeJob): void
    {
        if (empty($disableQuery) === false && $disableQuery !== 'record_active') {
            request()->headers->set('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU', "disable-query");
        }

        request()->headers->set('X80GEjobr3fwFWON6gn4egXsyncd9mode3y', $typeJob);
    }
}
