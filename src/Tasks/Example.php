<?php
namespace Application\Tasks;

use Application\Cores\Task;

class Example extends Task
{
    public function run($params = [])
    {
        $menu = [
            'initdb' => '初始化数据库 Initialization database',
            'quit' => '退出 Quit',
        ];
        
        while (true) {
            $choice = $this->menu($menu, null, '选择 Choose');
        
            if ($choice == 'quit') {
                break;
            }

            if ($choice == 'initdb') {
                $db = $this->get('db');

                // todo 创建初始数据
            }
        }
        $this->line('Running...');
    }
}
