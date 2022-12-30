<?php

namespace App\Command;

use App\Entity\Tool;
use App\Entity\ToolType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PingToolPortCommand extends Command
{
    protected static $defaultName = 'app:ping-tool-all';
    protected static $defaultDescription = 'Add a short description for your command';
    private string $projectDir;
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry, $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->managerRegistry = $managerRegistry;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $toolType = $this->managerRegistry->getRepository(ToolType::class)->findOneBy(['type' => 'ping_port']);
        $tools = $this->managerRegistry->getRepository(Tool::class)->findBy(['type' => $toolType->getId(), 'deleted' => false]);
        
        /** @var Tool $tool */
        foreach ($tools as $key => $tool) {
            exec("{$this->projectDir}/bin/console {$toolType->getCommand()} {$tool->getId()}");
            unset($tools[$key]);
        }

        return Command::SUCCESS;
    }
}
