<?php
namespace Application\Tasks;

use Application\Cores\Task;

class Example extends Task
{
    public function run($params = [])
    {
        $this->line('Running...');
    }
}
