<?php
use Slim\Http\Request;
use Slim\Http\Response;
// Routes
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
	$response->getBody()->write("Hello, $args[name]");
	return $response;
});
