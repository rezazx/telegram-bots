<?php

namespace App\Telegram;

use App\Db\State;
use Medoo\Medoo;

class Chat
{
    protected State $state;

    public function __construct(Medoo $db, string $bot_name, string $prefix = 'tb_')
    {
        $this->state= new State($db,$bot_name,$prefix);
    }

    public function setState(int $user_id, string $state, array $data = []): void
    {
        $this->state->set($user_id,$state,$data);
    }

    public function getState(int $user_id): ?array
    {
        return $this->state->get($user_id);
    }

    public function clearState(int $user_id): void
    {
        $this->state->clear($user_id);
    }
}
