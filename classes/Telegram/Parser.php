<?php

namespace App\Telegram;

class Parser
{
    protected array $update;

    public function __construct(array $update = [])
    {
        $this->update = $update;
    }

    public function chatId(): ?int
    {
        return $this->update['message']['chat']['id'] ?? 
               $this->update['callback_query']['message']['chat']['id'] ?? null;
    }

    public function messageText(): ?string
    {
        return $this->update['message']['text'] ?? 
               $this->update['callback_query']['data'] ?? null;
    }

    public function messageType(): string
    {
        if (isset($this->update['message']['text'])) return 'text';
        if (isset($this->update['message']['photo'])) return 'photo';
        if (isset($this->update['message']['document'])) return 'document';
        if (isset($this->update['callback_query'])) return 'callback';
        return 'unknown';
    }

    public function callbackData(): ?string
    {
        return $this->update['callback_query']['data'] ?? null;
    }


    public function userId(): ?int
    {
        return $this->update['message']['from']['id'] ?? 
               $this->update['callback_query']['from']['id'] ?? null;
    }

    public function update(): array
    {
        return $this->update;
    }

    public function callbackId(): ?string
    {
        return $this->update['callback_query']['id'] ?? null;
    }

}
