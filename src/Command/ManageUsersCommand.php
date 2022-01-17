<?php

namespace App\Command;

use App\Entity\Colleague;
use App\Repository\ColleagueRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ManageUsersCommand extends Command
{
    protected static $defaultName = 'app:manage-users';
    protected static string $defaultDescription = 'Manage colleague information.';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('name', InputArgument::OPTIONAL, 'Enter colleague name')
            ->addArgument('email', InputArgument::OPTIONAL, 'Enter colleague email')
            ->addArgument('notes', InputArgument::OPTIONAL, 'Enter your notes')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Enter name:')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Enter email:')
            ->addOption('notes', null, InputOption::VALUE_REQUIRED, 'Enter notes:')
        ;
    }


    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('name');
        //$arg2 = $input->getArgument('email');
        //$arg3 = $input->getArgument('notes');

        //if ($arg1) {
        //    $io->note(sprintf('You passed an argument: %s', $arg1));
        //}

        list($name, $email, $notes) = $this->validateUser($io);

        //$repo = $this->entityManager->getRepository(Colleague::class);

        $io->success('Success! You have saved the information for '.$name.','.$email.','.$notes.' in the database.');

        return 0;
    }

    /**
     * @param SymfonyStyle $io
     * @return array
     */
    protected function validateUser(SymfonyStyle $io): array
    {
        $name = $io->ask('Enter Name:');
        //$name = $input->getOption('name');
            while($name == '')
            {
                $io->writeln(
                    'Name should not be blank');
                $name = $io->ask('Enter Name:');
            }
        $email = $io->ask('Enter Email:');
            while($email == '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $io->writeln(
                        'Email format is not correct!');
                }
                elseif($email == '') {
                    $io->writeln(
                        'Email should not be blank');
                }
                $email = $io->ask('Enter Email:');
            }
        $notes = $io->ask('Enter Notes:');

            /** @var ColleagueRepository $repo */
        $repo = $this->entityManager->getRepository(Colleague::class);
        $newColleague = new Colleague();
        $newColleague->setName($name);
        $newColleague->setEmail($email);
        $newColleague->setNotes($notes);
        $newColleague->setPicture('');
        $newColleague->setCreatedAt(new \DateTime('now'));
        $this->entityManager->persist($repo);

        return array($name, $email, $notes);
    }
}
