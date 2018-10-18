<?php

$serv = new Swoole\Http\Server('0.0.0.0', 9501);
$serv->on('WorkerStart', function () {
    require __DIR__ . '/../vendor/autoload.php';
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
});

$serv->on('request', function ($request, $response) {
    // 设置$_SERVER，使slim能够读取到其中的值
    foreach ($request->server as $key => $val) {
        // 如果不指定以下代码，$_SERVER中无REQUEST_METHOD等项，导致method not allow
        $_SERVER[strtoupper($key)] = $val;
    };

    // 设置$_GET、$_POST、$_COOKIE、$_FILES
    $_GET = $request->get;
    $_POST = $request->post;
    $_COOKIE = $request->cookie;
    $_FILES = $request->files;  // 不知道为什么,这里postman测试一直没值

    ob_start();
    $env = __DIR__ . '/../.env';
    if (file_exists($env)) {
        $dotenv = new Dotenv\Dotenv(dirname($env));
        $dotenv->load();
    }
    $settings = require __DIR__ . '/../src/settings.php';
    $app = new \Slim\App($settings);
    require __DIR__ . '/../src/dependencies.php';
    require __DIR__ . '/../src/middleware.php';
    require __DIR__ . '/../src/routes.php';
    $app->run();
    $result = ob_get_contents();
    ob_end_clean();
    $response->end($result);
});

$serv->start();
