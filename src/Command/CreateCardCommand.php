<?php

namespace App\Command;

use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:card:create', description: 'Create a new card')]
class CreateCardCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name        = $io->ask('Name');
        $type        = $io->choice('Type', ['Warrior', 'Defender', 'Healer', 'Champion']);
        $color       = $io->choice('Color', ['Red', 'Green', 'Blue']);
        $attack      = $io->ask('Attack (leave empty if none)');
        $hp          = $io->ask('HP (leave empty if none)');
        $heal        = $io->ask('Heal amount (leave empty if none)');
        $description = $io->ask('Description (leave empty if none)');

        $card = new Card();
        $card->setName($name);
        $card->setType($type);
        $card->setColor($color);
        $card->setAttack($attack !== null ? (int) $attack : null);
        $card->setHp($hp !== null ? (int) $hp : null);
        $card->setHeal($heal !== null ? (int) $heal : null);
        $card->setDescription($description);

        $this->em->persist($card);
        $this->em->flush();

        $io->success("Card \"$name\" created with ID {$card->getId()}.");
        return Command::SUCCESS;
    }
}