<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\Balance\GetBalanceHandler;
use App\Handler\Booster\BoosterOpenHandler;
use App\Handler\Card\GetCardsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/me', name: 'api_my_')]
final class UserController extends AbstractController
{
    
    #[Route('/boosters', name: 'boosters', methods: ['GET'])]
    public function indexBoosters(BoosterOpenHandler $handler): Response
    {
        $boosters = $handler->handleIndex($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($booster): array => $booster->toArray(),
            $boosters,
        ));
    }

    #[Route('/cards', name: 'cards', methods: ['GET'])]
    public function indexCards(GetCardsHandler $handler): Response
    {
        $cards = $handler->handleListCardByPlayer($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($card): array => $card->toArray(),
            $cards,
        ));
    }

    #[Route('/balance', name: 'balance', methods: ['GET'])]
    public function balance(GetBalanceHandler $handler): Response
    {
        $user = $this->getAuthenticatedUser();
        $balance = $handler->handle($user);

        return $this->json($balance);
    }

    private function getAuthenticatedUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Unauthorized.');
        }

        return $user;
    }
}
