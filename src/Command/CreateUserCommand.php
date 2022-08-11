<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // create 2 users houssem and zbaida, hash the password and add them to the database

        $user1 = new User();
        $user1->setUsername('houssem');
        $hashedPassword1 = $this->passwordHasher->hashPassword(
            $user1,
            'Houss_123'
        );
        $user1->setPassword($hashedPassword1);


        $this->em->persist($user1);

        $user2 = new User();
        $user2->setUsername('zbaida');
        $hashedPassword2 = $this->passwordHasher->hashPassword(
            $user2,
            'Zouba_123'
        );
        $user2->setPassword($hashedPassword2);
        $this->em->persist($user2);

        $this->em->flush();

        return Command::SUCCESS;
    }
}
