<?php

namespace App;

use App\Traits\TelegramNotifiable;

class Telegram
{
    use TelegramNotifiable;

    public function __construct($chatId = null)
    {
        $this->chatId = $chatId ?? $this->chatId;
    }
}
