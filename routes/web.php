<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
