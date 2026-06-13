<?php

namespace App\Command;

use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:card:delete', description: 'Delete a card')]
class DeleteCardCommand extends Command
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

        $id   = $io->ask('Card ID to delete');
        $card = $this->cardRepository->find((int) $id);

        if (null === $card) {
            $io->error("Card #$id not found.");
            return Command::FAILURE;
        }

        $confirm = $io->confirm("Delete \"{$card->getName()}\" ? This cannot be undone.", false);
        if (!$confirm) {
            $io->note('Cancelled.');
            return Command::SUCCESS;
        }

        $this->em->remove($card);
        $this->em->flush();

        $io->success("Card #{$id} deleted.");
        return Command::SUCCESS;
    }
}