<?php

namespace App\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Services\SSEService;
use App\Utils\ApiResponder;
use App\Utils\JWTUtils;
use App\Utils\Result;
use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;

class SSEController
{
	private SSEService $sseService;

	public function __construct(SSEService $sseService)
	{
		$this->sseService = $sseService;
	}
	public function stream(IRequest $request, IResponse $response): IResponse
	{
		$token = $request->getQueryParams()['token'] ?? '';
		if (!$token) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED));
		}

		try {
			$jwtPayload = JWTUtils::decode($token);
			$userId = $jwtPayload->data->userId ?? null;
			if (!$userId) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED));
			}
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}

		// Comment: Necessary SSE headers
		$response = $response
			->withHeader('Content-Type', 'text/event-stream')
			->withHeader('Cache-Control', 'no-cache')
			->withHeader('Connection', 'keep-alive')
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'Cache-Control');

		// Comment: Empties output buffer (ob)
		if (ob_get_level()) {
			ob_end_clean();
		}

		// Comment: Send headers as raw, since Slim doesnt return until end of execution
		foreach ($response->getHeaders() as $name => $values) {
			header($name . ': ' . implode(', ', $values));
		}

		// Comment: Removes time limit for execution so connection can stay open
		set_time_limit(0);
		// Comment: Allows execution to end if the client disconnects
		ignore_user_abort(false);

		// Comment: First event to confirm connection
		$this->sseService->sendEvent('connected', json_encode([
			'message' => 'SSE connection established',
			'userId' => $userId,
			'timestamp' => date('Y-m-d H:i:s')
		]));

		// Comment: insert ignored if exists
		$this->sseService->addUserState($userId);

		// Comment: Keep connection alive with periodic heartbeat

		$connectionStart = time();
		$maxConnectionTime = 60 * 5; // Comment: I guess a refresh every 5 minutes is reasonable
		$lastHeartbeat = time();
		while (connection_status() === CONNECTION_NORMAL && !connection_aborted()) {
			$currentTime = time();

			// Comment: timeout (sometimes connections hang indefinitely, programming in php is misserable)
			if ($currentTime - $connectionStart > $maxConnectionTime) {
				error_log("SSE Connection timeout for user $userId");
				break;
			}

			if ($currentTime - $lastHeartbeat >= 30) {
				$this->sseService->sendHeartbeat();
				$lastHeartbeat = $currentTime;
			}

			// Comment: Check for new events to send
			$pendingEvents = $this->sseService->getPendingEvents($userId);
			foreach ($pendingEvents as $event) {
				$this->sseService->sendEvent($event->motive, $event->data, $event->id);
				$lastEventId = $event->id;
				$this->sseService->updateUserState($userId, $lastEventId);
			}

			// Comment: Small sleep to prevent high CPU usage
			sleep(1);
		}

		return $response;
	}
}
