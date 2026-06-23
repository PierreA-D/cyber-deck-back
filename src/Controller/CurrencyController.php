<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\Currency\GetCurrencyHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/currency')]
final class CurrencyController extends AbstractController
{
    #[Route('', name: 'api_currency_show', methods: ['GET'])]
    public function show(GetCurrencyHandler $handler): JsonResponse
    {
        $currency = $handler->handle($this->getAuthenticatedUser());

        return $this->json($currency->toArray());
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
