<?php

return [
    'admin_chat_id' => env('TELEGRAM_ADMIN_CHAT_ID'),
    'admin_chat_ids' => json_decode(env('TELEGRAM_ADMIN_CHAT_IDS'), true) ?: ["149248691"],
    'bot_username' => env('TELEGRAM_BOT_USERNAME'),
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'channel_id' => env('TELEGRAM_CHANNEL_ID'),
];