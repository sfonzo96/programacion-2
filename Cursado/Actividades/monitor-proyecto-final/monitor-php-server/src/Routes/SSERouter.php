<?php

namespace App\Routes;

use App\Controllers\SSEController;
use App\Database\Database;
use App\Repositories\SSERepository;
use App\Services\SSEService;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class SSERouter
{
	static function Register(App $app): void
	{
		$pdo = Database::getInstance()->getConnection();
		$sseController = new SSEController(new SSEService(new SSERepository($pdo)));

		$app->group("/api/sse", function (RouteCollectorProxy $group) use ($sseController) {
			$group->get("/stream", [$sseController, "stream"]);
		});
	}
}
