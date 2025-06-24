<?php

namespace Utils;

use Psr\Http\Message\ResponseInterface as Response;
use Utils\Result;

class ApiResponder
{
	public static function Respond(Response $response, Result $result): Response
	{
		$payload = [
			'success' => $result->isSuccess,
			'message' => $result->message,
			'data' => $result->data,
		];

		$body = $response->getBody();
		$body->write(json_encode($payload));

		return $response
			->withBody($body)
			->withStatus($result->code)
			->withHeader('Content-Type', 'application/json');
	}
}
