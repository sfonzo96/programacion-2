<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use App\Database\Database;
use App\Routes\AuthRouter;
use App\Routes\HostsRouter;
use App\Routes\NetworksRouter;
use App\Routes\RolesRouter;
use App\Routes\UsersRouter;
use App\Routes\SystemRouter;
use App\Routes\MetricsRouter;
use App\Routes\SSERouter;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use App\Middlewares\CorsMiddleware;

try {
	$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
	$dotenv->load();

	$dotenv->required(["DB_DSN", "DB_USER", "DB_PASS", "JWT_SECRET"])->notEmpty();
} catch (Exception $ex) {
	error_log("[.env] loading went wrong: {$ex->getMessage()} (Code: {$ex->getCode()})");
	exit("Environment loading failed.\n");
}

$app = AppFactory::create();

// $app->add(new CorsMiddleware());

$app->addBodyParsingMiddleware();

$db = Database::getInstance($_ENV["DB_DSN"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

UsersRouter::Register($app);
AuthRouter::Register($app);
SystemRouter::Register($app);
RolesRouter::Register($app);
NetworksRouter::Register($app);
HostsRouter::Register($app);
MetricsRouter::Register($app);
SSERouter::Register($app);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();
