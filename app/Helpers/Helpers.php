<?php

use App\Notifications\TelegramNotification;
use App\Telegram;

if (!function_exists('telegram')) {
    /**
     * 發送訊息到 telegram
     * @param string $msg
     * @param array $options TelegramMessage method => argument
     */
    function telegram(string $msg, array $options = [])
    {
        if (app()->runningUnitTests()) {
            config(['telegram.isCalled' => true]);
            return;
        }

        $chat_id = $options["chatId"] ?? "";

        //if (app()->environment('production') && config('telegram.bot_token')) {
        if (config('telegram.bot_token')) {
            Notification::send(
                new Telegram($chat_id),
                new TelegramNotification($msg, $options)
            );
        }
    }
}