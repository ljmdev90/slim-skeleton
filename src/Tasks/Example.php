<?php
namespace Application\Tasks;

class Example
{
    public function run($task_id, $from_id, $data)
    {
        echo "Task_id:$task_id\nFrom_id:$from_id\nData:$data\nRunning\n";
    }
}
