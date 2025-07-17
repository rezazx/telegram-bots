<?php

use Medoo\Medoo;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\ResponseFactoryInterface;

// load '.env' file
$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

// for use dependency.
$container = new Container();

// Add Medoo  as 'db' 
$container->set('db', function () {
    return new Medoo([
        'database_type' => 'mysql',
        'database_name' => $_ENV['DB_NAME'],
        'server'        => $_ENV['DB_HOST'],
        'port'          => $_ENV['DB_PORT'],
        'username'      => $_ENV['DB_USER'],
        'password'      => $_ENV['DB_PASS'],
        'charset'       => 'utf8mb4',
        // 'error'         => \PDO::ERRMODE_EXCEPTION, // Error Handling
    ]);
});

// General logger for the project
$container->set('logger', function () {
    return new \App\Core\Log('app','app.log');
});

$container->set('LogFactory', function () {
    return function (string $channel, string $filename) {
        return new \App\Core\Log($channel, $filename);
    };
});

// App settings
$container->set('settings', function () {
    return [
        'displayErrorDetails' => $_ENV['APP_ENV'] === 'dev', // show error details on dev mode.
        'logErrors'           => true,
        'logErrorDetails'     => true,
        'url'                 => $_ENV['URL'],
        'baseUrl'             => $_ENV['BASE_URL'],
        'appKey'              => $_ENV['APP_KEY'],
        'authKey'             => $_ENV['AUTH_KEY'],
        'dbPrefix'            => $_ENV['DB_PREFIX'],
        'appName'             => $_ENV['APP_NAME'],
        'cronSecretKey'       => $_ENV['CRON_SECRET_KEY']

    ];
});


// add Container to Slim.
AppFactory::setContainer($container);


$container->set(ResponseFactoryInterface::class, function () {
    return AppFactory::determineResponseFactory();
    // return $app->getResponseFactory();
});

define('APP_KEY',$_ENV['APP_KEY']);
define('DB_PREFIX',$_ENV['DB_PREFIX']);
define('AUTH_KEY',$_ENV['AUTH_KEY']);
define('APP_NAME',$_ENV['APP_NAME']);
define('CRON_SECRET_KEY',$_ENV['CRON_SECRET_KEY']);
