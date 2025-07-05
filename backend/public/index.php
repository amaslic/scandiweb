<?php
declare(strict_types=1);

// 1) Load Composer’s PSR-4 autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// 2) Bootstrap environment & DB
require_once __DIR__ . '/../bootstrap.php';

use FastRoute\RouteCollector;
// 3) Import your GraphQL controller so App\Controller\GraphQL::class resolves
use App\Controller\GraphQL;

$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->post('/graphql', [GraphQL::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.1 404 Not Found");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header("HTTP/1.1 405 Method Not Allowed");
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars    = $routeInfo[2];
        echo $handler($vars);
        break;
}
