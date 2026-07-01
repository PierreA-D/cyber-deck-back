<?php

namespace App\DataFixtures;

use App\Entity\Extension;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExtensionFixtures extends Fixture
{
    public const CYBER_GENESIS = 'extension-cyber-genesis';
    public const NEON_UPRISING = 'extension-neon-uprising';

    /**
     * @var array<string, string>
     */
    private const EXTENSIONS = [
        self::CYBER_GENESIS => 'Cyber Genesis',
        self::NEON_UPRISING => 'Neon Uprising',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EXTENSIONS as $reference => $name) {
            $extension = new Extension();
            $extension->setName($name);

            $manager->persist($extension);
            $this->addReference($reference, $extension);
        }

        $manager->flush();
    }
}
