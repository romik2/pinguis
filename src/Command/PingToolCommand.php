<?php

namespace App\Command;

use App\Entity\Tool;
use App\Entity\ToolType;
use App\Service\TelegramBot;
use App\Service\ToolService;
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
    private ToolService $toolService;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry, TelegramBot $telegramBot, ToolService $toolService)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
        $this->telegramBot = $telegramBot;
        $this->toolService = $toolService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $paramsSendMessages = [];
        $toolType = $this->managerRegistry->getRepository(ToolType::class)->findOneBy(['type' => 'command_ping']);
        $tools = $this->managerRegistry->getRepository(Tool::class)->findBy(['type' => $toolType->getId(), 'deleted' => false]);

        /** @var Tool $tool */
        foreach ($tools as $tool) {
            $io->info("Tool {$tool->getName()}");
            $output = "";
            exec("ping -c 1 {$tool->getAddress()}", $output, $result);
            list($toolStatus, $paramsSendMessages) = $this->toolService->buildToolStatus($tool, $result == 0, $paramsSendMessages, implode("\n", $output));
            $this->entityManager->persist($toolStatus);
            $this->entityManager->flush();
        }

        foreach ($paramsSendMessages as $params) {
            $this->telegramBot->sendMessages($params);
        }

        $io->success('Command Success');

        return Command::SUCCESS;
    }
}
