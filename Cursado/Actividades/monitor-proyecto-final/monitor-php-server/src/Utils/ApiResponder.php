<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface as IResponse;
use App\Utils\Result;

class ApiResponder
{
	public static function Respond(IResponse $response, Result $result): IResponse
	{
		if ($result->code >= 300 && $result->code < 400 && $result->redirectTo) {
			return $response
				->withHeader("Location", $result->redirectTo)
				->withStatus($result->code);
		}

		$payload = [
			"success" => $result->isSuccess,
			"message" => $result->message,
			"data" => $result->data,
		];

		$body = $response->getBody();
		$body->write(json_encode($payload));

		return $response
			->withBody($body)
			->withStatus($result->code)
			->withHeader("Content-Type", "application/json");
	}
}
