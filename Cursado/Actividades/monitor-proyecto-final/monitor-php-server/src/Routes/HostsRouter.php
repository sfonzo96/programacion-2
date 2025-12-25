<?php

namespace App\Routes;

use App\Controllers\HostsController;
use App\Database\Database;
use App\DTOs\Requests\CreateHostRequest;
use App\Enums\UsersRoles;
use App\Middlewares\JwtAuthenticationMiddleware;
use App\Middlewares\RequestBodyValidatorMiddleware;
use App\Middlewares\RoleAuthorizationMiddleware;
use App\Repositories\HostsRepository;
use App\Repositories\SSERepository;
use App\Services\SSEService;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class HostsRouter
{
	static function Register(App $app): void
	{
		$responseFactory = $app->getResponseFactory();
		$pdo = Database::getInstance()->getConnection();
		$hostsController = new HostsController(new HostsRepository($pdo), new SSEService(new SSERepository($pdo)));

		$app->group("/api/hosts", function (RouteCollectorProxy $group) use ($hostsController, $responseFactory) {
			$group->post("[/]", [$hostsController, "createHost"])
				->add(new RequestBodyValidatorMiddleware(CreateHostRequest::class, $responseFactory))
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN, UsersRoles::DAEMON]));

			$group->get("[/]", [$hostsController, "getAllHosts"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->get("/{hostId}", [$hostsController, "getHostById"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->get("/network/{networkId}", [$hostsController, "getHostsByNetworkId"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->get("/{hostId}/portScan", [$hostsController, "scanHostPorts"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));

			$group->get("/{hostId}/bannerGrab", [$hostsController, "grabHostBanners"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ALL]));
		})->add(new JwtAuthenticationMiddleware());
	}
}
