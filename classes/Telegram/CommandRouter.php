<?php

namespace App\Telegram;

class CommandRouter
{
    protected array $textCommands = [];
    protected array $callbackCommands = [];
    protected array $fallbacks = [];
    protected array $callbackFallbacks = [];

    public function register(string $command, callable $handler): void
    {
        $this->textCommands[strtolower($command)] = $handler;
    }

    public function registerCallback(string $callback_data, callable $handler): void
    {
        $this->callbackCommands[$callback_data] = $handler;
    }

    public function registerFallback(callable $handler): void
    {
        $this->fallbacks[] = $handler;
    }

    public function registerCallbackFallback(callable $handler): void
    {
        $this->callbackFallbacks[] = $handler;
    }

    public function dispatchText(?string $text): void
    {
        $text = strtolower(trim($text ?? ''));

        if (isset($this->textCommands[$text])) {
            call_user_func($this->textCommands[$text]);
        } else {
            foreach ($this->fallbacks as $handler) {
                call_user_func($handler, $text);
            }
        }
    }

    public function dispatchCallback(?string $data): void
    {
        if (isset($this->callbackCommands[$data])) {
            call_user_func($this->callbackCommands[$data]);
        } else {
            foreach ($this->callbackFallbacks as $handler) {
                call_user_func($handler, $data);
            }
        }
    }
}
