<?php

namespace App\Controller;

use App\Dto\Game\GameCreateDto;
use App\Entity\User;
use App\Handler\Game\CreateGameHandler;
use App\Handler\Game\GetGameHandler;
use App\Handler\Game\GetGamesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/games')]
final class GameController extends AbstractController
{
    #[Route('', name: 'api_games_index', methods: ['GET'])]
    public function index(GetGamesHandler $handler): JsonResponse
    {
        $games = $handler->handle($this->getAuthenticatedUser());

        return $this->json(array_map(
            static fn ($game): array => $game->toArray(),
            $games,
        ));
    }

    #[Route('', name: 'api_games_create', methods: ['POST'])]
    public function create(Request $request, CreateGameHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON payload.'], 400);
        }

        try {
            $dto = GameCreateDto::fromArray($data);
            $game = $handler->handle($this->getAuthenticatedUser(), $dto);

            return $this->json($game->toArray(), 201);
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'api_games_show', methods: ['GET'])]
    public function show(int $id, GetGameHandler $handler): JsonResponse
    {
        $game = $handler->handle($this->getAuthenticatedUser(), $id);
        if (null === $game) {
            return $this->json(['error' => 'Game not found.'], 404);
        }

        return $this->json($game->toArray());
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
