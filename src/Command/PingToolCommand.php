<?php

namespace App\Command;

use App\Entity\Tool;
use App\Entity\Status;
use App\Entity\ToolStatus;
use App\Service\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PingToolCommand extends Command
{
    protected static $defaultName = 'app:ping-tool';
    protected static $defaultDescription = 'Ping tool command by IP';
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $managerRegistry;
    private TelegramBot $telegramBot;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry, TelegramBot $telegramBot)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
        $this->telegramBot = $telegramBot;

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $paramsSendMessages = [];
        $tools = $this->managerRegistry->getRepository(Tool::class)->findAll();

        /** @var Tool $tool */
        foreach ($tools as $tool) {
            $io->info("Tool {$tool->getName()}");
            exec("ping -c 3 {$tool->getAddress()}", $output, $result);
            list($toolStatus, $paramsSendMessages) = $this->buildToolStatus($tool, $result == 0, $paramsSendMessages);
            $this->entityManager->persist($toolStatus);
        }
        $this->entityManager->flush();

        foreach ($paramsSendMessages as $params) {
            $this->telegramBot->sendMessages($params);
        }

        $io->success('Command Success');

        return Command::SUCCESS;
    }

    public function buildToolStatus(Tool $tool, bool $pingStatus = false, $paramsSendMessages = []): array
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
            ->setStatus($status);

        return [$toolStatus, $paramsSendMessages];
    }
}
