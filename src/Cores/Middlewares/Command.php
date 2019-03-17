<?php
namespace Application\Cores\Middlewares;

/**
 * 命令行任务中间件类
 */
class Command
{
    private $container = null;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * 当把该类的对象以方法的方式调用时触发,类似Controller基类
     */
    public function __invoke($request, $response, $next)
    {
        // 非命令行时,当作普通请求处理
        if (PHP_SAPI != 'cli') {
            return $response = $next($request, $response);
        }

        global $argv;

        $command = '';
        if (count($argv) > 1) {
            $command = $argv[1];
            $class = '\\Application\\Commands\\' . $command;
            $example = new $class($this->container);
            
            $args = array_slice($argv, 2);
            $example->run($args);

            return $response;
        }

        throw new \InvalidArgumentException('The Command expects at least 2 parameters, 1 given.');
    }
}
