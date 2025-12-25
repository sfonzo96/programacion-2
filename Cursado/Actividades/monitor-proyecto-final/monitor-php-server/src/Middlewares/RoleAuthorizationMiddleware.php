<?php

namespace App\Middlewares;

use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Enums\UsersRoles;
use App\Utils\ApiResponder;
use App\Utils\Result;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Psr\Http\Server\RequestHandlerInterface as IHandler;
use Psr\Http\Message\ResponseInterface as IResponse;
use Slim\Psr7\Response;

class RoleAuthorizationMiddleware implements IMiddleware
{
	private array $allowedRoles;

	public function __construct(array $allowedRoles)
	{
		$this->allowedRoles = $allowedRoles;
	}

	public function process(IRequest $request, IHandler $handler): IResponse
	{
		$jwtPayload = $request->getAttribute("jwtPayload");

		if (!$jwtPayload || !isset($jwtPayload->data->roleId)) {
			return ApiResponder::Respond(
				new Response(),
				Result::Failure(HttpStatusCodes::FORBIDDEN, ResponseMessages::FORBIDDEN)
			);
		}

		if ($this->allowedRoles === [UsersRoles::ALL]) {
			return $handler->handle($request);
		}

		$roleId = (int) $jwtPayload->data->roleId;
		$userRole = UsersRoles::tryFrom($roleId);

		if (!$userRole || !in_array($userRole, $this->allowedRoles, true)) {
			return ApiResponder::Respond(
				new Response(),
				Result::Failure(HttpStatusCodes::FORBIDDEN, ResponseMessages::FORBIDDEN)
			);
		}

		return $handler->handle($request);
	}
}
