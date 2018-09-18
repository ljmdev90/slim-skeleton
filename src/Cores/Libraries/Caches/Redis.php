<?php
namespace Application\Cores\Libraries\Caches;

use Symfony\Component\Cache\Simple\RedisCache;
use Predis\Client as RedisClient;
use Application\Cores\Commons\Singleton\SingletonTrait;

class Redis
{
    use SingletonTrait;

    public static $setting = [];

    public function getConnection()
    {
        $setting = self::$setting;
        if (empty($setting)) {
            throw new \Exception('Setting is empty!');
        }
        return SingletonRegister::getInstance()->get($key, function () use ($setting) {
            return new RedisCache(new RedisClient($setting));
        });
    }
}