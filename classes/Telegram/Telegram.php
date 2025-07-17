<?php

namespace App\Telegram;

use App\Telegram\Sender;
use App\Telegram\Parser;
use App\Telegram\UI;
use App\Telegram\Chat;
use Medoo\Medoo;

class Telegram
{
    public Sender $sender;
    public Parser $parser;
    public UI $ui;
    public Chat $chat;

    public function __construct(Medoo $db,string $token, array $update = [], string $bot_name='tb', string $prefix = 'tb_')
    {
        $this->sender = new Sender($token);
        $this->parser = new Parser($update);
        $this->ui     = new UI();
        $this->chat   = new Chat($db,$bot_name,$prefix);
    }

    /**
     * Shortcut to get the current chat_id
     */
    public function chatId(): ?int
    {
        return $this->parser->chatId();
    }

    /**
     * Shortcut to get the current message text
     */
    public function messageText(): ?string
    {
        return $this->parser->messageText();
    }
}
