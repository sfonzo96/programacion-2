<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/hello', function (Request $req, Response $res, $args) {
	$res->getBody()->write("Holaaaaaaaaaaaaaaaa ");
	return $res;
});

$app->setBasePath('/2025_05_30-Slim');

$app->run();

