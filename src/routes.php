<?php
use Slim\Http\Request;
use Slim\Http\Response;
// Routes
$app->get('/hello[/{name}]', '\Application\\Modules\\Home\\Controllers\\Index:hello');

$app->group('',function() {
    $controller_name = '\Application\\Modules\\Home\\Controllers\\Index';
    $controller = new $controller_name($this);
    $slim = $this;
    $this->map(['GET','POST'], '[/{controller}[/{action}]]', function($req, $res, $args) use ($slim) {
        $controller_name = '\Application\\Modules\\Home\\Controllers\\';
        $controller_name .= isset($args['controller']) ? ucwords($args['controller']) : 'Index';
        $controller = new $controller_name($slim->getContainer());
       	return $controller($req, $res, $args);
    });
});
