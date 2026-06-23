<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\Collection\GetCollectionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/collection')]
final class CollectionController extends AbstractController
{
    #[Route('', name: 'api_collection_index', methods: ['GET'])]
    public function index(GetCollectionHandler $handler): JsonResponse
    {
        $items = $handler->handle($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($item): array => $item->toArray(),
            $items,
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
