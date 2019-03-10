<?php

$pid_file = '/tmp/server.pid';

$oper = isset($argv[1]) ? strtolower($argv[1]) : 'start';

if (!in_array($oper, ['start', 'stop', 'reload'])) {
    exit("Parameter 1 is invalid!\n");
}

if (file_exists($pid_file) && $pid = file_get_contents($pid_file)) {
    $is_exist = Swoole\Process::kill($pid, 0);
    switch ($oper) {
        case 'start':
            if ($is_exist) {
                exit("Swoole Server is Already Running\n");
            }
            break;
        case 'stop':
            if ($is_exist && Swoole\Process::kill($pid)) {
                exit("Stopped Swoole Server\n");
            } elseif (!$is_exist) {
                exit("Swoole Server has Stopped\n");
            } else {
                exit("Stopping Swoole Server Error\n");
            }
            break;
        case 'reload':
            if ($is_exist && $ret = Swoole\Process::kill($pid, SIGUSR1)) {
                exit("Reloaded Swoole Server\n");
            } elseif (!isset($ret) || !$ret) {
                exit("Reloading Swoole Server Error\n");
            }
            break;
    }
} elseif ($oper == 'stop') {
    exit("Swoole Server is not Running\n");
}

define('SWOOLE_MODE', true);

$serv = new Swoole\Http\Server('0.0.0.0', 9501);

$settings = array();

$serv->on('WorkerStart', function () use (&$settings) {
    require __DIR__ . '/../vendor/autoload.php';
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $env = __DIR__ . '/../.env';
    if (file_exists($env)) {
        $dotenv = new Dotenv\Dotenv(dirname($env));
        $dotenv->load();
    }
    $settings = require __DIR__ . '/../src/settings.php';
});

$serv->on('request', function ($request, $response) use ($settings) {
    // 设置$_SERVER，使slim能够读取到其中的值
    foreach ($request->server as $key => $val) {
        // 如果不指定以下代码，$_SERVER中无REQUEST_METHOD等项，导致method not allow
        $_SERVER[strtoupper($key)] = $val;
    };
    foreach ($request->header as $key => $val) {
        $_SERVER[str_replace('-', '_', strtoupper($key))] = $val;
    };

    // 设置$_GET、$_POST、$_COOKIE、$_FILES
    $_GET = $request->get;
    $_POST = $request->post;
    $_COOKIE = $request->cookie;
    $_FILES = $request->files;

    $app = new \Slim\App($settings);
    require __DIR__ . '/../src/dependencies.php';
    require __DIR__ . '/../src/middleware.php';
    require __DIR__ . '/../src/routes.php';
    $slimResponse = $app->run(true);
    $response->end($slimResponse->getBody());
});

$serv->set(array(
    'daemonize' =>  1,
    'pid_file'  =>  $pid_file,
));

$serv->start();
