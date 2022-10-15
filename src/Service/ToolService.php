<?php

namespace App\Service;

use App\Entity\Tool;
use App\Entity\Status;
use App\Entity\ToolStatus;
use Doctrine\Persistence\ManagerRegistry;

class ToolService
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function buildToolStatus(Tool $tool, bool $pingStatus = false, $paramsSendMessages = [], $messages = ""): array
    {
        $status = $this->managerRegistry->getRepository(Status::class)->findOneBy(['service' => $pingStatus]);
        if (empty($tool->getStatus()) || $status->getId() != $tool->getStatus()->getId()) {
            $paramsSendMessages[] = [
                'chat_id' => $tool->getUser()->getTelegramChatId(),
                'text' => "Устройство {$tool->getName()} было {$status->getName()}"
            ];
        }

        $toolStatus = new ToolStatus();
        $toolStatus
            ->setTool($tool)
            ->setStatus($status)
            ->setMessages($messages);

        return [$toolStatus, $paramsSendMessages];
    }
}