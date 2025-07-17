<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

// use App\Middleware\AdminMiddleware;
// use App\Middleware\AuthMiddleware;

$container = $app->getContainer(); // Get container from App
// $authMiddleware = new AuthMiddleware(
//     $container->get(ResponseFactoryInterface::class)
// );

// $adminMiddleware = new AdminMiddleware(
//     $container->get(ResponseFactoryInterface::class),
//     $container->get('db')
// );

$app->get('/api/test', function (Request $request, Response $response, $args) {
    $response->getBody()->write("zx telegram bot api is work!");
    return $response;
});