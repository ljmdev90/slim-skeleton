<?php
namespace Application\Cores\Libraries\Caches;

use Symfony\Component\Cache\Simple\RedisCache;
use Predis\Client as RedisClient;
use Application\Cores\Commons\Singleton\SingletonTrait;

class Redis
{
    use SingletonTrait;

    public static $setting = [];
    private $client = null;

    public function getConnection()
    {
        $setting = self::$setting;
        if (empty($setting)) {
            throw new \Exception('Setting is empty!');
        }
        $client = $this->client = new RedisClient($setting);
        return SingletonRegister::getInstance()->get($key, function () use ($client) {
            return new RedisCache($client);
        });
    }

    public function __call($method, $params)
    {
        $conn = $this->getConnection();
        if (method_exists($conn, $name)) {
            return $conn->$name(...$params);
        }
        if (method_exists($this->client, $name)) {
            return $this->client->$name(...$params);
        }
        throw new \Exception('Method '.$method.' not found!');
    }
}
