<?php

namespace App\Routes;

use App\Controllers\SystemController;
use App\Middlewares\JwtAuthenticationMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class SystemRouter
{
	static function Register(App $app): void
	{
		$systemController = new SystemController();
		$app->group("/api/system", function (RouteCollectorProxy $group) use ($systemController) {
			$group->get("/info", [$systemController, "getInfo"]); // OK

			$group->get("/processes", [$systemController, "getProcesses"]); // OK

			$group->get("/disks", [$systemController, "getDisks"]);

			$group->get("/logs", [$systemController, "getLogs"]);
		})->add(new JwtAuthenticationMiddleware());
	}
}
