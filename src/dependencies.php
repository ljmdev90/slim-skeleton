<?php
	$container = $app->getContainer();

	// database
	$container['db'] = function ($c) {
		$settings = $c->get('settings')['db'];
		return new Medoo\Medoo($settings);
	};