<?php
namespace Application\Cores\Libraries\Caches;

use MongoDB\Client;

class MongoDB
{
    public static $setting = [];
    private $client = null;

    public function __construct($configs = [])
    {
        if (empty($configs)) {
            throw new \Exception('configs is empty!');
        }
        $uri = $configs['uri'] ?? 'mongodb://127.0.0.1/';
        $this->client = new Client($uri);
    }

    public function __call($method, $params)
    {
        if (method_exists($this->client, $method)) {
            return $this->client->$method(...$params);
        }
        throw new \Exception('Method \'' . $method . '\' not found!');
    }
}