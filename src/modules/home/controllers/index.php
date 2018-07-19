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
}