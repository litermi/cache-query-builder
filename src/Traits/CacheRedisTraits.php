<?php

namespace Litermi\Cache\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Litermi\Cache\Classes\CacheConst;
use Litermi\ErrorNotification\Services\CatchNotificationService;
use Litermi\Logs\Facades\LogConsoleFacade;
use Litermi\Response\Traits\ResponseTrait;
use Psr\SimpleCache\InvalidArgumentException;
use function App\Core\Base\Traits\str_slug;

/**
 * Trait CacheRedisTraits
 *
 * @package App\Traits
 */
trait CacheRedisTraits
{

    Use ResponseTrait;
    use TextUtilsTraits;

    /**
     * Extract the key saved in redis
     *
     * @param string $key      name of the key
     * @param string $idUser   id of user
     * @param        $idSystem
     * @param string $database name of the database redis
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function getCache( $key, $idUser = '', $idSystem = '', $database = '' )
    {
        $keyCustoms = $this->generateKeyCacheAuth($key, $idUser, $idSystem);

        $response = Cache::get($keyCustoms);

        $array                 = [
            'message'          => 'get cache',
            'key_custom_cache' => $keyCustoms,
            'id-user'          => $idUser,
            'id-system'        => $idSystem,
            'value'            => $response,
        ];


        LogConsoleFacade::full()->log('cache', $array);

        return $response;
    }

    /**
     * Save or update the key in redis
     *
     * @param string $key      name of the key
     * @param mixed  $values   the object to be saved
     * @param int    $time     time in session
     * @param string $idUser   id of user
     * @param        $idSystem
     * @param string $database name of the database redis
     */
    protected function setCache($key, $values, $time, $idUser = '', $idSystem = '', $database = '' ): void
    {
        $keyCustoms = $this->generateKeyCacheAuth($key, $idUser, $idSystem);

        $array                 = [
            'message'          => 'set cache',
            'key_custom_cache' => $keyCustoms,
            'id-user'          => $idUser,
            'id-system'        => $idSystem,
            'value'            => $values,
        ];

        LogConsoleFacade::full()->log('access', $array);

        Cache::put($keyCustoms, $values, $time);
    }

    /**
     * Validate if your key exists in redis
     *
     * @param string $key      name of the key
     * @param string $idUser   id of user
     * @param        $idSystem
     * @param string $database name of the database redis
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function hasCache( $key, $idUser = '', $idSystem = '', $database = '' ): bool
    {

        $keyCustoms = $this->generateKeyCacheAuth( $key, $idUser, $idSystem );
        if ( !empty( $database ) ) {
            if ( !empty( Cache::store( $database )->has( $keyCustoms ) ) ) {
                $status = true;
            } else {
                $status = false;
            }
        } elseif ( empty( Cache::has( $keyCustoms ) ) ) {
            $status = false;
        } else {
            $status = true;
        }

        return $status;
    }

    /**
     * Delete the key saved in redis
     *
     * @param string $key      name of the key
     * @param string $idUser   id of user
     * @param        $idSystem
     * @param string $database name of the database redis
     * @return void
     */
    protected function forgetCache( $key, $idUser = '', $idSystem = '', $database = '' ): void
    {

        $keyCustoms = $this->generateKeyCacheAuth( $key, $idUser, $idSystem );

        if ( !empty( $database ) ) {
            Cache::store( $database )->forget( $keyCustoms );
        } else {
            Cache::forget( $keyCustoms );
        }
    }

    /**
     * Initiating the progress in 1%
     *
     * @param $name
     * @param $time
     * @param $user
     * @param $idSystem
     */
    protected function cacheProgressStarting( $name, $time, $idUser, $idSystem ): void
    {
        $this->forgetCache( $name, $idUser, $idSystem );
        $response = [ 'percentage' => 1, 'status' => 'Starting', 'success' => true ];
        $this->setCache($name, $response, $time, $idUser, $idSystem );
    }

    /**
     * Process progress in redis
     *
     * @param       $name
     * @param       $time
     * @param       $user
     * @param       $idSystem
     * @param       $progress
     * @param array $params
     */
    protected function cacheProgressProcessing( $name, $time, $idUser, $idSystem, $progress, $params = [] ): void
    {

        if ( !empty( $params ) ) {
            $response = array_merge( [
                'percentage' => ceil( $progress ),
                'status'     => 'Processing',
                'success'    => true,
            ], $params );
        } else {
            $response = [ 'percentage' => ceil( $progress ), 'status' => 'Processing', 'success' => true ];
        }

        $this->setCache($name, $response, $time, $idUser, $idSystem );
    }

