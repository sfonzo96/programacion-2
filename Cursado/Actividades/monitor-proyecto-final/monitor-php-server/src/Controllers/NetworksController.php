<?php

namespace App\Controllers;

use App\DTOs\Requests\CreateNetworkRequest;
use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\NetworksRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;

class NetworksController
{
	private NetworksRepository $networksRepository;

	public function __construct(NetworksRepository $networksRepository)
	{
		$this->networksRepository = $networksRepository;
	}

	public function createNetwork(IRequest $request, IResponse $response): IResponse
	{
		try {
			$body = $request->getParsedBody();

			$networkExists = $this->networksRepository->getbyIpAndMask($body["ipAddress"], $body["CIDRMask"]);
			if ($networkExists) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::FOUND, "Network already exists."));
			}

			$network = new CreateNetworkRequest($body["ipAddress"], $body["CIDRMask"]);
			if (!$this->networksRepository->create($network)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::CREATED, ResponseMessages::OK_CREATE, ""));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getAllNetworks(IRequest $request, IResponse $response, array $args): IResponse
	{
		try {
			$networks = $this->networksRepository->getAll();
			if (count($networks) < 1) {
				return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::NO_RECORDS, []));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $networks));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getNetworkById(IRequest $request, IResponse $response, array $args): IResponse
	{
		$id = $args["networkId"] ?? null;
		if (!$id || !is_numeric($id)) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
		}

		try {
			$network = $this->networksRepository->getById($id);
			if (!$network) {
				return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::NOT_FOUND, null));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $network));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function deleteNetwork(IRequest $request, IResponse $response, array $args): IResponse
	{
		try {
			$id = $args["networkId"] ?? null;
			if (!$id || !is_numeric($id)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
			}

			$success = $this->networksRepository->deleteById($id);
			if (!$success) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::NOT_FOUND, ResponseMessages::NOT_FOUND));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_DELETE, ""));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}
}
