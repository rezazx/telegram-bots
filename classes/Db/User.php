<?php

namespace App\Db;

use Medoo\Medoo;

class User
{
    protected Medoo $db;
    protected string $table_users;
    protected string $table_user_bots;

    public function __construct(Medoo $db, string $prefix = DB_PREFIX)
    {
        $this->db = $db;
        $this->table_users = $prefix . 'users';
        $this->table_user_bots = $prefix . 'user_bots';
    }

    /**
     * Create or update user information
     */
    public function createOrUpdate(array $data): void
    {
        if ($this->db->has($this->table_users, ['telegram_id' => $data['telegram_id']])) {
            $this->db->update($this->table_users, [
                'full_name' => $data['full_name'] ?? null,
                'username' => $data['username'] ?? null,
                'phone' => $data['phone'] ?? null,
            ], ['telegram_id' => $data['telegram_id']]);
        } else {
            $this->db->insert($this->table_users, [
                'telegram_id' => $data['telegram_id'],
                'full_name' => $data['full_name'] ?? null,
                'username' => $data['username'] ?? null,
                'phone' => $data['phone'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

/**
     * Log the bot that the user has used
     */
    public function addBotToUser(int $telegram_id, string $bot_name): void
    {
        if (!$this->isUserInBot($telegram_id, $bot_name)) {
            $this->db->insert($this->table_user_bots, [
                'telegram_id' => $telegram_id,
                'bot_name' => $bot_name,
                'joined_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function isUserInBot(int $telegram_id, string $bot_name): bool
    {
        return $this->db->has($this->table_user_bots, [
            'telegram_id' => $telegram_id,
            'bot_name' => $bot_name
        ]);
    }

    public function getBotsForUser(int $telegram_id): array
    {
        return $this->db->select($this->table_user_bots, 'bot_name', [
            'telegram_id' => $telegram_id
        ]);
    }

    public function getUser(int $telegram_id): ?array
    {
        return $this->db->get($this->table_users, '*', [
            'telegram_id' => $telegram_id
        ]) ?: null;
    }

    /**
     * Automatic creation of tables
     */
    public function install(): void
    {
        $charset = 'utf8mb4';
    
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `{$this->table_users}` (
                `telegram_id` BIGINT PRIMARY KEY,
                `full_name` VARCHAR(255),
                `username` VARCHAR(255),
                `phone` VARCHAR(50),
                `created_at` DATETIME
            );
        ");
    
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `{$this->table_user_bots}` (
                `telegram_id` BIGINT,
                `bot_name` VARCHAR(100),
                `joined_at` DATETIME,
                PRIMARY KEY (`telegram_id`, `bot_name`),
                FOREIGN KEY (`telegram_id`) REFERENCES `{$this->table_users}`(`telegram_id`) ON DELETE CASCADE
            );
        ");
    }
    
}
