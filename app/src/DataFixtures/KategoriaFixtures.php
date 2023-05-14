<?php
/**
 * Kategoria fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Kategoria;
use App\Entity\Ksiazka;
use DateTimeImmutable;

/**
 * Class KategoriaFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class KategoriaFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(10, 'kategorie', function (int $i) {
            $kategoria = new Kategoria();
            $kategoria->setNazwa($this->faker->randomElement($array = ['Horror','Kryminał', 'Fantasy', 'Thriller', 'Dramat', 'Biografia', 'Obyczajowa', 'Romans', 'Bajka']));

            $ksiazka = $this->getRandomReference('ksiazki');
            $kategoria->addKsiazki($ksiazka);

            return $kategoria;
        });

        $this->manager->flush();
    }
    public function getDependencies(): array
    {
        return [KsiazkaFixtures::class];
    }
}