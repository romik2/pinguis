<?php

namespace App\Command;

use App\Entity\Tool;
use App\Entity\ToolType;
use App\Service\ToolService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PingToolCommand extends Command
{
    protected static $defaultName = 'app:ping-tool-by-id';
    protected static $defaultDescription = 'Ping tool command by IP';
    private ManagerRegistry $managerRegistry;
    private ToolService $toolService;

    public function __construct(ManagerRegistry $managerRegistry, ToolService $toolService)
    {
        $this->managerRegistry = $managerRegistry;
        $this->toolService = $toolService;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setHelp('Ping tool port ip.')
            ->addArgument('toolId', InputArgument::REQUIRED, 'Id Tool.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $tool = $this->managerRegistry->getRepository(Tool::class)->find($input->getArgument('toolId'));

        $io->info("Tool {$tool->getName()}");
        list($address, $port) = explode(":", $tool->getAddress());

        try {
            exec("ping -c 1 {$tool->getAddress()}", $output, $result);
            $toolStatus = $this->toolService->buildToolStatus($tool, $result == 0, implode("\n", $output));
            $this->managerRegistry->getManager()->persist($toolStatus);
        } catch (\Exception $ex) {
            $toolStatus = $this->toolService->buildToolStatus($tool, false, "\n{$ex->getMessage()}");
        }

        $this->managerRegistry->getManager()->persist($toolStatus);
        $this->managerRegistry->getManager()->flush();

        $io->success('Command Success');
        
        return Command::SUCCESS;
    }
}
