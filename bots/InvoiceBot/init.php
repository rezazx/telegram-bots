<?php

use App\Core\BotRegistry;
use Bots\InvoiceBot\InvoiceBotHandler;

BotRegistry::register([
    'name' => 'invoice-bot',
    'handler' => InvoiceBotHandler::class,
    'webhook' => '/invoice-bot',
    'config' => __DIR__ . '/config.php',
]);
