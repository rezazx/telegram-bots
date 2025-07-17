<?php

namespace App\Telegram;

use GuzzleHttp\Client;

class Sender
{
    protected string $token;
    protected Client $http;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->http = new Client([
            'base_uri' => "https://api.telegram.org/bot{$token}/"
        ]);
    }

    protected function request(string $method, array $params = [])
    {
        $response = $this->http->post($method, ['json' => $params]);
        return json_decode($response->getBody(), true);
    }

    public function sendMessage(int $chat_id, string $text, array $options = []): array
    {
        return $this->request('sendMessage', array_merge([
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ], $options));
    }

    public function sendPhoto(int $chat_id, string $photo_url_or_id, array $options = []): array
    {
        return $this->request('sendPhoto', array_merge([
            'chat_id' => $chat_id,
            'photo' => $photo_url_or_id
        ], $options));
    }

    public function sendDocument(int $chat_id, string $file_url_or_id, array $options = []): array
    {
        return $this->request('sendDocument', array_merge([
            'chat_id' => $chat_id,
            'document' => $file_url_or_id
        ], $options));
    }

    public function sendChatAction(int $chat_id, string $action = 'typing'): array
    {
        return $this->request('sendChatAction', [
            'chat_id' => $chat_id,
            'action' => $action
        ]);
    }

    public function answerCallbackQuery(string $callback_query_id, array $options = []): array
    {
        return $this->request('answerCallbackQuery', array_merge([
            'callback_query_id' => $callback_query_id
        ], $options));
    }

}
