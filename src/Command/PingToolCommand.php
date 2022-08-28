<?php

namespace App\Command;

use App\Entity\Status;
use App\Entity\Tool;
use App\Entity\ToolStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PingToolCommand extends Command
{
    protected static $defaultName = 'app:ping-tool';
    protected static $defaultDescription = 'Add a short description for your command';
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $managerRegistry;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;

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

        $tools = $this->managerRegistry->getRepository(Tool::class)->findAll();

        /** @var Tool $tool */
        foreach ($tools as $tool) {
            exec("ping -c 3 {$tool->getAddress()}", $output, $result);
            $toolStatus = $this->buildToolStatus($tool, $result == 0);
            $this->entityManager->persist($toolStatus);
        }
        $this->entityManager->flush();

        $io->success('Command Success');

        return Command::SUCCESS;
    }

    public function buildToolStatus(Tool $tool, bool $pingStatus = false)
    {
        $status = $this->managerRegistry->getRepository(Status::class)->findOneBy(['service' => $pingStatus]);

        $toolStatus = new ToolStatus();
        $toolStatus
            ->setToolId($tool)
            ->setStatusId($status);

        return $toolStatus;
    }
}
