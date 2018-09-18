<?php
namespace Application\Modules\Home\Controllers;

use Application\Cores\Controller;
use Application\Cores\Model;
use Application\Cores\Libraries\Caches\Redis;
use Application\Modules\Home\Models\T1;

class Index extends Controller
{
    
    public function hello($request, $response, $args)
    {
        return $response->write('ok');
    }

    public function fuck($request, $response, $args)
    {
        return $response->withJson(['ok']);
    }

    public function view($request, $response, $args)
    {
        return $this->render(['a'=>1, 'b'=>2]);
    }

    public function dbtest($request, $response, $args)
    {
        $db = $this->get('db');
        // var_dump($db);
        $t1 = Model::getInstance('t1');
        $t1_2 = T1::getInstance();
        var_dump($t1->getConnection() === $t1_2->getConnection());
        var_dump($t1 === $t1_2);
        // for ($i = 0; $i < 10; $i++) {
        //     $res = $t1->insert(['name'=> 'zhangsan'.\mt_rand(1,100), 'age'=>mt_rand(18, 35),
        //          'group'=>mt_rand(1,3), 'add_time'=>time() + mt_rand(-1000, 1000)]);
        // }
        // $res = $t1->select(['id[>]'=>'50']);
        // $res = $t1->select(['t1.id[>]'=>'50'], ['t1.id', 't1.name', 't1.group',
        //          't2.group(group_name)'], ['[>]t2'=>['group'=>'id']]);
        // $res = $t1->get(['name[~]'=>'zhangsan']);
        // $res = $t1->avg(['name[~]'=>'zhangsan'], 'age');
        $res = $t1->update(['id'=>'54'], ['age[+]'=>1]);
        // $res = $t1->delete(['id'=>5]);
        var_dump($res);
        echo ($t1->lastSql());
    }

    public function redistest()
    {
        $redis = Redis::getInstance();
        // var_dump($redis);
        var_dump($redis);
        $redis = $this->get('redis');
        var_dump($redis);
    }
}
