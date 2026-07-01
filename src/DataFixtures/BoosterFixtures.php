<?php

namespace App\DataFixtures;

use App\Entity\Booster;
use App\Entity\Extension;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoosterFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var list<array{name: string, cost: int, cardCount: int, rarityWeights: array<string, int>, extension: string}>
     */
    private const BOOSTERS = [
        [
            'name' => 'Cyber Genesis - Standard',
            'cost' => 100,
            'cardCount' => 5,
            'rarityWeights' => ['common' => 70, 'rare' => 25, 'legendary' => 5],
            'extension' => ExtensionFixtures::CYBER_GENESIS,
        ],
        [
            'name' => 'Cyber Genesis - Premium',
            'cost' => 250,
            'cardCount' => 5,
            'rarityWeights' => ['common' => 50, 'rare' => 35, 'legendary' => 15],
            'extension' => ExtensionFixtures::CYBER_GENESIS,
        ],
        [
            'name' => 'Neon Uprising - Standard',
            'cost' => 120,
            'cardCount' => 5,
            'rarityWeights' => ['common' => 65, 'rare' => 28, 'legendary' => 7],
            'extension' => ExtensionFixtures::NEON_UPRISING,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::BOOSTERS as $data) {
            $booster = new Booster();
            $booster->setName($data['name']);
            $booster->setCost($data['cost']);
            $booster->setCardCount($data['cardCount']);
            $booster->setRarityWeights($data['rarityWeights']);
            $booster->setExtension($this->getReference($data['extension'], Extension::class));

            $manager->persist($booster);
        }

        $manager->flush();
    }

    /**
     * @return array<int, class-string>
     */
    public function getDependencies(): array
    {
        return [
            ExtensionFixtures::class,
        ];
    }
}
