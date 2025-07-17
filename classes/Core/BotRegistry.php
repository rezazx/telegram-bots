<?php

namespace App\Core;

class BotRegistry
{
    protected static array $bots = [];

    public static function register(array $data): void
    {
        self::$bots[] = $data;
    }

    public static function all(): array
    {
        return self::$bots;
    }

    public static function findByWebhook(string $uri): ?array
    {
        foreach (self::$bots as $bot) {
            if ($bot['webhook'] === $uri) return $bot;
        }
        return null;
    }
}
