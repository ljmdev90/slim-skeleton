<?php

if (!defined('SWOOLE_MODE') || !SWOOLE_MODE) {
    $app->add(Application\Cores\Middlewares\Command::class);
}
