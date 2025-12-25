<?php

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use App\Repositories\UsersRepository;
use App\Utils\ApiResponder;
use App\Utils\Result;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Utils\JWTUtils;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

class AuthController
{
	private UsersRepository $userRepository;

	public function __construct(UsersRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function loginUser(IRequest $request, IResponse $response): IResponse
	{
		$username = $request->getAttribute("username");
		try {
			$user = $this->userRepository->getByUsername($username);
			if ($user->isEnabled == false) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, "Account disabled."));
			}

			$accessTokenPayload = [
				"iss" => "programacion.local", // Issuer
				"aud" => "programacion.local", // Audience
				"iat" => time(), // Issued at
				"nbf" => time(), // Not before
				"exp" => time() + 15 * 60, // Expiration Time (15 min)
				"data" => [
					"username" => $username,
					"userId" => $user->id,
					"role" => $user->role->name,
					"roleId" => $user->role->id
				]
			];
			$accessToken = JWTUtils::encode($accessTokenPayload);

			$refreshTokenPayload = [
				"iss" => "programacion.local",
				"aud" => "programacion.local",
				"iat" => time(),
				"nbf" => time(),
				"exp" => time() + 604800, // Expiration Time (7 days)
				"data" => [
					"userId" => $user->id,
				]
			];
			// Comment: save live refresh tokens, logout removes them
			$refreshToken = JWTUtils::encode($refreshTokenPayload);
			JWTUtils::saveToken($username, $refreshToken);
			setcookie("refreshToken", $refreshToken, [
				'expires' => time() + 604800,
				'path' => '/',
				'secure' => false, // Comment: true if https
				'httponly' => true,
				'samesite' => 'Lax'
			]);


			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, "Logged in", ["accessToken" => $accessToken]));
		} catch (Exception $ex) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}

	public function refreshAccessToken(IRequest $request, IResponse $response): IResponse
	{
		$refreshToken = $_COOKIE["refreshToken"] ?? null;
		if (!$refreshToken) {
			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, "Missing refresh token."));
		}

		try {
			$decoded = JWTUtils::decode($refreshToken);

			$savedToken = JWTUtils::getUserSavedToken($decoded->data->username);
			if (!$savedToken || JWTUtils::tokenIsExpired($decoded->data->exp)) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, "Refresh token expired."));
			}

			$user = $this->userRepository->getByUsername($decoded->data->username);

			$accessTokenPayload = [
				"iss" => "programacion.local",
				"aud" => "programacion.local",
				"iat" => time(),
				"nbf" => time(),
				"exp" => time() + 900,
				"data" => [
					"username" => $user->username,
					"role" => $user->role->name,
					"roleId" => $user->role->id
				]
			];

			$accessToken = JWTUtils::encode($accessTokenPayload);

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, "Token refreshed", ["accessToken" => $accessToken]));
		} catch (Exception $ex) {
			if ($ex instanceof UnexpectedValueException) {
				$tokenErrorMessage = match ($ex::class) {
					SignatureInvalidException::class => "Invalid signature.",
					ExpiredException::class => "Token expired.",
					BeforeValidException::class => "Token is not valid yet.",
					default => "Token error."
				};

				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED, ["error" => $tokenErrorMessage], $ex->getMessage()));
			}

			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR,  null, $ex->getMessage()));
		}
	}

	public function logoutUser(IRequest $request, IResponse $response): IResponse
	{
		try {
			$refreshToken = $_COOKIE["refreshToken"] ?? null;

			if (!$refreshToken) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, "Missing refresh token."));
			}

			$decoded = JWTUtils::decode($refreshToken);
			$savedToken = JWTUtils::getUserSavedToken($decoded->data->userId);
			if (!$savedToken) {
				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, "Invalid refresh token."));
			}

			JWTUtils::removeToken($decoded->data->userId);

			setcookie("refreshToken");

			return ApiResponder::Respond($response, Result::Success(HttpStatusCodes::OK, "Logged out successfully."));
		} catch (Exception $ex) {
			if ($ex instanceof UnexpectedValueException) {
				$tokenErrorMessage = match ($ex::class) {
					SignatureInvalidException::class => "Invalid signature.",
					ExpiredException::class => "Token expired.",
					BeforeValidException::class => "Token is not valid yet.",
					default => "Token error."
				};

				return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::UNAUTHORIZED, ResponseMessages::UNAUTHORIZED, ["error" => $tokenErrorMessage], $ex->getMessage()));
			}

			return ApiResponder::Respond($response, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages::INTERNAL_SERVER_ERROR, null, $ex->getMessage()));
		}
	}
}
