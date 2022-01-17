<?php

namespace App\Command;

use App\Entity\Colleague;
use App\Repository\ColleagueRepository;
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

    public function __construct(EntityManagerInterface $entityManager, $projectDir)
    {
        $this->projectDir = $projectDir;
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
            ->addOption('notes', null, InputOption::VALUE_OPTIONAL, 'Enter notes:')
        ;
    }


    /**
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //dd($this->projectDir);
        $io = new SymfonyStyle($input, $output);

        //List entered data
        list($name, $email, $notes) = $this->validateUser($io);

        /** @var ColleagueRepository $repo */
        //Test if Repository is working properly...
        //$repo = $this->entityManager->getRepository(Colleague::class);
        //$e = $repo->findAll(); dd($e);

        //Enter record in the database
        $newColleague = new Colleague();
        $newColleague->setName($name);
        $newColleague->setEmail($email);
        $newColleague->setNotes($notes);
        $newColleague->setCreatedAt(new \DateTime('now'));
        $this->entityManager->persist($newColleague);
        $this->entityManager->flush();

        //dd($newColleague);

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

        return array($name, $email, $notes);
    }
}
