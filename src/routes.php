<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function ($request, $response) {
    $response->getBody()->write("Hello World");
    return $response;
});

$app->any('/hello[/{name}]', '\Application\\Modules\\Home\\Controllers\\Example:hello');

$app->group('', function () {
    $container = $this->getContainer();
    
    $this->map(['GET','POST'], '[/{module}[/{controller}[/{action}]]]', function ($req, $res, $args) use ($container) {
        if (!isset($args['action']) || empty($args['action'])) {
            $module = 'Home';
            $controller = isset($args['module']) ? ucwords($args['module']) : 'Index';
            $args['action'] = $args['controller'];
            $args['controller'] = $controller;
        } else {
            $module = ucwords($args['module']);
            $controller = isset($args['controller']) ? ucwords($args['controller']) : 'Index';
        }
        $controller_name = '\Application\\Modules\\' . $module . '\\Controllers\\';
        $controller_name .= $controller;
        $controller = new $controller_name($container);
        
        $container['view'] = function ($c) {
            $view_path = __DIR__ . '/Modules/Home/Views';
            return new \Slim\Views\PhpRenderer($view_path);
        };

        return $controller($req, $res, $args);
    });
});
