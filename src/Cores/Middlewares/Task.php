<?php
namespace Application\Cores\Middlewares;

class Task
{
    private $container = null;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $next)
    {
        if (PHP_SAPI != 'cli') {
            return $response = $next($request, $response);
        }

        global $argv;

        $command = '';
        if (count($argv) > 1) {
            $command = $argv[1];
            $args = array_slice($argv, 2);
        }

        $class = '\\Application\\Tasks\\' . $command;
        $example = new $class($this->container);
        $example->run();

        return $response;
    }
}
