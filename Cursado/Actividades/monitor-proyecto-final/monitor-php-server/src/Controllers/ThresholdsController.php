<?php

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\ThresholdRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Models\Threshold;

class ThresholdsController
{
	private ThresholdRepository $thresholdRepository;

	public function __construct(ThresholdRepository $thresholdRepository)
	{
		$this->thresholdRepository = $thresholdRepository;
	}

	public function createThreshold(IRequest $request, IResponse $response): IResponse
	{
		try {
			return $response;
		} catch (Exception $ex) {
			return $response;
		}
	}

	public function updateThreshold(IRequest $request, IResponse $response): IResponse
	{
		try {
			return $response;
		} catch (Exception $ex) {
			return $response;
		}
	}

	public function deleteThreshold(IRequest $request, IResponse $response): IResponse
	{
		try {
			return $response;
		} catch (Exception $ex) {
			return $response;
		}
	}
}
