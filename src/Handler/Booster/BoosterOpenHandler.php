<?php

namespace App\Handler\Booster;

use App\Dto\Booster\BoosterOpenDto;
use App\Entity\BoosterOpening;
use App\Entity\Card;
use App\Entity\User;
use App\Entity\UserCard;
use App\Entity\UserCurrency;
use App\Enum\Card\CardRarity;
use App\Exception\InsufficientFundsException;
use App\Repository\BoosterOpeningRepository;
use App\Repository\BoosterRepository;
use App\Repository\CardRepository;
use App\Repository\UserCardRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BoosterOpenHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BoosterRepository $boosterRepository,
        private readonly CardRepository $cardRepository,
        private readonly UserCardRepository $userCardRepository,
        private readonly BoosterOpeningRepository $boosterOpeningRepository,
    ) {
    }

    public function handle(User $player, int $boosterId): BoosterOpenDto
    {
        return $this->entityManager->wrapInTransaction(
            fn (): BoosterOpenDto => $this->open($player, $boosterId),
        );
    }

    private function open(User $player, int $boosterId): BoosterOpenDto
    {
        $booster = $this->boosterRepository->find($boosterId);
        if (null === $booster) {
            throw new NotFoundHttpException('Booster not found.');
        }

        $extension = $booster->getExtension();
        if (null === $extension) {
            throw new NotFoundHttpException('This booster is not linked to any extension.');
        }

        $cost = $booster->getCost() ?? 0;
        $cardCount = $booster->getCardCount() ?? 0;
        if ($cost < 0 || $cardCount <= 0) {
            throw new NotFoundHttpException('This booster is misconfigured.');
        }

        $currency = $this->lockCurrency($player);

        if ($currency->getBalance() < $cost) {
            throw new InsufficientFundsException();
        }

        $currency->setBalance($currency->getBalance() - $cost);

        /** @var array<string, list<int>> $idsByRarity */
        $idsByRarity = [];
        /** @var list<int> $allIds */
        $allIds = [];

        foreach (CardRarity::cases() as $case) {
            $idsByRarity[$case->value] = $this->cardRepository->findIdsByRarity($case, $extension);
            $allIds = array_merge($allIds, $idsByRarity[$case->value]);
        }

        if ([] === $allIds) {
            throw new NotFoundHttpException('No cards available to open this booster.');
        }

        /** @var array<int, true> $drawnIds */
        $drawnIds = [];
        /** @var list<Card> $cards */
        $cards = [];
        /** @var list<int> $cardIds */
        $cardIds = [];

        for ($i = 0; $i < $cardCount; ++$i) {
            $rarity = $this->drawRarity($booster->getRarityWeights());

            $candidates = array_values(array_filter(
                $idsByRarity[$rarity->value],
                static fn (int $id): bool => !isset($drawnIds[$id]),
            ));

            if ([] === $candidates) {
                $candidates = array_values(array_filter(
                    $allIds,
                    static fn (int $id): bool => !isset($drawnIds[$id]),
                ));
            }

            if ([] === $candidates) {
                break;
            }

            $cardId = $candidates[random_int(0, \count($candidates) - 1)];
            $drawnIds[$cardId] = true;

            $card = $this->cardRepository->find($cardId);
            if (null === $card) {
                continue;
            }

            $this->grantCard($player, $card);

            $cards[] = $card;
            $cardIds[] = $card->getId();
        }

        $opening = new BoosterOpening();
        $opening->setPlayer($player);
        $opening->setBooster($booster);
        $opening->setCardsObtained($cardIds);
        $this->entityManager->persist($opening);

        $this->entityManager->persist($currency);
        $this->entityManager->flush();

        return BoosterOpenDto::fromResult($booster, $currency->getBalance(), $cards);
    }

    /**
     * @return list<BoosterOpenDto>
     */
    public function handleIndex(User $player): array
    {
        $userBoosters = $this->boosterOpeningRepository->findBy(['player' => $player], ['id' => 'ASC']);
        
        return array_map(
            static fn ($booster): BoosterOpenDto => BoosterOpenDto::fromEntity($booster->getBooster()),
            $userBoosters,
        );
    }

    private function grantCard(User $player, Card $card): void
    {
        $userCard = $this->userCardRepository->findOneBy(['player' => $player, 'card' => $card]);
        if (null !== $userCard) {
            $userCard->incrementQuantity();
        } else {
            $userCard = new UserCard();
            $userCard->setPlayer($player);
            $userCard->setCard($card);
            $this->entityManager->persist($userCard);
        }
    }

    private function lockCurrency(User $player): UserCurrency
    {
        $currency = $this->resolveCurrency($player);

        $currencyId = $currency->getId();
        if (null !== $currencyId) {
            $locked = $this->entityManager->find(
                UserCurrency::class,
                $currencyId,
                LockMode::PESSIMISTIC_WRITE,
            );
            if ($locked instanceof UserCurrency) {
                $currency = $locked;
            }
        }

        return $currency;
    }

    private function resolveCurrency(User $player): UserCurrency
    {
        $currency = $player->getUserCurrency();
        if (null === $currency) {
            $currency = new UserCurrency();
            $currency->setPlayer($player);
            $currency->setBalance(0);
        }

        return $currency;
    }

    /**
     * @param array<string, int> $weights
     */
    private function drawRarity(array $weights): CardRarity
    {
        /** @var array<string, int> $validWeights */
        $validWeights = [];
        foreach ($weights as $rarity => $weight) {
            if ($weight > 0 && null !== CardRarity::tryFrom((string) $rarity)) {
                $validWeights[(string) $rarity] = $weight;
            }
        }

        $total = array_sum($validWeights);
        if ($total <= 0) {
            return CardRarity::Common;
        }

        $roll = random_int(1, $total);
        $cumulative = 0;
        foreach ($validWeights as $rarity => $weight) {
            $cumulative += $weight;
            if ($roll <= $cumulative) {
                return CardRarity::from((string) $rarity);
            }
        }

        return CardRarity::Common;
    }
}
