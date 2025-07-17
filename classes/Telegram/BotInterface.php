<?php

namespace App\Telegram;

interface BotInterface
{
    /**
     * Execute the main bot logic
     */
    public function run(): void;

    /**
     * Get the bot name — for logging or dispatching
     */
    public function getName(): string;
}
