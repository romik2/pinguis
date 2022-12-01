<?php

namespace App\Service;

use App\Entity\Setting;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;

class TelegramBot
{
    private ManagerRegistry $managerRegistry;
    public string $telegramUrl = 'https://api.telegram.org/';

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function sendMessages($params): int
    {
        $client = new Client();
        $telegramToken = $this->managerRegistry->getRepository(Setting::class)->findOneBy(['nameProgramm' => 'api_key_telegram']);

        if (!empty($telegramToken)) {
            $response = $client->request('POST', "{$this->telegramUrl}bot{$telegramToken->getValue()}/sendMessage", [
                'json' => $params
            ]);
            return $response->getStatusCode();
        }

        return 0;
    }
}