    /**
     * Complete process in 100%
     *
     * @param       $name
     * @param       $time
     * @param       $user
     * @param       $idSystem
     * @param array $params
     * @return void
     */
    protected function cacheProgressComplete( $name, $time, $idUser, $idSystem, $params = [] ): void
    {

        if ( empty( $params ) === false ) {
            $response = [ 'percentage' => 100, 'status' => 'Complete', 'success' => true ];
            $response = array_merge($response , $params );
        } else {
            $response = [ 'percentage' => 100, 'status' => 'Complete', 'success' => true ];
        }

        $this->setCache($name, $response, $time, $idUser, $idSystem );
    }

    /**
     * Execute a failed process
     *
     * @param       $name
     * @param       $time
     * @param       $user
     * @param       $idSystem
     * @param array $params
     */
    protected function cacheProgressFails( $name, $time, $idUser, $idSystem, $params = [] ): void
    {

        if ( !empty( $params ) ) {
            $response = array_merge( [ 'percentage' => 0, 'status' => 'Fails', 'success' => false ], $params );
        } else {
            $response = [ 'percentage' => 0, 'status' => 'Fails', 'success' => false ];
        }

        $this->setCache($name, $response, $time, $idUser, $idSystem );
    }

    /**
     * Delete the progress after complete
     *
     * @param $name
     * @param $idUser
     * @param $idSystem
     */
    protected function cacheProgressForget( $name, $idUser, $idSystem ): void
    {

        $this->forgetCache( $name, $idUser, $idSystem );
    }

    /**
     * Returns the status of the process
     *
     * @param       $identify
     * @param       $idUser
     * @param       $idSystem
     * @param array $params
     * @return array
     * @throws InvalidArgumentException
     */
    protected function statusProgressJobs( $identify, $idUser, $idSystem, $params = [] ): array
    {

        $response      = null;
        $data          = null;
        $responseCache = $this->getCache( $identify, $idUser, $idSystem );
        if ( $responseCache !== null ) {
            $data = $this->responseValue( $responseCache, $params );
            switch ( $data ) {
                case ( ( ( (int) $data[ 'percentage' ] < 100 ) && ( (int) $data[ 'percentage' ] > 0 ) ) && (boolean) $data[ 'success' ] === true ):
                    $data[ 'code' ] = Response::HTTP_TOO_EARLY;
                    $flushCache     = false;
                    break;
                case ((int)$data['percentage'] === 100 && (boolean)$data['success'] === true):
                    $data['code'] = Response::HTTP_OK;
                    $flushCache = true;
                    break;
                case ((int)$data['percentage'] === 0 && (boolean)$data['success'] === false):
                    $data['code'] = Response::HTTP_NOT_FOUND;
                    $flushCache = false;
                    break;
                default:
                    $data['code'] = Response::HTTP_BAD_REQUEST;
                    $flushCache = false;
                    break;
            }
            $response = $data;

            if ($flushCache) {
                /** elimina todos las respuestas despues del 100% **/
                $this->forgetCache( $identify, $idUser, $idSystem );
            }
        } else {

            $response = [ 'success' => false, 'code' => Response::HTTP_BAD_REQUEST ];
        }

        return $response;
    }

    /**
     * @param $code
     * @param $successMessage
     * @param $messageProgress
     * @param $errorMessage
     * @return mixed
     */
    public function getStatusFromCode( $code, $successMessage, $messageProgress, $errorMessage )
    {
        switch ( $code ) {
            case Response::HTTP_TOO_EARLY:
                return $messageProgress;
                break;
            case Response::HTTP_NOT_FOUND:
            case Response::HTTP_OK:
                return $successMessage;
                break;
            default:
                return $errorMessage;
                break;
        }
    }

    /**
     * valid information if you need a parameter
     *
     * @param $response
     * @param $params
     * @return array
     */
    private function responseValue($response, $params): array
    {
        if (empty($params)) {
            $responseData = $response;
        } else {
            $responseData = array_merge($response, $params);
        }

        return $responseData;
    }

