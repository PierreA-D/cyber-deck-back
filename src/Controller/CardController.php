<?php

namespace App\Controller;

use App\Handler\Card\GetCardHandler;
use App\Handler\Card\GetCardsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/cards')]
final class CardController extends AbstractController
{
    #[Route('', name: 'api_cards_index', methods: ['GET'])]
    public function index(GetCardsHandler $handler): JsonResponse
    {
        $cards = $handler->handle();

        return $this->json(array_map(
            static fn ($card): array => $card->toArray(),
            $cards,
        ));
    }

    #[Route('/{id}', name: 'api_cards_show', methods: ['GET'])]
    public function show(int $id, GetCardHandler $handler): JsonResponse
    {
        $card = $handler->handle($id);
        if (null === $card) {
            return $this->json(['error' => 'Card not found.'], 404);
        }

        return $this->json($card->toArray());
    }
}
