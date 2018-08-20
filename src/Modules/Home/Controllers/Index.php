<?php
namespace Application\Modules\Home\Controllers;

use Application\Cores\Controller;

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
        var_dump($db);
    }
}