    /**
     * Generate identify dynamic in the redis
     *
     * @param        $key
     * @param string $idUser
     * @param string $idSystem
     * @return string
     */
    private function generateKeyCacheAuth( $key, $idUser = '', $idSystem = '' ): string
    {

        return str_slug( $key . '-' .$idUser . ' - ' . $idSystem, '_' );
    }

    /**
     * @param $key
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function getKeysByPattern( $key ): array
    {

        return Redis::connection( 'default' )->keys( $key );
    }

    /**
     * @param $key
     * @return array
     */
    protected function keyPatternTransform( $key ): array
    {

        $newKey     = null;
        $user       = null;
        $idSystem  = null;
        $explodeKey = explode( ':', $key );
        if ( array_key_exists( 1, $explodeKey ) ) {
            $explodeKey   = explode( '_', $explodeKey[ 1 ] );
            $explodeCount = count( $explodeKey );
            $user         = $explodeKey[ 0 ];
            $idSystem    = $explodeKey[ 1 ];
            unset( $explodeKey[ $explodeCount - 1 ] );
            unset( $explodeKey[ 0 ] );
            unset( $explodeKey[ 1 ] );
            $newKey = $user . '-' . $idSystem;
            foreach ( $explodeKey as $load ) {
                $newKey .= '-' . $load;
            }
        }

        return [ $newKey, $user, $idSystem ];
    }

    /**
     * @param array $keys
     * @return array
     * @throws InvalidArgumentException
     */
    protected function getCacheByArrayKeys( array $keys ): array
    {

        $response = [];
        foreach ( $keys as $key ) {
            [ $newKey, $idUser, $idSystem ] = $this->keyPatternTransform( $key );
            if ( $newKey !== null ) {
                $data       = $this->getCache( $newKey, $idUser, $idSystem );
                $response[] = $data;
            }
        }

        return $response;
    }

    /**
     * @param array $keys
     */
    protected function deleteCacheByArrayKeys( array $keys ): void
    {

        foreach ( $keys as $key ) {
            [ $newKey, $idUser, $idSystem ] = $this->keyPatternTransform( $key );
            if ( $newKey !== null ) {
                $this->forgetCache( $newKey, $idUser, $idSystem );
            }
        }
    }

    /**
     * @param       $idUser
     * @param       $idSystem
     * @param       $model
     * @param array $keys
     * @param array $data
     * @param int   $time
     */
    protected function setCacheByKeyHandMade( $idUser, $idSystem, $model, $keys = [], $data = [], $time = 30 ): void
    {

        $key = "{$idUser}-{$idSystem}-{$model}";
        foreach ( $keys as $load ) {
            $key = $load === '' ? $key : $key . '-' . $load;
        }
        $this->setCache($key, $data, $time, $idUser, $idSystem );
    }

    /**
     * @param       $idUser
     * @param       $idSystem
     * @param       $model
     * @param array $keys
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function getCacheByKeyHandMade( $idUser, $idSystem, $model, $keys = [] )
    {

        $key = "{$idUser}-{$idSystem}-{$model}";
        foreach ( $keys as $load ) {
            $key = $load === '' ? $key : $key . '-' . $load;
        }

        return $this->getCache( $key, $idUser, $idSystem );
    }

    /**
     * @param       $idUser
     * @param       $idSystem
     * @param       $model
     * @param array $keys
     */
    protected function getForgetCacheByKeyHandMade( $idUser, $idSystem, $model, $keys = [] ): void
    {

        $key = "{$idUser}-{$idSystem}-{$model}";
        foreach ( $keys as $load ) {
            $key = $load === '' ? $key : $key . '-' . $load;
        }
        $this->forgetCache( $key, $idUser, $idSystem );
    }

    /**
     * @param     $idSystem
     * @param     $idUser
     * @param     $model
     * @param     $key
     * @param int $time
     * @return callable
     */
    public function mapKeyCache( $idSystem, $idUser, $model, $key, $time = 30 ): callable
    {

        return function ( $item ) use ( $idSystem, $idUser, $model, $key, $time ) {

            $itemKey = $item[ $key ];
            $this->setCacheByKeyHandMade( $idUser, $idSystem, $model, [ $itemKey ], $item, $time );

            return $item;
        };
    }

