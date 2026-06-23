<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\Booster\BoosterOpenHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/me')]
final class UserController extends AbstractController
{
    
    #[Route('/boosters', name: 'api_my_boosters', methods: ['GET'])]
    public function indexBoosters(BoosterOpenHandler $handler): Response
    {
        $boosters = $handler->handle($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($booster): array => $booster->toArray(),
            $boosters,
        ));
    }

    #[Route('/cards', name: 'api_my_cards', methods: ['GET'])]
    public function indexCards(BoosterOpenHandler $handler): Response
    {
        $boosters = $handler->handle($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($booster): array => $booster->toArray(),
            $boosters,
        ));
    }

    #[Route('/currency', name: 'api_my_currency', methods: ['GET'])]
    public function indexCards(BoosterOpenHandler $handler): Response
    {
        $boosters = $handler->handle($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($booster): array => $booster->toArray(),
            $boosters,
        ));
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
