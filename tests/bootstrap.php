<?php

// Set timezone
date_default_timezone_set('Asia/Shanghai');

// Prevent session cookies
ini_set('session.use_cookies', 0);



// Enable Composer autoloader
/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';

#require dirname(__FILE__) . '/getallheaders.php';

// Register test classes
$autoloader->addPsr4('Application\\Tests\\', __DIR__);
