<?php

namespace App\Command;

use App\Entity\User;
use App\Services\RHService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'resto:people:import',
    description: 'Importer le peronnel du resto extérieur',
)]
class RestoPeopleImportCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RhService $rhService,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        $people = $this->rhService->getPeople();

        foreach ($people as $person) {
            $user = new User();
            $user->setUsername("{$person['firstname']} {$person['lastname']}");
            $user->setEmail($person['email']);
            $user->setFirstame($person['firstname']);
            $user->setLastName($person['lastname']);
            $user->setJobTitle($person['jobtitle']);
            $user->setEnabled(true);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $user->setPassword(password_hash($person['firstname'], PASSWORD_BCRYPT));
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
