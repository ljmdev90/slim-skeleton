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

        $initdb_meanu = [
            'host'  =>  '请输入数据库主机名[127.0.0.1]',
            'port'  =>  '请输入数据库主机端口号[3306]',
            'user'  =>  '请输入数据库用户名',
            'password'  =>  '请输入数据库密码',
        ];
        
        while (true) {
            $choice = $this->menu($menu, null, '选择 Choose');
        
            if ($choice == 'quit') {
                break;
            }

            if ($choice == 'initdb') {
                $config = [];
                foreach ($initdb_meanu as $key => $question) {
                    preg_match('/\[([\w\d\.]+)\]/', $question, $matches);
                    $default = isset($matches[1]) ? $matches[1] : false;
                    $config[$key] = \cli\prompt($question, $default);
                }
                if (empty($config)) {
                    $this->line('config不能为空');
                    continue;
                }

                // todo 链接数据库、创建初始数据
            }
        }
        $this->line('Running...');
    }
}
