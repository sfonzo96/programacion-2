<?php

namespace App\Middlewares;

use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Utils\ApiResponder;
use App\Utils\Result;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IHandler;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Slim\Psr7\Response;

class JwtAuthenticationMiddleware implements IMiddleware
{
	public function process(IRequest $request, IHandler $handler): IResponse
	{
		$authHeader = $request->getHeaderLine('Authorization');
		if (!$authHeader) {
			return ApiResponder::Respond(new Response(), Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED));
		}

		$token = str_replace('Bearer ', '', $authHeader);
		try {
			$decoded = JWT::decode($token, new Key($_ENV["JWT_SECRET"], 'HS256'));
			$request = $request->withAttribute("jwtPayload", $decoded);
		} catch (Exception $ex) {
			$tokenErrorMessage = match ($ex::class) {
				SignatureInvalidException::class => "Invalid signature.",
				ExpiredException::class => "Token expired.",
				BeforeValidException::class => "Token is not valid yet.",
				default => "Token error."
			};

			return ApiResponder::Respond(new Response(), Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED, ["error" => $tokenErrorMessage], $ex->getMessage()));
		}

		return $handler->handle($request);
	}
}
