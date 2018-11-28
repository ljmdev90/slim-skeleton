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

                // 创建测试数据库
                $table = 'ssdb';
                $pdostat = $db->query('CREATE DATABASE IF NOT EXISTS ' . $table . ' CHARSET utf8');
                $this->errorCheck($pdostat);
                $db->query('USE ' . $table);
                // 创建测试表
                $sql = 'CREATE TABLE IF NOT EXISTS `users`(
                    `id` INT UNSIGNED AUTO_INCREMENT,
                    `name` VARCHAR(30) NOT NULL DEFAULT \'\',
                    `age` TINYINT UNSIGNED NOT NULL,
                    `add_time` INT UNSIGNED NOT NULL,
                    PRIMARY KEY (`id`)
                )';
                $pdostat = $db->query($sql);
                $this->errorCheck($pdostat);

                $this->line('created!');
                break;
            }
        }
        $this->line('Bye.');
    }

    private function errorCheck($pdostat)
    {
        $error = $pdostat->errorInfo();
        if ($error[0] !== '00000') {
            $this->line($error[2]);
            exit;
        }
    }
}
