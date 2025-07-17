<?php

namespace App\Core;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Log
{
    protected Logger $logger;

    public function __construct(string $channel = 'app', string $filename = 'app.log')
    {
        $logDir = __DIR__ . '/../../logs/';

        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $filePath = $logDir . $filename;

        $stream = new StreamHandler($filePath, Logger::DEBUG);

        $formatter = new LineFormatter(null, null, true, true);
        $stream->setFormatter($formatter);

        $this->logger = new Logger($channel);
        $this->logger->pushHandler($stream);
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
