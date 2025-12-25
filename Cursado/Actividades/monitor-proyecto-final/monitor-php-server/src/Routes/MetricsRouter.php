<?php

namespace App\Routes;

use App\Controllers\MetricsController;
use App\Database\Database;
use App\DTOs\Requests\CreateMetricRecordRequest;
use App\Repositories\MetricsRepository;
use App\Enums\UsersRoles;
use App\Middlewares\JwtAuthenticationMiddleware;
use App\Middlewares\RequestBodyValidatorMiddleware;
use App\Middlewares\RoleAuthorizationMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class MetricsRouter
{
	static function Register(App $app): void
	{
		$responseFactory = $app->getResponseFactory();
		$pdo = Database::getInstance()->getConnection();
		$metricsController = new MetricsController(new MetricsRepository($pdo));

		$app->group("/api/metrics", function (RouteCollectorProxy $group) use ($metricsController, $responseFactory) {
			$group->post("[/]", [$metricsController, "saveMetricRecord"])
				->add(new RequestBodyValidatorMiddleware(CreateMetricRecordRequest::class, $responseFactory))
				->add(new RoleAuthorizationMiddleware([UsersRoles::DAEMON]));

			$group->get("[/]", [$metricsController, "getMetrics"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->get("/{metricId}", [$metricsController, "getMetricById"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));
		})->add(new JwtAuthenticationMiddleware());
	}
}
