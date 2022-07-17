<?php

namespace Litermi\Cache\Traits;

use Litermi\Cache\Models\ModelCacheConst;

/**
 *
 */
trait DisableActiveRecordsByHeaderTraits
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $queryActive = request()->header(ModelCacheConst::HEADER_ACTIVE_RECORD);
            if ($queryActive !== null) {
                return false;
            }
        });

        static::saving(function ($model) {
            $queryActive = request()->header(ModelCacheConst::HEADER_ACTIVE_RECORD);
            if ($queryActive !== null) {
                return false;
            }
        });

        static::updating(function ($model) {
            $queryActive = request()->header(ModelCacheConst::HEADER_ACTIVE_RECORD);
            if ($queryActive !== null) {
                return false;
            }
        });

        static::deleting(function ($model) {
            $queryActive = request()->header(ModelCacheConst::HEADER_ACTIVE_RECORD);
            if ($queryActive !== null) {
                return false;
            }
        });
    }
}
