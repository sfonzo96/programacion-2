<?php

namespace App\Middlewares;

use App\Enums\HttpStatusCodes;
use App\Utils\ApiResponder;
use App\Utils\Result;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IHandler;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Slim\Psr7\Response;

class AlreadyLoggedInMiddleware implements IMiddleware
{
	public function process(IRequest $request, IHandler $handler): IResponse
	{
		$authHeader = $request->getHeaderLine('Authorization');
		if ($authHeader) {
			return ApiResponder::Respond(new Response(), Result::Success(HttpStatusCodes::FOUND, "Already logged in", null, "/dashboard"));
		}

		return $handler->handle($request);
	}
}
