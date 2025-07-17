<?php

namespace App\Core;

use App\Core\Log;
use Medoo\Medoo;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Core\BotHandlerInterface;

abstract class BaseBotHandler implements BotHandlerInterface
{
    protected ContainerInterface $container;
    protected Medoo $db;
    protected Log $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $container->get('db');
        $this->logger = $container->get('logger');
    }

    // The handle method must be implemented in child classes
    abstract public function handle(Request $request, Response $response, array $args = []): Response;
}
