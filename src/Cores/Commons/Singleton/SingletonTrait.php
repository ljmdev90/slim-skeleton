<?php
namespace Application\Cores\Commons\Singleton;

trait SingletonTrait
{
    protected static $instances;

    protected function __contruct()
    {
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    final public function __clone()
    {
        throw new \Exception('The method __clone is deny in singleton.');
    }

    final public function __sleep()
    {
        throw new \Exception('The method __sleep is denied in singleton.');
    }

    final public function __wakeup()
    {
        throw new \Exception('The method __wakeup is denied in singleton.');
    }

    protected static function beforeGetInstance($class)
    {
    }

    public static function getInstance($class = '')
    {
        static::beforeGetInstance($class);

        $class = isset($class) && !empty($class) ? $class : get_called_class();

        $key = md5($class);
        if (!isset(self::$instances[$key]) || empty(self::$instances[$key])) {
            self::$instances[$key] = new static;
        }

        static::afterGetInstance(self::$instances[$key]);

        return self::$instances[$key];
    }

    protected static function afterGetInstance($instance)
    {
    }
}
