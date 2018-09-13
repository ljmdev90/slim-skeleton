<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$env = __DIR__ . '/../.env';
if (file_exists($env)) {
    $dotenv = new Dotenv\Dotenv(dirname($env));
    $dotenv->load();
}

$settings = require __DIR__ . '/settings.php';
$app = new \Slim\App($settings);

require __DIR__ . '/dependencies.php';

require __DIR__ . '/middleware.php';

require __DIR__ . '/routes.php';

$app->run();
