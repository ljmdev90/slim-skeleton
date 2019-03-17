<?php
namespace Application\Commands;

use Application\Cores\Command;

class Example extends Command
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
                $database = 'ssdb';
                $table = 'users';
                $pdostat = $db->query('CREATE DATABASE IF NOT EXISTS ' . $database . ' CHARSET utf8');
                $this->errorCheck($pdostat);
                $db->query('USE ' . $database);
                // 创建测试表
                $sql = 'CREATE TABLE IF NOT EXISTS `' . $table . '`(
                    `id` INT UNSIGNED AUTO_INCREMENT,
                    `name` VARCHAR(30) NOT NULL DEFAULT \'\',
                    `age` TINYINT UNSIGNED NOT NULL,
                    `add_time` INT UNSIGNED NOT NULL,
                    PRIMARY KEY (`id`)
                )';
                $pdostat = $db->query($sql);
                $this->errorCheck($pdostat);
                // 插入一些假数据
                $faker = \Faker\Factory::create('zh_CN');
                $insert_data = [];
                for ($i = 0; $i < 10; $i++) {
                    $insert_data[] = [
                        'name'  =>  $faker->name,
                        'age'   =>  mt_rand(20, 36),
                        'add_time'  =>  $faker->dateTimeBetween('-1 years', 'now')->getTimestamp(),
                    ];
                }
                $pdostat = $db->insert($table, $insert_data);
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
