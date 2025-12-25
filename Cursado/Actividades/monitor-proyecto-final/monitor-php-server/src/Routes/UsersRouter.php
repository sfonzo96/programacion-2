<?php

namespace App\Routes;

use App\Controllers\UsersController;
use App\Database\Database;
use App\DTOs\Requests\CreateUserRequest;
use App\DTOs\Requests\UpdateUserPasswordRequest;
use App\DTOs\Requests\UpdateUserRoleRequest;
use App\Enums\UsersRoles;
use App\Middlewares\JwtAuthenticationMiddleware;
use App\Middlewares\RequestBodyValidatorMiddleware;
use App\Middlewares\RoleAuthorizationMiddleware;
use App\Repositories\UsersRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class UsersRouter
{
	static function Register(App $app): void
	{
		$responseFactory = $app->getResponseFactory();

		$pdo = Database::getInstance()->getConnection();
		$userController = new UsersController(new UsersRepository($pdo));

		$app->group("/api/users", function (RouteCollectorProxy $group) use ($userController, $responseFactory) {
			$group->post("[/]", [$userController, "createUser"])
				->add(new RequestBodyValidatorMiddleware(CreateUserRequest::class, $responseFactory))
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN]));

			$group->get("[/]", [$userController, "getAllUsers"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN]));

			$group->get("/{userId}", [$userController, "getUserById"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN]));

			$group->patch("/password", [$userController, "updateUserPassword"])
				->add(new RequestBodyValidatorMiddleware(UpdateUserPasswordRequest::class, $responseFactory));

			$group->patch("/role", [$userController, "updateUserRole"])
				->add(new RequestBodyValidatorMiddleware(UpdateUserRoleRequest::class, $responseFactory))
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN]));

			$group->delete("/{userId}", [$userController, "deleteUser"])
				->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN]));
		})->add(new JwtAuthenticationMiddleware());
	}
}
