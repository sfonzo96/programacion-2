<?php

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Services\SystemService;

class SystemController
{
	public function getInfo(IRequest $request, IResponse $response): IResponse
	{
		try {
			$sysinfo = SystemService::getSystemInfo();
			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK, $sysinfo));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getProcesses(IRequest $request, IResponse $response): IResponse
	{
		try {
			$processesSnapshot = SystemService::snapshotProcesses();
			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK, ["processes" => $processesSnapshot]));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getDisks(IRequest $request, IResponse $response): IResponse
	{
		try {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::NOT_IMPLEMENTED, ResponseMessages::NOT_IMPLEMENTED));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getLogs(IRequest $request, IResponse $response): IResponse
	{
		try {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::NOT_IMPLEMENTED, ResponseMessages::NOT_IMPLEMENTED));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}
}
