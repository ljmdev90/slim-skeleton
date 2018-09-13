<?php
namespace Application\Cores;

use Interop\Container\ContainerInterface;
use Medoo\Medoo;

use Application\Cores\Commons\Singleton\SingletonTrait;
use Application\Cores\Commons\Singleton\SingletonRegister;

class Model
{
    use SingletonTrait;

    protected $database;
    protected static $table = '';

    public $pk = 'id';

    private $settings = [];

    protected static $connections = [];

    private static $container = null;

    private function __construct()
    {
        if (!(self::$container instanceof ContainerInterface)) {
            throw new \Exception('Container in Model is not init.');
        }
        $this->settings = self::$container->get('settings')['db-cluster'];
    }

    public static function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
    }

    protected static function beforeGetInstance($table = '')
    {
        if ($table) {
            static::$table = $table;
        }
        if (!static::$table) {
            throw new \Exception("Table can't be empty.");
        }
    }

    public function getConnection()
    {
        $type = $this->read ? 'slave' : 'master';
        $setting = $this->settings[$type][array_rand($this->settings[$type])];
        $key = md5(json_encode($setting));
        return SingletonRegister::getInstance()->get($key, function() use ($setting) {
            return new Medoo($setting);
        });
    }

    /**
     * e.g.
     * $model->select/get/has...($where, $columns, $join);
     * $model->insert($data);
     * $model->update($where, $data);
     * $model->delete($where);
     */
    public function __call($method, $params)
    {
        $result = null;

        $this->read = in_array($method, ['select', 'get', 'has', 'count', 'sum', 'max', 'min', 'avg']);

        if ($this->read) {
            $params[1] = isset($params[1]) ? $params[1] : '*';
            if (isset($params[2]) && !empty($params[2])) {
                $result = $this->getConnection()->$method(static::$table, $params[2], $params[1], $params[0]);
            } else {
                $result = $this->getConnection()->$method(static::$table, $params[1], $params[0]);
            }
        }
        
        $this->write = in_array($method, ['insert', 'update', 'delete']);
        if ($this->write) {
            switch ($method) {
                case 'insert':
                    $this->getConnection()->$method(static::$table, $params[0]);
                    $result = $this->getConnection()->id();
                    break;
                case 'update':
                case 'delete':
                    if (!$params[0]) {    // 更新/删除条件不为为空，防止更新/删除全部数据
                        throw new \Exception(ucfirst($method) . ' condition cann\'t be empty.');
                    }
                    $cond = $method == 'update' ? $params[1] : $params[0];
                    $pdostate = $this->getConnection()->$method(static::$table, $cond, $params[0]);
                    $result = $pdostate->rowCount();
                    break;
            }
        }

        return $result;
    }

    public function lastSql()
    {
        return $this->getConnection()->last();
    }
}
