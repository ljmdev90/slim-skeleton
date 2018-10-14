<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function ($request, $response) {
    $response->getBody()->write("Hello World");
    return $response;
});

$app->map(['get', 'post'], '/hello[/{name}]', '\Application\\Modules\\Home\\Controllers\\Index:hello');

$app->group('', function () {
    $container = $this->getContainer();
    ;
    $this->map(['GET','POST'], '[/{controller}[/{action}]]', function ($req, $res, $args) use ($container) {
        $controller_name = '\Application\\Modules\\Home\\Controllers\\';
        $controller_name .= isset($args['controller']) ? ucwords($args['controller']) : 'Index';
        $controller = new $controller_name($container);
        
        $container['view'] = function ($c) {
            $view_path = __DIR__ . '/Modules/Home/Views';
            return new \Slim\Views\PhpRenderer($view_path);
        };

        return $controller($req, $res, $args);
    });
});
