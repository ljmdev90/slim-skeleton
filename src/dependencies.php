<?php
	$container = $app->getContainer();

	// database
	$container['db'] = function ($c) {
		$settings = $c->get('settings')['db'];
		return new Medoo\Medoo($settings);
	};

	// view 如果routes里重新定义了，这里的值会被覆盖
	$container['view'] = function($c) {
		return new \Slim\Views\PhpRenderer('./templates/');
	};