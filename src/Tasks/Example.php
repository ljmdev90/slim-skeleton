<?php
namespace Application\Tasks;

use Application\Cores\Controller;

class Example extends Controller
{
    public function run($params = [])
    {
        echo 'running...' . PHP_EOL;
    }
}
