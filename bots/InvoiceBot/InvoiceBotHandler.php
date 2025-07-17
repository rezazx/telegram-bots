<?php
namespace Bots\InvoiceBot;
use Bots\InvoiceBot\Bot;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;



use App\Core\BaseBotHandler;

class InvoiceBotHandler extends BaseBotHandler
{
    protected Bot $bot;
    public function handle(Request $request, Response $response, array $args = []): Response
    {
        $path = $request->getUri()->getPath();
        $this->logger->info("InvoiceBot is run!");

        if (str_ends_with($path, '/admin')) {
            return $this->handleAdmin($request, $response);
        }


        return $this->handleWebhook($request, $response);
    }

    protected function handleWebhook(Request $request, Response $response): Response
    {
        // منطق webhook اصلی
         $this->logger->info("InvoiceBot handleWebhook start!");
        $update = json_decode($request->getBody()->getContents(), true);
        $this->bot = new Bot($this->db,$update);
        $this->bot->run();
        $this->logger->info("InvoiceBot handleWebhook end!");
        $response->getBody()->write("Webhook handled!");
        return $response->withStatus(200);
    }

    protected function handleAdmin(Request $request, Response $response): Response
    {
        // منطق پنل ادمین
        $response->getBody()->write("Admin panel here");
        return $response;
    }
}
