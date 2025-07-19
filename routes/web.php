<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\BotRegistry;
use MRZX\Tools;

// Define a basic route
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("ZxTelegram bots manager platform!");
    return $response;
});


// Define a basic route
$app->get('/install', function (Request $request, Response $response, $args) {

    try {
        // $db = $this->get('db');
        // $m=new \App\Models\User($db);
        // $m->install();
    
        // $m=new \App\Models\SmsProvider($db);
        // $m->install();
    
        // $m=new \App\Models\SmsQueue($db);
        // $m->install();
    
        // $m=new \App\Models\SmsQueueProcessor($db);
        // $m->install();

        $response->getBody()->write("Install successful!");
    } catch ( \Exception $e) {
        //throw $th;
        $response->getBody()->write('Erron on install: '.$e->getMessage());
    }



    // $response->getBody()->write(print_r($db->info(),true));
    // $response->getBody()->write("Install successful!");

    return $response;
});

$app->get('/setwebhook/{bot}', function (Request $request, Response $response, array $args) {
    $botName = preg_replace('/[^a-zA-Z0-9_\-]/', '', $args['bot'] ?? '');
    $queryParams = $request->getQueryParams();
    $adminKey = $queryParams['key'] ?? '';


    if ($adminKey !== $_ENV['ADMIN_KEY']) {
        $response->getBody()->write('Access denied');
        return $response->withStatus(403);
    }

    $bot = null;
    foreach (BotRegistry::all() as $b) {
    if ($b['name'] === $botName) {
        $bot = $b;
        break;
        }
    }

    if (!$bot) {
        $response->getBody()->write('Bot not found');
        return $response->withStatus(403);
    }

    
    if (!isset($bot['config']['token'])) {
        $response->getBody()->write('wrong token!');
        return $response->withStatus(404);
    }
    $webhookUrl = $_ENV['APP_URL'] .$_ENV['BASE_URL'] . '/bot' . $bot['webhook'];
    $set = BotRegistry::setWebhookUrl($webhookUrl, $bot['config']['token']);

    $response->getBody()->write(json_encode($set, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});