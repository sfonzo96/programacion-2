<?php

namespace App\Controllers;

use App\DTOs\Requests\CreateUserRequest;
use App\DTOs\Requests\UpdateUserPasswordRequest;
use App\DTOs\Requests\UpdateUserRoleRequest;
use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\UsersRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Models\User;

// Comment: BUILD SERVICE AND ADAPT CONTROLLER TO USE IT


class UsersController
{
	private UsersRepository $userRepository;

	public function __construct(UsersRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function getAllUsers(IRequest $request, IResponse $response): IResponse
	{
		try {
			$users = $this->userRepository->getAll();
			if (count($users) < 1) {
				return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::NO_RECORDS, []));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $users));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function getUserById(IRequest $request, IResponse $response, array $args): IResponse
	{
		$id = $args["userId"] ?? null;
		if (!$id || !is_numeric($id)) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
		}

		try {
			$user = $this->userRepository->getById($id);
			if (!$user) {
				return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::NOT_FOUND, null));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_GET, $user));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function createUser(IRequest $request, IResponse $response): IResponse
	{
		try {
			$body = $request->getParsedBody();
			if ($body["password"] != $body["confirmPassword"]) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Passwords do not match."));
			}

			$userExists = $this->userRepository->getByUsername($body["username"]);
			if ($userExists) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::FOUND, "Username already in use."));
			}

			$user = new CreateUserRequest($body["firstName"], $body["lastName"], $body["username"], $body["password"]);
			if (!$this->userRepository->create($user)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::CREATED, ResponseMessages::OK_CREATE, ""));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function updateUserPassword(IRequest $request, IResponse $response): IResponse
	{
		$body = $request->getParsedBody();
		if ($body["password"] != $body["confirmPassword"]) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Passwords do not match."));
		}

		$jwtPayload = $request->getAttribute("jwtPayload");
		$existingUser = $this->userRepository->getByUsername($jwtPayload->data->username);
		if ($existingUser && $existingUser["id"] != $body["userId"]) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::UNAUTHORIZED));
		}

		$user = new UpdateUserPasswordRequest($body["userId"], $body["currentPassword"], $body["newPassword"]);

		try {
			if (!$this->userRepository->updateUserPassword($user)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_UPDATE));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function updateUserRole(IRequest $request, IResponse $response): IResponse
	{
		$body = $request->getParsedBody();

		$user = new UpdateUserRoleRequest($body["userId"], $body["roleId"]);

		try {
			if (!$this->userRepository->updateUserRole($user)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_UPDATE));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function deleteUser(IRequest $request, IResponse $response, array $args): IResponse
	{
		$id = $args["id"] ?? null;
		if (!$id || !is_numeric($id)) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST));
		}

		try {
			if (!$this->userRepository->disable((int)$id)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::FAIL));
			}

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, ResponseMessages::OK_DELETE, ""));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}
}
