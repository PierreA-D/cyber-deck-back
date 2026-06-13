<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin', description: 'Create an admin user')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email    = $io->ask('Email');
        $username = $io->ask('Username');
        $password = $io->askHidden('Password');

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();

        $io->success("Admin $username created.");
        return Command::SUCCESS;
    }
}