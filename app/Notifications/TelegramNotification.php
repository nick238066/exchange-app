<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Fluent;
use TelegramNotifications\Messages\TelegramMessage;
use TelegramNotifications\TelegramChannel;

class TelegramNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $chatId = null;
    protected $message = '';
    protected $options = [];

    /**
     * Create a new notification instance.
     *
     * @param $message
     * @param array $options TelegramMessage method => argument
     */
    public function __construct($message, array $options = [])
    {
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->chatId) {
            $notifiable->setChatId($this->chatId);
        }

        return [TelegramChannel::class];
    }

    public function toTelegram()
    {
        $result = (new TelegramMessage())
            ->text($this->message);

        foreach ($this->options as $method => $argument)
        {
            call_user_func([$result, $method], $argument);
        }

        return $result;
    }
}
