<?php
namespace Application\Cores\Libraries\Caches;

use Symfony\Component\Cache\Simple\RedisCache;
use Predis\Client as RedisClient;

class Redis
{
    public static $setting = [];
    private $client = null;

    public function __construct($configs = [])
    {
        if (empty($configs)) {
            throw new \Exception('configs is empty!');
        }
        $this->client = new RedisClient($configs);
        $this->cache = new RedisCache($this->client);
    }

    public function __call($method, $params)
    {
        if (method_exists($this->cache, $method)) {
            return $this->cache->$method(...$params);
        }
        if (method_exists($this->client, $method)) {
            return $this->client->$method(...$params);
        }
        throw new \Exception('Method \'' . $method . '\' not found!');
    }
}
