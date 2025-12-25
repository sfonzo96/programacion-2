<?php

namespace App\Routes;

use App\Controllers\RolesController;
use App\Database\Database;
use App\Repositories\RolesRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class RolesRouter
{
	static function Register(App $app): void
	{
		$pdo = Database::getInstance()->getConnection();
		$rolesRepository = new RolesRepository($pdo);
		$rolesController = new RolesController($rolesRepository);

		$app->group("/api/roles", function (RouteCollectorProxy $group) use ($rolesController) {
			$group->get("", [$rolesController, "getAllRoles"]);
		});
	}
}
