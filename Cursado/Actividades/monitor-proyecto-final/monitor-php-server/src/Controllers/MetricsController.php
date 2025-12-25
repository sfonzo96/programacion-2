<?php

namespace App\Controllers;

use App\DTOs\Requests\CreateMetricRecordRequest;
use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\MetricsRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Models\Metric;

class MetricsController
{
	private MetricsRepository $metricsRepository;

	public function __construct(MetricsRepository $metricsRepository)
	{
		$this->metricsRepository = $metricsRepository;
	}

	public function getMetricById(IRequest $request, IResponse $response, array $args): IResponse
	{
		try {
			$id = $args["metricId"] ?? null;
			if (!$id || !is_numeric($id)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
			}

			$queryParams = $request->getQueryParams();
			$since = $queryParams['since'] ?? null;

			if (!$since) {
				$metric = $this->metricsRepository->getMetricById($id);
			} else {
				$metric = $this->metricsRepository->getMetricWithRecordsById($id, $since);
			}
			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $metric));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getMetrics(IRequest $request, IResponse $response): IResponse
	{
		try {
			$metrics = $this->metricsRepository->getAll();
			if (count($metrics) < 1) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::NOT_FOUND, ResponseMessages::NO_RECORDS));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $metrics));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function saveMetricRecord(IRequest $request, IResponse $response): IResponse
	{
		try {
			$body = $request->getParsedBody();

			$metric = new CreateMetricRecordRequest($body["metricId"], $body["value"]);
			if (!$this->metricsRepository->create($metric)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::CREATED, ResponseMessages::OK_CREATE, ""));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getThresholdsByMetricId(IRequest $request, IResponse $response): IResponse
	{
		try {
			return $response;
		} catch (Exception $ex) {
			return $response;
		}
	}
}
