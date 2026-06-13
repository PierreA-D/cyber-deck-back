<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    #[Route('/register', name: 'api_auth_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password']) || empty($data['username'])) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword($hasher->hashPassword($user, $data['password']));
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        return $this->json([
            'message' => 'User created',
            'email'   => $user->getEmail(),
        ], 201);
    }

    #[Route('/me', name: 'api_auth_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'id'         => $user->getId(),
            'email'      => $user->getEmail(),
            'username'   => $user->getUsername(),
        ]);
    }
}