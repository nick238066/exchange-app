<?php

namespace App\Traits;

use Illuminate\Notifications\Notifiable;

trait TelegramNotifiable
{
    use Notifiable;

    protected $chatId;

    // babenkoivan/telegram-notifications 設定 char ID 用
    public function routeNotificationForTelegram()
    {
        if($this->chatId){
            return $this->chatId;
        }
        return config('telegram.admin_chat_id', '149248691');
    }

    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }
}
