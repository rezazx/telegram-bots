<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/bootstrap.php';

// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header('X-Powered-By: MRZX.ir');
// header('Access-Control-Allow-Headers:Content-Type,Authorization,X-Requested-With,Accept,Origin');
// Create Slim app
$app = AppFactory::create();
// $app->add(new App\Middleware\JsonToFormMiddleware());

// Add Middleware for CORS
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $response = $handler->handle($request);

    // Add CORS headers
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('X-Powered-By', 'MRZX.ir')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
});



$settings = $app->getContainer()->get('settings');

$app->setBasePath($settings['baseUrl']);


foreach (glob(__DIR__ . '/bots/*/init.php') as $file) {
    require_once $file; // All bots register themselves
}

require __DIR__ .'/routes/api.php';
require __DIR__ .'/routes/web.php';
require __DIR__ .'/routes/bot.php';
require __DIR__ .'/routes/error.php';

// Automatically handle OPTIONS requests
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response->withStatus(200);
});

// Run app
$app->run();