<?php

namespace App\Controllers;

use App\DTOs\Requests\CreateHostRequest;
use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\HostsRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Models\SSEEvent;
use App\Services\SSEService;

class HostsController
{
	private HostsRepository $hostRepository;
	private SSEService $sseService;

	public function __construct(HostsRepository $hostRepository, SSEService $sseService)
	{
		$this->hostRepository = $hostRepository;
		$this->sseService = $sseService;
	}

	public function createHost(IRequest $request, IResponse $response): IResponse
	{
		try {
			$body = $request->getParsedBody();

			$hostExists = $this->hostRepository->getByMac($body["macAddress"]);
			if ($hostExists) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::FOUND, "Host already exists."));
			}

			$host = new CreateHostRequest($body["ipAddress"], $body["macAddress"], $body["networkId"], $body["hostname"]);
			if (!$this->hostRepository->create($host)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			$this->sseService->enqueueEvent(new SSEEvent('notification', json_encode([
				"message" => "New host added: {$body['hostname']} ({$body['ipAddress']})",
				"level" => "info",
			])));

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::CREATED, ResponseMessages::OK_CREATE, ""));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getAllHosts(IRequest $request, IResponse $response): IResponse
	{
		try {
			$hosts = $this->hostRepository->getAll();
			if (count($hosts) < 1) {
				return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::NO_RECORDS, []));
			}
			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $hosts));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getHostById(IRequest $request, IResponse $response, array $args): IResponse
	{
		try {
			$id = $args["hostId"] ?? null;
			if (!$id || !is_numeric($id)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
			}

			$host = $this->hostRepository->getById($id);
			if (!$host) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::NOT_FOUND, ResponseMessages::NOT_FOUND));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $host));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getHostsByNetworkId(IRequest $request, IResponse $response, array $args): IResponse
	{
		try {
			$id = $args["networkId"] ?? null;
			if (!$id || !is_numeric($id)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
			}

			$hosts = $this->hostRepository->getHostsByNetworkId($id);
			if (count($hosts) < 1) {
				return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::NO_RECORDS, []));
			}
			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $hosts));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}
}
