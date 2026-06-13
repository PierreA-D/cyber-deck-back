<?php

namespace App\Command;

use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:card:update', description: 'Update an existing card')]
class UpdateCardCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CardRepository $cardRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $id   = $io->ask('Card ID to update');
        $card = $this->cardRepository->find((int) $id);

        if (null === $card) {
            $io->error("Card #$id not found.");
            return Command::FAILURE;
        }

        $io->note("Editing \"{$card->getName()}\" — leave empty to keep current value.");

        $name        = $io->ask("Name [{$card->getName()}]");
        $type        = $io->choice('Type', ['Warrior', 'Defender', 'Healer', 'Champion'], $card->getType());
        $color       = $io->choice('Color', ['Red', 'Green', 'Blue'], $card->getColor());
        $attack      = $io->ask("Attack [{$card->getAttack()}]");
        $hp          = $io->ask("HP [{$card->getHp()}]");
        $heal        = $io->ask("Heal [{$card->getHeal()}]");
        $description = $io->ask("Description [{$card->getDescription()}]");

        if ($name)        $card->setName($name);
        if ($type)        $card->setType($type);
        if ($color)       $card->setColor($color);
        if ($attack !== null) $card->setAttack((int) $attack);
        if ($hp !== null)     $card->setHp((int) $hp);
        if ($heal !== null)   $card->setHeal((int) $heal);
        if ($description)     $card->setDescription($description);

        $this->em->flush();

        $io->success("Card #{$card->getId()} updated.");
        return Command::SUCCESS;
    }
}