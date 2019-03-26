<?php
namespace Application\Modules\Home\Controllers;

use Application\Cores\Controller;
use Application\Cores\Model;
use Application\Cores\Libraries\Caches\Redis;
# use Application\Modules\Home\Models\Users;
use Application\Models\Users;

class Example extends Controller
{
    
    public function hello($request, $response, $args)
    {
        return $response->write($args['name']);
    }

    public function fuck($request, $response, $args)
    {
        return $response->withJson(['ok']);
    }

    public function view($request, $response, $args)
    {
        return $this->render(['a'=>1, 'b'=>2]);
    }

    public function db($request, $response, $args)
    {
        $users = Model::getInstance('users');
        $users_2 = Users::getInstance();
        var_dump($users->getConnection() === $users_2->getConnection());
        var_dump($users === $users_2);
        // for ($i = 0; $i < 10; $i++) {
        //     $res = $users->insert(['name'=> 'zhangsan'.\mt_rand(1,100), 'age'=>mt_rand(18, 35),
        //          'group'=>mt_rand(1,3), 'add_time'=>time() + mt_rand(-1000, 1000)]);
        // }
        // $res = $users->select(['id[>]'=>'50']);
        // $res = $users->select(['users.id[>]'=>'50'], ['users.id', 'users.name', 'users.group',
        //          't2.group(group_name)'], ['[>]t2'=>['group'=>'id']]);
        // $res = $users->get(['name[~]'=>'zhangsan']);
        // $res = $users->avg(['name[~]'=>'zhangsan'], 'age');
        $res = $users->update(['id'=>'54'], ['age[+]'=>1]);
        // $res = $t1->delete(['id'=>5]);
        var_dump($res);
        echo ($users->lastSql());
    }

    public function redis()
    {
        $redis = $this->get('redis');
        var_dump($redis->set('a', 1));
        var_dump($redis->get('a'));
    }

    public function log()
    {
        $logger = $this->get('logger');
        $logger->addNotice('Notice log...');    // 这一条不会写入日志文件
        $logger->addWarning('Warn log...');
        $logger->addError('Error log...');
    }

    // 只有在cli模式下才能正常访问
    public function swoole()
    {
        # sleep(1);
        swoole_timer_after(1000, function () {
            var_dump('timer after 1000');
        });
        $serv = $this->get('slim-server');
        $serv->newTask('Example', 'test data', -1, function($serv, $task_id, $data) {
            echo 'task_id:', $task_id, "\n";
            echo 'data:', $data, "\n";
        });
        var_dump('swoole');
    }
}