    /**
     * @param $idSystem
     * @param $idUser
     * @param $model
     * @param $key
     * @return callable
     */
    public function mapForgetKeyCache( $idSystem, $idUser, $model, $key ): callable
    {

        return function ( $item ) use ( $idSystem, $idUser, $model, $key ) {

            $itemKey = $item[ $key ];
            $this->getForgetCacheByKeyHandMade( $idUser, $idSystem, $model, [ $itemKey ] );

            return $item;
        };
    }

    /**
     * @param       $idSystem
     * @param       $idUser
     * @param       $model
     * @param array $keys
     * @param int   $time
     * @return callable
     */
    public function mapKeysArrayCache( $idSystem, $idUser, $model, array $keys, $time = 30 ): callable
    {

        return function ( $item ) use ( $idSystem, $idUser, $model, $keys, $time ) {

            $newsKeys = [];
            foreach ( $keys as $key ) {
                $newsKeys[] = $item[ $key ];
            }

            $this->setCacheByKeyHandMade( $idUser, $idSystem, $model, $newsKeys, $item, $time );

            return $item;
        };
    }

    /**
     * @param       $idSystem
     * @param       $idUser
     * @param       $model
     * @param array $keys
     * @return callable
     */
    public function mapForgetKeysArrayCache( $idSystem, $idUser, $model, array $keys ): callable
    {

        return function ( $item ) use ( $idSystem, $idUser, $model, $keys ) {

            $this->getForgetCacheByKeyHandMade( $idUser, $idSystem, $model, $keys );

            return $item;
        };
    }

    /**
     * @param $idUser
     * @param $idSystem
     * @param $keyPattern
     */
    protected function getForgetCacheByKeyPattern( $idUser, $idSystem, $keyPattern ): void
    {

        $userCompany = "laravel:{$idUser}_{$idSystem}";
        $key         = $userCompany . "_" . $keyPattern;
        $arrayKeys   = $this->getKeysByPattern( $key );
        $this->deleteCacheByArrayKeys( $arrayKeys );
    }

    /**
     * @param $nameJob
     * @param $data
     * @return string
     */
    public function setCacheDataJob($nameJob, $data):
    string {

        $idSystem  = GetUserAndSystemSessionService::getSystemSession();
        $idUser    = GetUserAndSystemSessionService::getUserSession();
        $customName = $this->createCustomName($nameJob);

         $this->setCache( $customName, $data, CacheConst::CACHE_TIME_DAY,$idUser, $idSystem );

        return $customName;
    }

    /**
     * @param $customName
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getCacheDataJob($customName)
     {

        /*session user*/
        $idSystem  = GetUserAndSystemSessionService::getSystemSession();
        $idUser    = GetUserAndSystemSessionService::getUserSession();

         return $this->getCache($customName, $idUser, $idSystem );
    }

    /**
     * @param     $nameJob
     * @param int $len
     * @return string
     */
    public function createCustomName($nameJob, $len = 10): string
    {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        $randomString =substr(implode($word), 0, $len);
        $randomString .= "_" . $nameJob;
        return $randomString;

    }

    /**
     * @param $name
     * @param $successMessage
     * @param $messageProgress
     * @param $errorMessage
     * @return JsonResponse|null
     * @throws InvalidArgumentException
     */
    public function progressJobs($name, $successMessage, $messageProgress, $errorMessage ): ?JsonResponse
    {

        try {
            /*session user*/
            $idSystem  = GetUserAndSystemSessionService::getSystemSession();
            $idUser    = GetUserAndSystemSessionService::getUserSession();

            /*get template from redis*/
            $data   = $this->statusProgressJobs( $name, $idUser, $idSystem );
            $code   = Response::HTTP_BAD_REQUEST;
            $code   = $this->checkOrEmpty( $data, 'code', $code );

            /*check if status is processing and return message directly*/
            $successMessage = $this->getStatusFromCode( $code, $successMessage, $messageProgress, $errorMessage);

            return $this->successResponseWithMessage(
                $data,
                $successMessage,
                $code
            );
        }
        catch ( Exception $exception ) {
            CatchNotificationService::error(
                [
                    'exception' => $exception,
                    'usersId'   => Auth::id(),
                ]
            );

            return $this->errorCatchResponse(
                $exception,
                $errorMessage,
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }
    }

}
