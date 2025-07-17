<?php

namespace Bots\InvoiceBot;

use App\Telegram\BotInterface;
use App\Telegram\Telegram;
use App\Telegram\CommandRouter;
use App\Core\Config;
use Medoo\Medoo;

class Bot implements BotInterface
{
    protected Telegram $telegram;
    protected CommandRouter $router;
    protected array $config;

    public function __construct(Medoo $db, array $update)
    {
        $this->config = require __DIR__ . '/config.php';
        $token = $this->config['token'];

        $this->telegram = new Telegram($db, $token, $update);
        $this->router = new CommandRouter();
    }

    public function run(): void
    {
        $this->router->register('/start', function () {
            $this->telegram->sender->sendMessage($this->telegram->chatId(), "سلام خوش اومدی 🎉");
        });

        $this->router->dispatchText($this->telegram->messageText());
    }

    public function getName(): string
    {
        return 'invoice-bot';
    }
}
