<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Core\BotHandlerInterface;
use App\Core\BotRegistry;

use App\Core\Config;
use App\Core\Log;

$container = $app->getContainer(); // Get container from App


$app->group('/bot', function ($group) {
    foreach (BotRegistry::all() as $botDef) {
        $group->any($botDef['webhook'] . '[/{params:.*}]', function (Request $req, Response $res, $args) use ($botDef) {
            $handlerClass = $botDef['handler'];// Instead of the bot class, we get the handler
            /** @var BotHandlerInterface $handler */
            $logger=$this->get('logger');
            $logger->info('route hablder');
            $container = $this;
            $handler = new $handlerClass($container);
            return $handler->handle($req, $res, $args);
        });
    }
});
