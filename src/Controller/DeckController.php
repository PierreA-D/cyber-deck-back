<?php

namespace App\Controller;

use App\Dto\Deck\DeckUpsertDto;
use App\Entity\User;
use App\Handler\Deck\CreateDeckHandler;
use App\Handler\Deck\DeleteDeckHandler;
use App\Handler\Deck\GetDeckHandler;
use App\Handler\Deck\GetDecksHandler;
use App\Handler\Deck\UpdateDeckHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/decks')]
final class DeckController extends AbstractController
{
    #[Route('', name: 'api_decks_index', methods: ['GET'])]
    public function index(GetDecksHandler $handler): JsonResponse
    {
        $decks = $handler->handle($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($deck): array => $deck->toArray(),
            $decks,
        ));
    }

    #[Route('', name: 'api_decks_create', methods: ['POST'])]
    public function create(Request $request, CreateDeckHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON payload.'], 400);
        }

        try {
            $dto = DeckUpsertDto::fromArray($data);
            $deck = $handler->handle($this->getAuthenticatedUser(), $dto);

            return $this->json($deck->toArray(), 201);
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'api_decks_show', methods: ['GET'])]
    public function show(int $id, GetDeckHandler $handler): JsonResponse
    {
        $deck = $handler->handle($this->getAuthenticatedUser(), $id);
        if (null === $deck) {
            return $this->json(['error' => 'Deck not found.'], 404);
        }

        return $this->json($deck->toArray());
    }

    #[Route('/{id}', name: 'api_decks_update', methods: ['PUT'])]
    public function update(int $id, Request $request, UpdateDeckHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON payload.'], 400);
        }

        try {
            $dto = DeckUpsertDto::fromArray($data);
            $deck = $handler->handle($this->getAuthenticatedUser(), $id, $dto);
            if (null === $deck) {
                return $this->json(['error' => 'Deck not found.'], 404);
            }

            return $this->json($deck->toArray());
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'api_decks_delete', methods: ['DELETE'])]
    public function delete(int $id, DeleteDeckHandler $handler): JsonResponse
    {
        $deleted = $handler->handle($this->getAuthenticatedUser(), $id);
        if (!$deleted) {
            return $this->json(['error' => 'Deck not found.'], 404);
        }

        return $this->json(null, 204);
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
