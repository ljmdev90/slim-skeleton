<?php
    $container = $app->getContainer();

    // database
    $container['db'] = function ($c) {
        $settings = $c->get('settings')['db'];
        return new Medoo\Medoo($settings);
    };

    // redis
    use Application\Cores\Libraries\Caches\Redis;

    $container['redis'] = function ($c) {
        return new Redis($c->get('settings')['redis']);     // 不再依赖单例trait, 因为pimple自带
    };

    // view 如果routes里重新定义了，这里的值会被覆盖
    $container['view'] = function ($c) {
        return new \Slim\Views\PhpRenderer('./templates/');
    };

    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new Monolog\Logger($settings['name']);
        $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };
