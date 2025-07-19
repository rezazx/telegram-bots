# Telegram Bot Framework

A modular PHP framework for building Telegram bots using Slim 4 and modern design patterns like DI, registration, and handlers.

> ⚠️ **Hey there! Just a heads up — this project isn’t finished yet.**  
> Some parts might change, and the documentation isn’t complete or 100% accurate.  
> Thanks for your patience!

## 📦 Features

- Multi-bot registration support
- Slim 4 integration
- Dependency Injection (PHP-DI)
- PSR-4 autoloading
- Centralized logging using Monolog
- Route grouping and dynamic dispatching
- Command router and UI helpers for Telegram bots
## 🧩 Requirements

Before using this Telegram bot, ensure that your environment meets the following requirements:

### ✅ Server Requirements
- PHP **8.0+** (Tested on PHP 8.0 and 8.1)
- Composer
- OpenSSL extension
- cURL extension
- PDO extension (with MySQL driver)
- Apache or Nginx  
- `mod_rewrite` enabled (for Apache)

### ✅ Telegram Requirements
- A **Telegram Bot Token** from [@BotFather](https://t.me/BotFather)
- (Optional) A verified Telegram account for testing

### ✅ Composer Packages
Install these dependencies via `composer install`:

- `slim/slim` - for write routes and api
- `catfan/medoo` - for work with db
- `vlucas/phpdotenv` — for loading `.env` config variables
- `guzzlehttp/guzzle` — for sending HTTP requests (if needed)
- `monolog/monolog` — for logging (optional, but recommended)
- `php-di/php-di` — for dependency injection container

### ✅ Database
- A MySQL or MariaDB database
- Required tables for handling:
  - Users
  - Messages
  - Sessions or states (if you're managing user flows)

---

## 🚀 Installation

Follow the steps below to install and run this Telegram Bot project on your local machine or server:

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-telegram-bot.git
cd your-telegram-bot
```

---

### 2. Install Dependencies

Make sure you have [Composer](https://getcomposer.org/) installed.

```bash
composer install
```

This will install the required packages such as:

* [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv) – for managing environment variables
* [`catfan/medoo`](https://medoo.in/) – lightweight database framework

---

### 3. Set Up Environment Variables

Copy the example environment file:

```bash
cp .env.example .env
```

Edit the `.env` file and update it with your own configuration:

```dotenv
DB_HOST=localhost
DB_PORT=3306
DB_NAME=db_name
DB_USER=root
DB_PASS=db_pass
DB_PREFIX=zxtb_                   # Optional: set custom table prefix

# Site settings
URL=https://example.com                # Your full website URL
BASE_URL=/telegram-bots                # Base path to your Telegram bot directory
APP_ENV=dev                            # App environment (e.g. dev, production)
APP_NAME=zx_telegram                   # A custom name for your app instance

# Key settings
APP_KEY="write your key"               # Application-level encryption key
AUTH_KEY="write your key"              # Authentication key for protected routes
CRON_SECRET_KEY="write your key"       # Secret key for accessing CRON routes

# bot setting
BOT_EX_TOKEN="example_bot_secret_key"
BOT_ex_ADMIN_ID="12345678
```

---

### 4. Create the Database Tables

Use the SQL file provided (if any), or manually create the necessary tables.
Make sure your database is up and running.

---

### ✅ You're all set!

Your bot should now be up and running.
Test it by sending a message to your bot on Telegram.


## ⚙️ Usage

To create and register a Telegram bot:

1. **Create a directory for your bot** inside the `/bot` folder, e.g. `/bot/InvoiceBot/`.

2. **Add an `init.php` file** inside your bot folder to register it:

```php
<?php

use App\Core\BotRegistry;
use Bots\InvoiceBot\InvoiceBotHandler;

BotRegistry::register([
    'name'    => 'invoice-bot', // unique identifier
    'handler' => InvoiceBotHandler::class, // must implement BotHandlerInterface
    'webhook' => '/invoice-bot', // route used in Telegram webhook
    'config'  => __DIR__ . '/config.php', // optional config path
]);
```

3. **Create your bot handler** class (e.g. `InvoiceBotHandler`) which must implement the `BotHandlerInterface` or extend the abstract class `BaseBotHandler`:

```php
<?php

namespace Bots\InvoiceBot;

use App\Interfaces\BotHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InvoiceBotHandler implements BotHandlerInterface
{
    public function handle(Request $request, Response $response, array $args = []): Response
    {
        // Your bot logic here
        return $response->withStatus(200);
    }
}
```

4. **Webhook URL**

If your `.env` includes:

```
URL=https://example.com
BASE_URL=/telegram-bots
```

And your bot's webhook is `/invoice-bot`, then your Telegram webhook URL becomes:

```
https://example.com/telegram-bots/bot/invoice-bot
```

5. **Register the webhook with Telegram**

Use a tool or API call to set your webhook URL. Example (replace TOKEN and URL):

```
https://api.telegram.org/bot<YOUR_TOKEN>/setWebhook?url=https://example.com/telegram-bots/bot/invoice-bot
```

---


## 🧱 Project Architecture

```
public_html/
├── website/                        ← Main website (optional)
│   └── index.php
│
├── telegram-bots/                  ← Root of the bot system
│   ├── bootstrap.php               ← Loads config and autoload
│   ├── index.php                   ← Entry point of the bot application
│
│   ├── bots/                       ← Bot-specific entry points and logic
│   │   ├── ExampleBot/
│   │   │   ├── init.php           ← Registers bot via BotRegistry
│   │   │   ├── ExampleBotHandler.php ← Main handler (extends BaseBotHandler or implements BotHandlerInterface)
│   │   │   ├── other.php          ← Additional logic/files
│   │   ├── DoubleExampleBot/      ← Another bot definition
│
│   ├── utils/                      ← Shared utilities (Tools.php, Validation.php, etc.)
│
│   ├── routes/                     ← Route definitions and handlers
│   │   ├── bot.php                 ← Bot-specific webhook routes
│   │   ├── web.php                 ← Web frontend routes
│   │   ├── api.php                 ← Optional API routes
│   │   ├── error.php               ← Error handling routes
│
│   ├── logs/                       ← Log files
│
│   ├── classes/
│   │   ├── telegram/               ← Telegram API classes (BaseBot, Keyboard, BotClient, etc.)
│   │   ├── users/                  ← User-related classes (User.php, StoreUser.php, etc.)
│   │   └── db/                     ← DB access and models (Connection.php, InvoiceTable.php, etc.)
│
│   ├── controller/
│   │   ├── Invoice/                ← Controllers for invoice bot
│   │   └── Store/                  ← Controllers for store bot
│
│   ├── files/                      ← Uploaded and generated files (PDFs, images, etc.)
│   │   ├── invoice-bot/invoices/
│   │   ├── store-admin/photos/
│
│   ├── vendor/                     ← Composer-installed libraries
│   ├── .env                        ← Environment config (tokens, DB settings)
│   └── config/                     ← Optional separate config files (telegram.php, database.php, etc.)
```

## 📚 Documentation

For full documentation and advanced usage examples, please visit:

[https://zxbots.space/docs/telegram-bots](https://zxbots.space/docs/telegram-bots)

---

## 📬 Contact

For support or inquiries, feel free to reach out:

- Email: Reza.zx@live.com 
- Telegram: [@zx_reza](https://t.me/zx_reza)

## License

MIT License

