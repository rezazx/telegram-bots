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

    public static function setWebhookUrl($webhook_url,$bot_token): array
    {
        $api = "https://api.telegram.org/bot{$bot_token}/setWebhook";

        $response = file_get_contents($api . '?url=' . urlencode($webhook_url));
        return json_decode($response, true);
    }
}
