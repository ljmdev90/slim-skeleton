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
            $class = '\\Application\\Tasks\\' . $command;
            $example = new $class($this->container);
            
            $args = array_slice($argv, 2);
            $example->run($args);

            return $response;
        }

        throw new \InvalidArgumentException('The Command expects at least 2 parameters, 1 given.');
    }
}
