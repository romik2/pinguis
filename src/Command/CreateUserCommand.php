<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    protected static $defaultDescription = 'Command for create user.';
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setHelp('Command for create user.')
            ->addArgument('username', InputArgument::REQUIRED, 'The username.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Create User');
        $output->writeln('Username: ' . $username = $input->getArgument('username'));
        $output->writeln('Password: ' . $password = $input->getArgument('password'));

        $user = new User();

        $encoded = $this->passwordEncoder->encodePassword($user, $password);
        $user
            ->setUsername($username)
            ->setPassword($encoded)
            ->setRoles(['ROLE_ADMIN']);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = $errors->get(0)->getMessage();

            $output->writeln($errorsString);
            return Command::FAILURE;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('User successfully generated!');

        return Command::SUCCESS;
    }
}
