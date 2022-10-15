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

class PingToolPortCommand extends Command
{
    protected static $defaultName = 'app:ping-tool-port';
    protected static $defaultDescription = 'Ping tool port ip';
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
        $toolType = $this->managerRegistry->getRepository(ToolType::class)->findOneBy(['type' => 'ping_port']);
        $tools = $this->managerRegistry->getRepository(Tool::class)->findBy(['type' => $toolType->getId()]);
        $waitTimeoutInSeconds = 1;
        $paramsSendMessages = [];

        /** @var Tool $tool */
        foreach ($tools as $tool) {
            $io->info("Tool {$tool->getName()}");
            $address = explode(":", $tool->getAddress());
            dd($address);
            try {
                $fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds);
                if($fp){   
                    list($toolStatus, $paramsSendMessages) = $this->toolService->buildToolStatus($tool, true, $paramsSendMessages);
                } else {
                    list($toolStatus, $paramsSendMessages) = $this->toolService->buildToolStatus($tool, false, $paramsSendMessages, 'Error');
                } 
                fclose($fp);
            } catch (\Exception $ex) {
                list($toolStatus, $paramsSendMessages) = $this->toolService->buildToolStatus($tool, false, $paramsSendMessages, "\n{$ex->getMessages()}");
            }
            $this->entityManager->persist($toolStatus);
        }
        
        $this->entityManager->flush();

        foreach ($paramsSendMessages as $params) {
            $this->telegramBot->sendMessages($params);
        }

        $io->success('Command Success');
        
        return Command::SUCCESS;
    }
}
