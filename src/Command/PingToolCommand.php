<?php

namespace App\Command;

use App\Entity\Tool;
use App\Entity\ToolType;
use App\Service\ToolService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PingToolCommand extends Command
{
    protected static $defaultName = 'app:ping-tool';
    protected static $defaultDescription = 'Ping tool command by IP';
    private ManagerRegistry $managerRegistry;
    private ToolService $toolService;

    public function __construct(ManagerRegistry $managerRegistry, ToolService $toolService)
    {
        $this->managerRegistry = $managerRegistry;
        $this->toolService = $toolService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $toolType = $this->managerRegistry->getRepository(ToolType::class)->findOneBy(['type' => 'command_ping']);
        $tools = $this->managerRegistry->getRepository(Tool::class)->findBy(['type' => $toolType->getId(), 'deleted' => false]);
        unset($toolType);
        $countTool = 0;

        /** @var Tool $tool */
        foreach ($tools as $key => $tool) {
            unset($tools[$key]);

            $io->info("Tool {$tool->getName()}");
            exec("ping -c 1 {$tool->getAddress()}", $output, $result);
            $toolStatus = $this->toolService->buildToolStatus($tool, $result == 0, implode("\n", $output));
            $this->managerRegistry->getManager()->persist($toolStatus);

            $countTool += 1;
            if ($countTool % 20 == 0) {
                $this->managerRegistry->getManager()->flush();
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->success('Command Success');

        return Command::SUCCESS;
    }
}
