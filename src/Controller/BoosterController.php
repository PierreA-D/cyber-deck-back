<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\InsufficientFundsException;
use App\Handler\Booster\BoosterOpenHandler;
use App\Handler\Booster\BoostersOpenHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/boosters')]
final class BoosterController extends AbstractController
{
    #[Route('', name: 'api_boosters_index', methods: ['GET'])]
    public function index(BoostersOpenHandler $handler): JsonResponse
    {
        $boosters = $handler->handle();

        return $this->json(array_map(
            static fn ($booster): array => $booster->toArray(),
            $boosters,
        ));
    }

    #[Route('/{id}/open', name: 'api_boosters_open', methods: ['POST'])]
    public function open(int $id, BoosterOpenHandler $handler): JsonResponse
    {
        try {
            $result = $handler->handle($this->getAuthenticatedUser(), $id);
        } catch (InsufficientFundsException $exception) {
            return $this->json(['error' => $exception->getMessage()], 400);
        }

        return $this->json($result->toArray(), 201);
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
