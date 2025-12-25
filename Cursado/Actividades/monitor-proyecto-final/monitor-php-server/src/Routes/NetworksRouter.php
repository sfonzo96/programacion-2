<?php

namespace App\Routes;

use App\Controllers\NetworksController;
use App\Database\Database;
use App\DTOs\Requests\CreateNetworkRequest;
use App\Enums\UsersRoles;
use App\Middlewares\JwtAuthenticationMiddleware;
use App\Middlewares\RequestBodyValidatorMiddleware;
use App\Middlewares\RoleAuthorizationMiddleware;
use App\Repositories\NetworksRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class NetworksRouter
{
	static function Register(App $app): void
	{
		$responseFactory = $app->getResponseFactory();
		$pdo = Database::getInstance()->getConnection();
		$networksController = new NetworksController(new NetworksRepository($pdo));

		$app->group("/api/networks", function (RouteCollectorProxy $group) use ($networksController, $responseFactory) {
			$group->post("[/]", [$networksController, "createNetwork"])
				->add(new RequestBodyValidatorMiddleware(CreateNetworkRequest::class, $responseFactory))
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN, UsersRoles::DAEMON]));

			$group->get("[/]", [$networksController, "getAllNetworks"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->get("/{networkId}", [$networksController, "getNetworkById"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->delete("/{networkId}", [$networksController, "deleteNetwork"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN, UsersRoles::MANAGER]));
		})->add(new JwtAuthenticationMiddleware());
	}
}
