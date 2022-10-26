<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Services\RoleService;
use App\Services\UserService;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

$roleService = new RoleService($entityManager);
$userService = new UserService($entityManager);

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get(
	'/users',
	function (Request $request, Response $response, $args) use ($userService) {
		$usersList = json_encode($userService->query(1), JSON_PRETTY_PRINT);
		$response->getBody()->write($usersList);
		return $response;
	}
);

$app->get(
	'/users/{userId}',
	function (Request $request, Response $response, $args) use ($userService) {
		$userId = $args['userId'];
		$user = json_encode($userService->getUser($userId), JSON_PRETTY_PRINT);
		$response->getBody()->write($user);
		return $response;
	}
);

$app->run();
