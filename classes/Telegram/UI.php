<?php

namespace App\Telegram;

class UI
{
    public function replyKey(array $buttons, bool $resize = true, bool $one_time = false): array
    {
        return [
            'reply_markup' => [
                'keyboard' => $buttons,
                'resize_keyboard' => $resize,
                'one_time_keyboard' => $one_time
            ]
        ];
    }

    public function inlineKey(array $buttons): array
    {
        return [
            'reply_markup' => [
                'inline_keyboard' => $buttons
            ]
        ];
    }

    public function removeKey(): array
    {
        return [
            'reply_markup' => [
                'remove_keyboard' => true
            ]
        ];
    }

    public function button(string $text): array
    {
        return ['text' => $text];
    }

    public function inlineButton(string $text, string $callback_data): array
    {
        return ['text' => $text, 'callback_data' => $callback_data];
    }
}
