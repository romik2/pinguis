<?php

namespace App\Service;

use GuzzleHttp\Client;

class TelegramBot
{
    public string $telegramToken;
    public string $telegramUrl = 'https://api.telegram.org/';

    public function __construct($telegramToken)
    {
        $this->telegramToken = $telegramToken;
    }

    public function sendMessages($params): void
    {
        $client = new Client();
        $response = $client->request('POST', "{$this->telegramUrl}bot{$this->telegramToken}/sendMessage", [
            'json' => $params
        ]);
    }
}