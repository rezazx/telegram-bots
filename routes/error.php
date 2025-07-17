<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response as SlimResponse;

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    try {
        return $handler->handle($request);
    } catch (\Slim\Exception\HttpMethodNotAllowedException $e) {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Method Not Allowed. Please use the correct method.'
        ]));
        return $response->withStatus(405)->withHeader('Content-Type', 'application/json');
    }
});
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    try {
        return $handler->handle($request);
    } catch (HttpNotFoundException $e) {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Route Not Found. Please check your request URL.'
        ]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
});