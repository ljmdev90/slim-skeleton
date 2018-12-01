<?php
namespace Application\Cores\Commons\Singleton;

class SingletonRegister
{
    use SingletonTrait;

    public function set($key, &$instance)
    {
        self::$instances[$key] = $instance;
    }

    public function get($key, $callback)
    {
        if (!isset(self::$instances[$key]) || !is_object(self::$instances[$key])) {
            if (!is_callable($callback)) {
                throw new \Exception('The param $callback must be callable.');
            }
            $instance = $callback();
            self::set($key, $instance);
        }
        return self::$instances[$key];
    }
}
