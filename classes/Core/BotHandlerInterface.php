<?php

namespace App\Core;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

interface BotHandlerInterface
{
    /**
     * Handle the request and return the response
     *
     * @param Request $request
     * @param Response $response
     * @param array $args Routing parameters (e.g., route params)
     * @return Response
     */
    public function handle(Request $request, Response $response, array $args = []): Response;
}
