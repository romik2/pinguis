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

class PingPortByToolCommand extends Command
{
    protected static $defaultName = 'app:ping-tool-port-by-id';
    protected static $defaultDescription = 'Ping tool port ip';
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
            $fp = fsockopen($address, $port, $errCode, $errStr, 45);
            if($fp){   
                $toolStatus = $this->toolService->buildToolStatus($tool, true);
            } else {
                $toolStatus = $this->toolService->buildToolStatus($tool, false, 'Error');
            } 
            fclose($fp);
        } catch (\Exception $ex) {
            $toolStatus = $this->toolService->buildToolStatus($tool, false, "\n{$ex->getMessage()}");
        }

        $this->managerRegistry->getManager()->persist($toolStatus);
        $this->managerRegistry->getManager()->flush();

        $io->success('Command Success');
        
        return Command::SUCCESS;
    }
}
