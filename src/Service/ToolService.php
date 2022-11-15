<?php

namespace App\Service;

use App\Entity\Tool;
use App\Entity\Status;
use App\Service\TelegramBot;
use App\Entity\ToolStatus;
use Doctrine\Persistence\ManagerRegistry;

class ToolService
{
    private ManagerRegistry $managerRegistry;
    private TelegramBot $telegramBot;

    public function __construct(ManagerRegistry $managerRegistry, TelegramBot $telegramBot)
    {
        $this->managerRegistry = $managerRegistry;
        $this->telegramBot = $telegramBot;
    }

    public function buildToolStatus(Tool $tool, bool $pingStatus = false, $messages = ""): ToolStatus
    {
        $status = $this->managerRegistry->getRepository(Status::class)->findOneBy(['service' => $pingStatus]);
        if (empty($tool->getStatus()) || $status->getId() != $tool->getStatus()->getId() and !empty($tool->getUser()->getTelegramChatId())) {
            $this->telegramBot->sendMessages([
                'chat_id' => $tool->getUser()->getTelegramChatId(),
                'text' => "Устройство {$tool->getName()} было {$status->getName()}"
            ]);
        }

        $toolStatus = new ToolStatus();
        $toolStatus
            ->setTool($tool)
            ->setStatus($status)
            ->setMessages($messages);

        return $toolStatus;
    }
}