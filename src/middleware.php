<?php
namespace Application;

class TaskMiddleware
{
    private $container = null;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $next)
    {
        if (PHP_SAPI != 'cli') {
            $response = $next($request, $response);
        }

        global $argv;

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

$app->add(TaskMiddleware::class);
