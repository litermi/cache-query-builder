<?php

namespace Litermi\Cache\Traits;


/**
 * Class GetUserAndSystemSessionService
 * @package App\Core\Base\Services
 */
class GetUserAndSystemSessionService
{

    /**
     * @return array|string|null
     */
    public static function getUserSession()
    {
        return request()->header('id-user');
    }

    /**
     * @return array|string|null
     */
    public static function getSystemSession()
    {
        return request()->header('id-system');
    }

}
