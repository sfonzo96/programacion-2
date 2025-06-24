<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DAO\UserDAO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Database\Database;
use Models\User;
use Utils\ApiResponder;
use Utils\HttpStatusCodes;
use Utils\Result;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

function parseBoolFromString(string $bString): bool
{
	return $bString == "true" ? true : false;
};

$app->get('/api/person', function (Request $req, Response $res, $args) {
	$userDAO = new UserDAO(Database::getPDOInstance());
	$users = $userDAO->getAllUsers();

	if (count($users) < 1) {
		return ApiResponder::Respond($res, Result::Success(HttpStatusCodes::OK, "There're no users so far...", []));
	}

	return ApiResponder::Respond($res, Result::Success(HttpStatusCodes::OK, "Users list", $users));
});

$app->post('/api/person', function (Request $req, Response $res, $args) {
	$body = $req->getParsedBody();
	if (!isset($body["status"]) || !isset($body["email"])) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Invalid data structure"));
	};
	// Comment: parseBoolFromString only if comming from postman's mocks!
	$user = new User(parseBoolFromString($body["status"]), $body["email"]);

	$userDAO = new UserDAO(Database::getPDOInstance());
	$success = $userDAO->createUser($user);
	if (!$success) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, "Error creating user with email {$user->email}"));
	}

	return ApiResponder::Respond($res, Result::Success(HttpStatusCodes::CREATED, "User created successfully", $user));
});

$app->delete('/api/person', function (Request $req, Response $res, $args) {
	$queryParams = $req->getQueryParams();
	if (!isset($queryParams["id"])) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Missing query param: id"));
	};

	$id = $queryParams["id"];
	if (!is_numeric($id) || !is_integer(intval($id))) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Invalid value for query param: id"));
	}

	$userDAO = new UserDAO(Database::getPDOInstance());
	$success = $userDAO->deleteUser(intval($id));
	if (!$success) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, "Error deleting user with id: {$id}"));
	}

	return ApiResponder::Respond($res, Result::Success(HttpStatusCodes::CREATED, "User with id: {$id} deleted successfully"));
});

$app->put('/api/person', function (Request $req, Response $res, $args) {
	$body = $req->getParsedBody();
	if (!isset($body["status"]) || !isset($body["email"]) || !isset($body["id"])) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Invalid data structure"));
	};

	$id = $body["id"];
	if (!is_numeric($id) || !is_integer(intval($id))) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::BAD_REQUEST, "Invalid value field: id"));
	}

	$user = new User(($body["status"]), $body["email"]);

	$userDAO = new UserDAO(Database::getPDOInstance());
	$success = $userDAO->updateUser($user, $id);
	if (!$success) {
		return ApiResponder::Respond($res, Result::Failure(HttpStatusCodes::INTERNAL_SERVER_ERROR, "Error updating user with id: {$id}"));
	}

	return ApiResponder::Respond($res, Result::Success(HttpStatusCodes::CREATED, "User  th id: {$id} updated successfully", $user));
});


$app->setBasePath('/2025_05_30-Slim/public');
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();
