<?php

namespace Litermi\Cache\Traits;

/**
 *
 */
trait DisableActiveRecordsByHeaderTraits
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if ($queryActive !== null) {
                return false;
            }
        });

        static::saving(function ($model) {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if ($queryActive !== null) {
                return false;
            }
        });

        static::updating(function ($model) {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if ($queryActive !== null) {
                return false;
            }
        });

        static::deleting(function ($model) {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if ($queryActive !== null) {
                return false;
            }
        });
    }
}
