<?php

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\RolesRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;

class RolesController
{
	private RolesRepository $rolesRepository;

	public function __construct(RolesRepository $rolesRepository)
	{
		$this->rolesRepository = $rolesRepository;
	}

	public function getAllRoles(IRequest $request, IResponse $response): IResponse
	{
		try {
			$roles = $this->rolesRepository->getAll();
			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK, ["roles" => $roles]));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}
}
