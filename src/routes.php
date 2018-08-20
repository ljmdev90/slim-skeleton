<?php
use Slim\Http\Request;
use Slim\Http\Response;
// Routes
$app->get('/', function($request, $response) {
    $response->getBody()->write("Hello World");
    return $response;
});

$app->get('/hello[/{name}]', '\Application\\Modules\\Home\\Controllers\\Index:hello');

$app->group('',function() {
    $slim = $this;
    $this->map(['GET','POST'], '[/{controller}[/{action}]]', function($req, $res, $args) use ($slim) {
        $controller_name = '\Application\\Modules\\Home\\Controllers\\';
        $controller_name .= isset($args['controller']) ? ucwords($args['controller']) : 'Index';
        $controller = new $controller_name($slim->getContainer());
       	return $controller($req, $res, $args);
    });
});
