<?php

namespace App\Routes;

use App\Controllers\AuthController;
use App\Database\Database;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Middlewares\AlreadyLoggedInMiddleware;
use App\Middlewares\BasicAuthenticator;
use App\Middlewares\JwtAuthenticationMiddleware;
use App\Repositories\UsersRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use Error;
use Slim\App;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

class AuthRouter
{
	static function Register(App $app): void
	{

		$pdo = Database::getInstance()->getConnection();
		$usersRepository = new UsersRepository($pdo);
		$authController = new AuthController($usersRepository);

		$app->group("/api/auth", function (RouteCollectorProxy $group) use ($authController, $usersRepository) {
			$group->post("/login", [$authController, "loginUser"])->add(new HttpBasicAuthentication([
				"realm" => "Protected",
				"secure" => false,
				"authenticator" => new BasicAuthenticator($usersRepository),
				"error" => function (IResponse $response, $arguments) {
					// Comment: WWW-Authenticate header makes the browser show a popup asking credentiales, so removed
					return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED))->withoutHeader("WWW-Authenticate");
				},
				"before" => function (IRequest $request, $arguments) {
					return $request->withAttribute("username", $arguments["user"]);
				},
			])); //->add(new AlreadyLoggedInMiddleware()); // Comment: si no existe la ruta a la que redirige termino en un 404, pero el middleware funciona (error unexpected end of json bla bla)

			$group->post("/logout", [$authController, "logoutUser"])->add(new JwtAuthenticationMiddleware());
		});
	}
}
