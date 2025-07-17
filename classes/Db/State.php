<?php

namespace App\Db;

use Medoo\Medoo;

class State
{
    protected Medoo $db;
    protected string $table;

    public function __construct(Medoo $db, string $bot_name, string $prefix = 'tg_')
    {
        $this->db = $db;
        $this->table = $prefix . $bot_name . '_state';
    }

    /**
     * Save or update the user’s state and data
     */
    public function set(int $telegram_id, string $state, array $data = []): void
    {
        if ($this->has($telegram_id)) {
            $this->db->update($this->table, [
                'state' => $state,
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'updated_at' => date('Y-m-d H:i:s')
            ], ['telegram_id' => $telegram_id]);
        } else {
            $this->db->insert($this->table, [
                'telegram_id' => $telegram_id,
                'state' => $state,
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Retrieve the user’s status
     */
    public function get(int $telegram_id): ?array
    {
        $row = $this->db->get($this->table, '*', [
            'telegram_id' => $telegram_id
        ]);

        if (!$row) return null;

        $row['data'] = json_decode($row['data'], true) ?? [];
        return $row;
    }

    /**
     * Delete the user’s state
     */
    public function clear(int $telegram_id): void
    {
        $this->db->delete($this->table, [
            'telegram_id' => $telegram_id
        ]);
    }

    /**
     * Does the user have a state?
     */
    public function has(int $telegram_id): bool
    {
        return $this->db->has($this->table, [
            'telegram_id' => $telegram_id
        ]);
    }

    /**
     * Create the state table for a specific bot
     */
    public function install(): void
    {
        $charset = 'utf8mb4';

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `telegram_id` BIGINT PRIMARY KEY,
                `state` VARCHAR(100),
                `data` TEXT,
                `updated_at` DATETIME
            );
        ");
    }
}
